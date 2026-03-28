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
    public function ver($token)
    {
        // 1. Buscar participante por token
        $participante = PruebaParticipante::where('token', $token)->firstOrFail();

        // 2. Buscar jugada activa (O la jugada donde el participante tenga cartones)
        $relacion = ParticipanteCartonPrueba::where('participante_prueba_id', $participante->id)->latest()->first();
        
        $jugadaId = $relacion ? $relacion->jugada_id : null;
        if (!$jugadaId) {
            $jugada = Jugada::where('estado', 'en_produccion')->latest()->firstOrFail();
            $jugadaId = $jugada->id;
        } else {
            $jugada = Jugada::findOrFail($jugadaId);
        }

        // 3. Buscar sorteo activo
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->latest()
            ->first();

        // Valores por defecto
        $bolillaActual    = null;
        $ultimasBolillas  = collect();
        $bolillasMarcadas = [];

        if ($sorteo) {
            $bolillaActual = $sorteo->bolilla_actual;
            $todas = $sorteo->getBolillas();

            $bolillasMarcadas = $todas;
            $ultimasBolillas  = collect(array_reverse($todas))->take(8)->values();
        }

        // 4. Cartones asignados al participante
        $cartones = ParticipanteCartonPrueba::where('participante_prueba_id', $participante->id)
            ->where('jugada_id', $jugadaId)
            ->with('carton')
            ->get();

        // 5. Render
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
