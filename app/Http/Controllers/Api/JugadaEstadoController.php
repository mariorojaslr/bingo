<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jugada;
use App\Models\Sorteo;
use App\Http\Controllers\Api\JugadaEstadoController;

Route::get('/jugada/{jugada}/estado', [JugadaEstadoController::class, 'show']);


class JugadaEstadoController extends Controller
{
    public function show($jugadaId)
    {
        $jugada = Jugada::findOrFail($jugadaId);

        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->latest()
            ->first();

        if (!$sorteo) {
            return response()->json([
                'estado' => 'sin_sorteo',
                'bolillas' => [],
                'ultima' => null,
            ]);
        }

        $bolillas = $sorteo->getBolillas();

        return response()->json([
            'estado'   => $sorteo->estado,
            'bolillas' => $bolillas,
            'ultima'   => $sorteo->bolilla_actual,
        ]);
    }
}
