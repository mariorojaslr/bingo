<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PruebaParticipante;
use App\Models\ParticipanteCartonPrueba;
use App\Models\Jugada;
use App\Models\Sorteo;

class PilotoController extends Controller
{
    /**
     * Vista del piloto / jugador
     */
    public function ver(string $token)
    {
        // 1. Participante por token
        $participante = PruebaParticipante::where('token', $token)->firstOrFail();

        // 2. Jugada activa
        $jugada = Jugada::where('estado', 'en_produccion')
            ->latest()
            ->firstOrFail();

        // 3. Sorteo asociado a la jugada
        $sorteo = Sorteo::where('jugada_id', $jugada->id)
            ->latest()
            ->first();

        // Valores por defecto (estado seguro)
        $bolillaActual    = null;
        $bolillasMarcadas = [];
        $ultimasBolillas  = collect();

        if ($sorteo) {
            $bolillaActual    = $sorteo->bolilla_actual;
            $bolillasMarcadas = $sorteo->getBolillas();
            $ultimasBolillas  = collect($bolillasMarcadas)
                ->reverse()
                ->take(5)
                ->values();
        }

        // 4. Cartones asignados al participante
        $cartones = ParticipanteCartonPrueba::where('participante_prueba_id', $participante->id)
            ->where('jugada_id', $jugada->id)
            ->with('carton')
            ->get();

        // 5. Render de la vista
        return view('piloto.ver', [
            'participante'     => $participante,
            'jugada'           => $jugada,
            'jugadaId'         => $jugada->id,
            'cartones'         => $cartones,
            'bolillaActual'    => $bolillaActual,
            'ultimasBolillas'  => $ultimasBolillas,
            'bolillasMarcadas' => $bolillasMarcadas,
        ]);
    }
}
