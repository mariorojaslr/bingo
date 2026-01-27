<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jugada;
use App\Models\Sorteo;
use Illuminate\Http\Request;

class SorteoController extends Controller
{
    public function ver($jugadaId)
    {
        $jugada = Jugada::with(['institucion','organizador'])->findOrFail($jugadaId);

        // Crear o recuperar sorteo
        $sorteo = Sorteo::firstOrCreate(
            ['jugada_id' => $jugada->id],
            ['estado' => 'en_curso', 'bolillas_sacadas' => []]
        );

        $bolillas = $sorteo->bolillas_sacadas ?? [];
        $ultima = count($bolillas) ? end($bolillas) : null;

        return view('sorteador.jugada', compact('jugada','sorteo','bolillas','ultima'));
    }

    public function extraer($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)->firstOrFail();

        $bolillas = $sorteo->bolillas_sacadas ?? [];

        $disponibles = array_diff(range(1,90), $bolillas);

        if (count($disponibles) === 0) {
            return redirect()->back();
        }

        $nueva = collect($disponibles)->random();

        // Usamos la lógica del modelo
        $sorteo->agregarBolilla($nueva);

        return redirect()->route('sorteador.jugada', $jugadaId);
    }
}
