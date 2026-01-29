<?php

namespace App\Http\Controllers;

use App\Models\PruebaParticipante;
use App\Models\Sorteo;

class PilotoController extends Controller
{
    public function ver($token)
    {
        // Buscar participante por token
        $participante = PruebaParticipante::where('token', $token)->firstOrFail();

        // Buscar sorteo en curso
        $sorteo = Sorteo::where('estado', 'en_curso')->latest()->first();

        $bolillaActual = null;
        $ultimasBolillas = [];
        $bolillasMarcadas = [];
        $jugadaId = null;

        if ($sorteo) {
            $jugadaId = $sorteo->id;
            $bolillaActual = $sorteo->bolilla_actual;

            if (!empty($sorteo->bolillas_sacadas)) {
                if (is_array($sorteo->bolillas_sacadas)) {
                    $bolillasMarcadas = $sorteo->bolillas_sacadas;
                } else {
                    $bolillasMarcadas = json_decode($sorteo->bolillas_sacadas, true) ?? [];
                }

                $ultimasBolillas = array_slice(array_reverse($bolillasMarcadas), 0, 5);
            }
        }

        // Cartones del participante
        $cartones = $participante->cartones()->with('carton')->get();

        // Enviar todo a la vista correcta
        return view('piloto.ver', compact(
            'participante',
            'bolillaActual',
            'ultimasBolillas',
            'bolillasMarcadas',
            'cartones',
            'jugadaId'
        ));
    }
}
