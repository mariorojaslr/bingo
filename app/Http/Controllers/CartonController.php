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

        // Caché en RAM para hashes y evitar matemáticas idénticas
        $hashesExistentes = Carton::where('serie', $serie)->pluck('hash')->toArray();
        $hashesSet = array_flip($hashesExistentes);

        // Caché en RAM para números de suerte y garantizar 100% de unicidad
        $suertesExistentes = Carton::where('serie', $serie)->pluck('numero_suerte')->toArray();
        $suertesSet = array_flip($suertesExistentes);

        DB::beginTransaction();
        try {
            $cartonesToInsert = [];
            
            // Solución al bug de VARCHAR: Si la columna es texto en DB, max() devuelve "999" en vez de "1000".
            // Ordenar por ID nos da el último real de forma segura.
            $lastCarton = Carton::where('serie', $serie)->orderBy('id', 'desc')->first();
            $lastNumero = $lastCarton ? (int) $lastCarton->numero_carton : 0;

            $bingoService = new BingoCardService();

            while ($generados < $cantidad) {
                
                // 1. Crear matriz matemática
                $grilla = $bingoService->generarGrilla();
                $hash = md5(json_encode($grilla));

                // 2. Antibombas matemático
                if (isset($hashesSet[$hash])) {
                    $colisiones_evitadas++;
                    continue; 
                }

                // 3. Generar Número de Suerte único (7 dígitos)
                do {
                    $suerte = (string) mt_rand(1000000, 9999999);
                } while (isset($suertesSet[$suerte]));
                
                $suertesSet[$suerte] = true;
                $hashesSet[$hash] = true;
                $lastNumero++;
                $generados++;

                $cartonesToInsert[] = [
                    'serie' => $serie,
                    'numero_carton' => $lastNumero,
                    'numero_suerte' => $suerte,
                    'formato' => 'ARG',
                    'estado' => 'disponible',
                    'grilla' => json_encode($grilla),
                    'hash' => $hash,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                // Chunking de 500 para RAM
                if (count($cartonesToInsert) === 500) {
                    Carton::insert($cartonesToInsert);
                    $cartonesToInsert = [];
                }
            }

            if (!empty($cartonesToInsert)) {
                Carton::insert($cartonesToInsert);
            }

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'generados' => $generados,
                    'colisiones' => $colisiones_evitadas
                ]);
            }

            return redirect()->back()->with('success', "¡Éxito nivel Dios! Se generaron $generados cartones. Colisiones evitadas: $colisiones_evitadas.");

        } catch (Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error generando cartones: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
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

        $serieFiltro = Carton::orderBy('id', 'desc')->value('serie') ?? 'LR-2026-08';
        $totalCartones = Carton::where('serie', $serieFiltro)->count();

        if ($request->filled('numero')) {
            $numero = $request->numero;
            $targetCarton = Carton::where('serie', $serieFiltro)
                ->where('numero_carton', $numero)
                ->first();
                
            if ($targetCarton) {
                $posicion = Carton::where('serie', $serieFiltro)
                    ->where('id', '<=', $targetCarton->id)
                    ->count();
                $pagina = (int) ceil($posicion / $porPagina);
            } else {
                $pagina = 1;
            }
        } else {
            $pagina = $request->get('page', 1);
        }

        $cartones = Carton::orderBy('id')
            ->paginate($porPagina, ['*'], 'page', $pagina);



        return view('admin.cartones.listado', compact(
            'cartones',
            'columnas',
            'filas',
            'porPagina',
            'serieFiltro',
            'totalCartones'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | VISOR DEMO (MARKETING)
    |--------------------------------------------------------------------------
    */
    public function demoVisor(Request $request)
    {
        // Simple password protection
        if ($request->get('pwd') !== 'infinity2026') {
            return view('admin.cartones.demo_login');
        }

        $columnas = (int) $request->get('columnas', 3);
        $filas = (int) $request->get('filas', 2);
        
        $columnas = max(1, min(4, $columnas));
        $filas = max(1, min(4, $filas));

        $porPagina = $columnas * $filas;

        $serieFiltro = Carton::orderBy('id', 'desc')->value('serie') ?? 'LR-2026-08';
        $totalCartones = Carton::where('serie', $serieFiltro)->count();

        if ($request->filled('numero')) {
            $numero = $request->numero;
            $targetCarton = Carton::where('serie', $serieFiltro)
                ->where('numero_carton', $numero)
                ->first();
                
            if ($targetCarton) {
                $posicion = Carton::where('serie', $serieFiltro)
                    ->where('id', '<=', $targetCarton->id)
                    ->count();
                $pagina = (int) ceil($posicion / $porPagina);
            } else {
                $pagina = 1;
            }
        } else {
            $pagina = $request->get('page', 1);
        }

        $cartones = Carton::where('serie', 'LR-2026-08')
            ->orderBy('id')
            ->paginate($porPagina, ['*'], 'page', $pagina);
            
        // Maintain password and filters in pagination links
        $cartones->appends([
            'pwd' => 'infinity2026',
            'columnas' => $columnas,
            'filas' => $filas,
            'numero' => $request->numero
        ]);

        return view('admin.cartones.demo', compact(
            'cartones',
            'columnas',
            'filas',
            'serieFiltro',
            'totalCartones'
        ));
    }
}
