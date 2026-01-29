<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sorteo;
use App\Events\BolillaSorteada;

class SorteadorController extends Controller
{
    public function ver($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->where('estado', 'en_curso')
            ->latest()
            ->first();

        return view('sorteador.jugada', compact('sorteo', 'jugadaId'));
    }

    public function extraer(Request $request, $jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->where('estado', 'en_curso')
            ->latest()
            ->firstOrFail();

        $sacadas = is_array($sorteo->bolillas_sacadas)
            ? $sorteo->bolillas_sacadas
            : (json_decode($sorteo->bolillas_sacadas, true) ?? []);

        do {
            $nueva = rand(1, 90);
        } while (in_array($nueva, $sacadas));

        $sacadas[] = $nueva;

        $sorteo->bolillas_sacadas = $sacadas;
        $sorteo->bolilla_actual = $nueva;
        $sorteo->save();

        $ultimas = array_slice(array_reverse($sacadas), 0, 5);

        // EMITIR EN TIEMPO REAL
        event(new BolillaSorteada($jugadaId, $nueva, $ultimas));

        return back();
    }
}
