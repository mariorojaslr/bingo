<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jugada;
use App\Models\Sorteo;
use App\Services\DetectorBingoService;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function ver($jugadaId)
    {
        $jugada = Jugada::with(['institucion','organizador'])->findOrFail($jugadaId);
        $sorteo = Sorteo::where('jugada_id', $jugadaId)->first();

        return view('monitor.jugada', [
            'jugada' => $jugada,
            'sorteo' => $sorteo,
            'bolillas' => $sorteo?->bolillas_sacadas ?? [],
            'numeroActual' => $sorteo?->bolilla_actual,
        ]);
    }

    public function estado($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)->first();

        $resultado = DetectorBingoService::detectar(
            $jugadaId,
            $sorteo?->bolillas_sacadas ?? []
        );

        return response()->json([
            'bolillas' => $sorteo?->bolillas_sacadas ?? [],
            'ultima'   => $sorteo?->bolilla_actual,
            'linea'    => $resultado['linea'],
            'bingo'    => $resultado['bingo'],
            'estado'   => $sorteo?->estado,
        ]);
    }
}
