<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PruebaParticipante;

class BlackjackController extends Controller
{
    public function index(Request $request)
    {
        $participanteLogueado = null;
        $token = $request->cookie('participante_token');
        if ($token) {
            $participanteLogueado = PruebaParticipante::where('token', $token)->first();
        }

        return view('casino.blackjack', compact('participanteLogueado'));
    }
}
