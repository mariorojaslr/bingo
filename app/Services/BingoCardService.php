<?php

namespace App\Services;

class BingoCardService
{
    /**
     * Genera una grilla matemática estricta para el Bingo de 90 bolillas (Estilo Argentino)
     * Garantiza:
     * - 5 números por fila
     * - Al menos 1 número por columna (seguridad matemática)
     * - ORDENAMIENTO VERTICAL PERFECTO: Los números de cada columna están ordenados de menor a mayor de arriba hacia abajo.
     *
     * @return array Matriz 3x9 de enteros (0 significa hueco)
     */
    public function generarGrilla()
    {
        // 1. GENERAR MAPA DE HUECOS (MÁSCARA 3x9)
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
                // Seleccionar $necesarios números de este rango de columna
                $pool = $rangos[$c];
                shuffle($pool);
                $seleccionados = array_slice($pool, 0, $necesarios);
                
                // => AQUI SUCEDE LA MAGIA: ORDENAMIENTO VERTICAL ESTRICTO <=
                sort($seleccionados); 

                // Inyectarlos de arriba abajo
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
