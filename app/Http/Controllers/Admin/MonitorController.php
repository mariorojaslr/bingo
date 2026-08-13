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
        $sorteo = Sorteo::where('jugada_id', $jugadaId)->latest()->first();

        if (!$sorteo) {
            return response()->json([
                'bolilla' => null,
                'bolillas' => [],
                'ultimas' => [],
                'estado' => 'esperando',
                'ganadores' => ['lineas' => [], 'bingos' => []]
            ]);
        }

        $bolillas = $sorteo->getBolillas();
        $ultimas = array_slice(array_reverse($bolillas), 0, 9);
        $ganadores = $sorteo->evaluarGanadores();

        return response()->json([
            'bolilla'   => $sorteo->bolilla_actual,
            'bolillas'  => $bolillas,
            'ultimas'   => $ultimas,
            'estado'    => $sorteo->estado,
            'ganadores' => $ganadores,
        ]);
    }
}
