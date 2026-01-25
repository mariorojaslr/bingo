<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carton;
use App\Services\PdfService;

class CartonController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GENERACIÓN DE CARTONES
    |--------------------------------------------------------------------------
    */

    public function generarCartones(Request $request)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1|max:500000',
            'serie' => 'required|string'
        ]);

        $cantidad = (int) $request->cantidad;
        $serie = $request->serie;

        for ($i = 1; $i <= $cantidad; $i++) {

            // Generar número mágico irrepetible de 6 dígitos
            do {
                $numeroMagico = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            } while (Carton::where('numero_carton', $numeroMagico)->exists());

            Carton::create([
                'serie' => $serie,
                'numero_carton' => $numeroMagico,
                'formato' => 'ARG',
                'estado' => 'activo',
                'grilla' => $this->generarGrillaArgentina()
            ]);
        }

        return redirect()->back()->with('success', "Se generaron $cantidad cartones correctamente con números mágicos únicos.");
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
    | GENERADOR DE GRILLA ARGENTINA CORRECTA (3x9 oficial)
    |--------------------------------------------------------------------------
    */

    private function generarGrillaArgentina()
{
    $rangos = [
        range(1, 9), range(10, 19), range(20, 29),
        range(30, 39), range(40, 49), range(50, 59),
        range(60, 69), range(70, 79), range(80, 90)
    ];

    // Inicializar grilla vacía 3x9
    $grilla = array_fill(0, 3, array_fill(0, 9, 0));

    // Para cada columna, elegir cuántos números tendrá (1 o 2, y algunas 3)
    $cantidades = array_fill(0, 9, 1); // base: 1 por columna (9)
    $extra = 6; // faltan 6 para llegar a 15 números totales

    while ($extra > 0) {
        $col = rand(0, 8);
        if ($cantidades[$col] < 3) {
            $cantidades[$col]++;
            $extra--;
        }
    }

    // Para cada columna, seleccionar números únicos y ordenarlos
    $columnas = [];
    for ($col = 0; $col < 9; $col++) {
        shuffle($rangos[$col]);
        $numeros = array_slice($rangos[$col], 0, $cantidades[$col]);
        sort($numeros);
        $columnas[$col] = $numeros;
    }

    // Distribuir en filas garantizando 5 por fila
    $ocupacionFilas = [0, 0, 0];

    for ($col = 0; $col < 9; $col++) {
        foreach ($columnas[$col] as $num) {
            // Buscar una fila con menos de 5 números
            do {
                $fila = rand(0, 2);
            } while ($ocupacionFilas[$fila] >= 5);

            $grilla[$fila][$col] = $num;
            $ocupacionFilas[$fila]++;
        }
    }

    return $grilla;
}


}
