<?php

namespace App\Services;

use App\Models\JugadaCarton;

class DetectorBingoService
{
    public static function detectar($jugadaId, array $bolillas)
    {
        $resultado = [
            'linea' => null,
            'bingo' => null,
        ];

        // Normalizamos bolillas a enteros
        $bolillas = collect($bolillas)->map(fn($n) => (int)$n);

        $cartones = JugadaCarton::with('carton')
            ->where('jugada_id', $jugadaId)
            ->get();

        foreach ($cartones as $jugadaCarton) {

            $carton = $jugadaCarton->carton;

            if (!$carton || !$carton->grilla) continue;

            $matriz = is_array($carton->grilla)
                ? $carton->grilla
                : json_decode($carton->grilla, true);

            if (!is_array($matriz)) continue;

            // Todos los números reales del cartón (enteros, sin ceros)
            $numerosCarton = collect($matriz)
                ->flatten()
                ->filter(fn($n) => (int)$n > 0)
                ->map(fn($n) => (int)$n)
                ->values();

            // 🎯 BINGO (todos los números del cartón ya salieron)
            if ($numerosCarton->isNotEmpty() && $numerosCarton->diff($bolillas)->isEmpty()) {
                $resultado['bingo'] = [
                    'carton_id' => $carton->id,
                    'numeros'   => $numerosCarton
                ];
            }

            // 📏 LÍNEA (cualquiera de las filas completas)
            foreach ($matriz as $filaIndex => $fila) {

                if (!is_array($fila)) continue;

                $numerosFila = collect($fila)
                    ->filter(fn($n) => (int)$n > 0)
                    ->map(fn($n) => (int)$n)
                    ->values();

                if ($numerosFila->isNotEmpty() && $numerosFila->diff($bolillas)->isEmpty()) {
                    $resultado['linea'] = [
                        'carton_id' => $carton->id,
                        'fila'      => $filaIndex + 1,
                        'numeros'   => $numerosFila
                    ];
                }
            }

            if ($resultado['bingo']) break;
        }

        return $resultado;
    }
}
