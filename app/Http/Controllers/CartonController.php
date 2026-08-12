<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carton;
use App\Services\PdfService;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Services\BingoCardService;

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

            $bingoService = new BingoCardService();

            while ($generados < $cantidad) {
                
                // 1. Crear matriz matemática
                $grilla = $bingoService->generarGrilla();
                
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

    }
}
