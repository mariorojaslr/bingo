<?php

namespace App\Http\Controllers;

use App\Models\PruebaParticipante;
use App\Models\ParticipanteCartonPrueba;
use App\Models\Sorteo;
use App\Models\Jugada;

class PilotoController extends Controller
{
    public function ver($token)
    {
        // 1. Buscar participante
        $participante = PruebaParticipante::where('token', $token)->firstOrFail();

        // 2. Buscar jugada activa
        $jugada = Jugada::where('estado', 'en_produccion')
            ->orderBy('created_at', 'desc')
            ->first();

        $ultimaBolilla = null;
        $bolillas = collect();
        $cartones = collect();

        if ($jugada) {

            // 3. Traer sorteo
            $sorteo = Sorteo::where('jugada_id', $jugada->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($sorteo) {
                $ultimaBolilla = $sorteo->bolilla_actual;

                if ($sorteo->bolillas_sacadas) {
                    $todas = json_decode($sorteo->bolillas_sacadas, true);
                    $bolillas = collect(array_reverse($todas))->take(5);
                }
            }

            // 4. Traer cartones asignados al participante para esta jugada
            $cartones = ParticipanteCartonPrueba::where('participante_prueba_id', $participante->id)
                ->where('jugada_id', $jugada->id)
                ->with('carton')
                ->get();
        }

        return view('piloto.bienvenido', compact(
            'participante',
            'bolillas',
            'ultimaBolilla',
            'cartones'
        ));
    }
}
