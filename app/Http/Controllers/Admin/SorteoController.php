<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sorteo;
use App\Models\Jugada;
use App\Events\BolillaSorteada;

class SorteoController extends Controller
{
    public function ver($jugadaId)
    {
        $jugada = Jugada::findOrFail($jugadaId);

        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->where('estado', 'en_curso')
            ->latest()
            ->first();

        return view('sorteador.jugada', compact('jugada', 'sorteo', 'jugadaId'));
    }

    public function extraer(Request $request, $jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->where('estado', 'en_curso')
            ->latest()
            ->firstOrFail();

        $sacadas = $sorteo->bolillas_sacadas ?? [];

        do {
            $nueva = rand(1, 90);
        } while (in_array($nueva, $sacadas));

        $sacadas[] = $nueva;

        $sorteo->bolilla_actual = $nueva;
        $sorteo->bolillas_sacadas = $sacadas;
        $sorteo->save();

        $ultimas = array_slice(array_reverse($sacadas), 0, 5);

        event(new BolillaSorteada(
            (int) $jugadaId,
            (int) $nueva,
            $ultimas
        ));

        return back();
    }

    public function continuar($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)->latest()->first();
        $sorteo->estado = 'en_curso';
        $sorteo->save();

        return back();
    }
}
