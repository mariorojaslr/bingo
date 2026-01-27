<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jugada;
use App\Models\Sorteo;
use App\Services\DetectorBingoService;
use Illuminate\Http\Request;

class SorteoController extends Controller
{
    public function ver($jugadaId)
    {
        $jugada = Jugada::with(['institucion','organizador'])->findOrFail($jugadaId);

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

        if ($sorteo->estado !== 'en_curso') {
            return redirect()->route('sorteador.jugada', $jugadaId);
        }

        $bolillas = $sorteo->bolillas_sacadas ?? [];
        $disponibles = array_diff(range(1, 90), $bolillas);

        if (count($disponibles) === 0) return redirect()->back();

        $nueva = collect($disponibles)->random();
        $sorteo->agregarBolilla($nueva);
        $bolillas = $sorteo->bolillas_sacadas;

        $resultado = DetectorBingoService::detectar($jugadaId, $bolillas);

        if ($resultado['linea'] && !$sorteo->bolilla_linea) {
            $sorteo->registrarLinea($nueva);
            $sorteo->carton_linea_id = $resultado['linea']['carton_id'];
            $sorteo->estado = 'pausa_linea';
            $sorteo->save();
        }

        if ($resultado['bingo'] && !$sorteo->bolilla_bingo) {
            $sorteo->registrarBingo($nueva);
            $sorteo->carton_bingo_id = $resultado['bingo']['carton_id'];
            $sorteo->estado = 'pausa_bingo';
            $sorteo->save();
        }

        return redirect()->route('sorteador.jugada', $jugadaId);
    }

    public function continuar($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)->firstOrFail();
        $sorteo->estado = 'en_curso';
        $sorteo->save();

        return redirect()->route('sorteador.jugada', $jugadaId);
    }
}
