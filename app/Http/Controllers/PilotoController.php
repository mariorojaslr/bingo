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

        // 2. Buscar jugada activa
        $jugada = Jugada::where('estado', 'en_produccion')
            ->latest()
            ->firstOrFail();

        // 3. Buscar sorteo activo
        $sorteo = Sorteo::where('jugada_id', $jugada->id)
            ->latest()
            ->first();

        // Valores por defecto
        $bolillaActual = null;
        $ultimasBolillas = [];
        $bolillasMarcadas = [];

        if ($sorteo) {
            $bolillaActual = $sorteo->bolilla_actual;

            /**
             * 🔑 NORMALIZACIÓN CLAVE
             * bolillas_sacadas puede venir como array o como string JSON
             */
            if (is_array($sorteo->bolillas_sacadas)) {
                $todas = $sorteo->bolillas_sacadas;
            } elseif (is_string($sorteo->bolillas_sacadas)) {
                $todas = json_decode($sorteo->bolillas_sacadas, true) ?? [];
            } else {
                $todas = [];
            }

            $bolillasMarcadas = $todas;
            $ultimasBolillas = collect(array_reverse($todas))->take(5)->values();
        }

        // 4. Cartones asignados al participante
        $cartones = ParticipanteCartonPrueba::where('participante_prueba_id', $participante->id)
            ->where('jugada_id', $jugada->id)
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
