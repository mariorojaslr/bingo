<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carton;
use App\Services\PdfService;
use Illuminate\Support\Facades\DB;
use Exception;

class CartonController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GENERACIÓN DE CARTONES (LA BOMBA ATÓMICA ROLLS-ROYCE)
    |--------------------------------------------------------------------------
    | Algoritmo reescrito desde cero. Garantiza:
    | 1. Ordenamiento vertical perfecto.
    | 2. Cero colisiones matemáticas validando un Hash único.
    | 3. Distribución perfecta de vacíos vs números.
    */

    public function generarCartones(Request $request)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1|max:500000',
            'serie' => 'required|string'
        ]);

        $cantidad = (int) $request->cantidad;
        $serie = $request->serie;
        $generados = 0;
        $colisiones_evitadas = 0;

        // Caché en RAM de todos los hashes de esta serie para validación O(1)
        $hashesExistentes = Carton::where('serie', $serie)->pluck('hash')->toArray();
        $hashesSet = array_flip($hashesExistentes);

        DB::beginTransaction();
        try {
            $cartonesToInsert = [];
            // Para mantener el consecutivo
            $lastNumero = Carton::where('serie', $serie)->max('numero_carton') ?? 0;

            while ($generados < $cantidad) {
                
                // 1. Crear matriz matemática
                $grilla = $this->generarGrillaArgentina();
                
                // 2. Serializar a Hash
                $hash = md5(json_encode($grilla));

                // 3. Antibombas: Validar si la combinatoria ya existe en todo el universo
                if (isset($hashesSet[$hash])) {
                    $colisiones_evitadas++;
                    continue; // Matemática idéntica detectada. Desechar este cartón.
                }

                $hashesSet[$hash] = true; // Lo registramos temporalmente
                $lastNumero++;
                $generados++;

                $cartonesToInsert[] = [
                    'serie' => $serie,
                    'numero_carton' => $lastNumero,
                    'formato' => 'ARG',
                    'estado' => 'disponible',
                    'grilla' => json_encode($grilla),
                    'hash' => $hash,
                    // 'organizador_id' => ... -> PRÓXIMAMENTE EN INYECCIÓN TENANT
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                // Chunking automático para no reventar la RAM del servidor
                if (count($cartonesToInsert) === 500) {
                    Carton::insert($cartonesToInsert);
                    $cartonesToInsert = [];
                }
            }

            if (!empty($cartonesToInsert)) {
                Carton::insert($cartonesToInsert);
            }

            DB::commit();

            return redirect()->back()->with('success', "¡Éxito nivel Dios! Se generaron $generados cartones perfectos. Colisiones matemáticas idénticas bloqueadas: $colisiones_evitadas.");

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Fallo Crítico: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VISOR PROFESIONAL DE CARTONES
    |--------------------------------------------------------------------------
    */

    public function listado(Request $request)
    {
        $columnas = (int) $request->get('columnas', 3);
        $filas = (int) $request->get('filas', 2);

        $columnas = max(1, min(4, $columnas));
        $filas = max(1, min(4, $filas));

        $porPagina = $columnas * $filas;

        if ($request->filled('numero')) {
            $numero = $request->numero;
            $posicion = Carton::where('numero_carton', '<=', $numero)->count();
            $pagina = (int) ceil($posicion / $porPagina);
        } else {
            $pagina = $request->get('page', 1);
        }

        $cartones = Carton::orderBy('numero_carton')
            ->paginate($porPagina, ['*'], 'page', $pagina);

        return view('admin.cartones.listado', compact(
            'cartones',
            'columnas',
            'filas',
            'porPagina'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | GENERADOR MATEMÁTICO PURO - ALGORITMO ROLLS-ROYCE
    |--------------------------------------------------------------------------
    */

    private function generarGrillaArgentina()
    {
        // 1. GENERAR MAPA DE HUECOS (MÁSCARA 3x9)
        // Reglas estrictas: Exactamente 5 números por fila. Mínimo 1 número por columna.
        $valido = false;
        while (!$valido) {
            $patrones = [
                array_fill(0, 9, 0),
                array_fill(0, 9, 0),
                array_fill(0, 9, 0)
            ];
            
            for ($f = 0; $f < 3; $f++) {
                // Elegir aleatoriamente 5 columnas para esta fila
                $indices = array_rand(range(0, 8), 5);
                foreach ($indices as $col) {
                    $patrones[$f][$col] = 1;
                }
            }

            // Chequeo de seguridad: asegurar que toda columna tenga al menos un número y no más de 3
            $valido = true;
            for ($c = 0; $c < 9; $c++) {
                $sumaCol = $patrones[0][$c] + $patrones[1][$c] + $patrones[2][$c];
                if ($sumaCol === 0) {
                    $valido = false;
                    break;
                }
            }
        }

        // 2. INYECCIÓN DE NÚMEROS Y ORDENAMIENTO ESTRICTO
        $rangos = [
            range(1, 9), range(10, 19), range(20, 29),
            range(30, 39), range(40, 49), range(50, 59),
            range(60, 69), range(70, 79), range(80, 90)
        ];

        $grilla = [
            array_fill(0, 9, 0),
            array_fill(0, 9, 0),
            array_fill(0, 9, 0)
        ];

        for ($c = 0; $c < 9; $c++) {
            $necesarios = $patrones[0][$c] + $patrones[1][$c] + $patrones[2][$c];
            if ($necesarios > 0) {
                // Seleccionar $necesarios números de este rango columna
                $pool = $rangos[$c];
                shuffle($pool);
                $seleccionados = array_slice($pool, 0, $necesarios);
                
                // => AQUI SUCEDE LA MAGIA DE LA REGLA DE ORO <=
                sort($seleccionados); 

                // Injectarlos de arriba abajo
                $idxDato = 0;
                for ($f = 0; $f < 3; $f++) {
                    if ($patrones[$f][$c] === 1) {
                        $grilla[$f][$c] = $seleccionados[$idxDato];
                        $idxDato++;
                    }
                }
            }
        }

        return $grilla;
    }
}
