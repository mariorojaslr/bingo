<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sorteo;
use App\Models\Jugada;
use Illuminate\Http\Request;

class SorteadorController extends Controller
{
    public function iniciar($jugadaId)
    {
        $sorteo = Sorteo::firstOrCreate(
            ['jugada_id' => $jugadaId],
            ['estado' => 'pendiente']
        );

        $sorteo->iniciar();

        return response()->json([
            'ok' => true,
            'mensaje' => 'Sorteo iniciado',
            'sorteo' => $sorteo
        ]);
    }

    public function pausar($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)->firstOrFail();
        $sorteo->pausar();

        return response()->json(['ok' => true, 'estado' => 'pausado']);
    }

    public function reanudar($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)->firstOrFail();
        $sorteo->reanudar();

        return response()->json(['ok' => true, 'estado' => 'en_curso']);
    }

    public function sacarBolilla($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)->firstOrFail();

        $yaSalieron = $sorteo->bolillas_sacadas ?? [];
        $todas = range(1, 90);

        $disponibles = array_values(array_diff($todas, $yaSalieron));

        if (count($disponibles) === 0) {
            return response()->json(['ok' => false, 'mensaje' => 'No quedan bolillas']);
        }

        $numero = $disponibles[array_rand($disponibles)];

        $sorteo->agregarBolilla($numero);

        return response()->json([
            'ok' => true,
            'bolilla' => $numero,
            'anteriores' => array_slice(array_reverse($sorteo->bolillas_sacadas), 1, 6)
        ]);
    }

    public function estado($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)->first();

        return response()->json([
            'estado' => $sorteo?->estado ?? 'pendiente',
            'actual' => $sorteo?->bolilla_actual,
            'bolillas' => $sorteo?->bolillas_sacadas ?? []
        ]);
    }
}
