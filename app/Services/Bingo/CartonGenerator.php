<?php

namespace App\Services\Bingo;

/**
 * Generador de cartones de Bingo Argentino (3x9).
 *
 * Reglas:
 * - 3 filas x 9 columnas
 * - 15 números (5 por fila)
 * - Cada columna tiene al menos 1 número
 * - Cada columna tiene como máximo 3 números
 * - Rangos por columna:
 *   1-9, 10-19, 20-29, ..., 80-90
 * - Los blancos se representan con 0
 */
class CartonGenerator
{
    /**
     * Genera un cartón argentino válido.
     */
    public function generarCartonArgentino(): array
    {
        // Inicializamos matriz 3x9 en ceros
        $carton = array_fill(0, 3, array_fill(0, 9, 0));

        // Paso 1: Decidir en qué columnas hay número por fila (5 por fila)
        $filasColumnas = [];
        for ($f = 0; $f < 3; $f++) {
            $cols = range(0, 8);
            shuffle($cols);
            $filasColumnas[$f] = array_slice($cols, 0, 5);
        }

        // Paso 2: Contar cuántos números tendrá cada columna
        $conteoColumnas = array_fill(0, 9, 0);
        foreach ($filasColumnas as $cols) {
            foreach ($cols as $c) {
                $conteoColumnas[$c]++;
            }
        }

        // Aseguramos que ninguna columna quede vacía
        for ($c = 0; $c < 9; $c++) {
            if ($conteoColumnas[$c] === 0) {
                // Tomamos una fila al azar y reemplazamos una columna
                $fila = rand(0, 2);
                $reemplazar = array_pop($filasColumnas[$fila]);
                $filasColumnas[$fila][] = $c;
                $conteoColumnas[$reemplazar]--;
                $conteoColumnas[$c]++;
            }
        }

        // Paso 3: Generar números por columna según su rango
        $rangos = [
            [1, 9], [10, 19], [20, 29], [30, 39], [40, 49],
            [50, 59], [60, 69], [70, 79], [80, 90],
        ];

        $numerosPorColumna = [];

        for ($c = 0; $c < 9; $c++) {
            [$min, $max] = $rangos[$c];
            $pool = range($min, $max);
            shuffle($pool);
            $numerosPorColumna[$c] = array_slice($pool, 0, $conteoColumnas[$c]);
            sort($numerosPorColumna[$c]);
        }

        // Paso 4: Colocar los números en la matriz
        for ($c = 0; $c < 9; $c++) {
            $filaIndex = 0;
            for ($f = 0; $f < 3; $f++) {
                if (in_array($c, $filasColumnas[$f])) {
                    $carton[$f][$c] = $numerosPorColumna[$c][$filaIndex];
                    $filaIndex++;
                }
            }
        }

        return $carton;
    }

    /**
     * Genera un lote de cartones argentinos.
     */
    public function generarLoteArgentino(int $cantidad): array
    {
        $cartones = [];

        for ($i = 0; $i < $cantidad; $i++) {
            $cartones[] = $this->generarCartonArgentino();
        }

        return $cartones;
    }
}
