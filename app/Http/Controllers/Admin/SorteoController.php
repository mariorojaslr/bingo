<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sorteo;
use App\Models\Jugada;
use App\Events\SorteoActualizado;

class SorteoController extends Controller
{
    /**
     * 🎯 Pantalla principal del sorteador
     */
    public function ver($jugadaId)
    {
        $jugada = Jugada::findOrFail($jugadaId);

        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->latest()
            ->firstOrFail();

        return view('sorteador.jugada', compact('jugada', 'sorteo', 'jugadaId'));
    }

    /**
     * 🎲 Extraer bolilla
     */
    public function extraer(Request $request, $jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->where('estado', 'en_curso')
            ->latest()
            ->firstOrFail();

        // 🛑 Corte si ya salieron 90
        if (count($sorteo->getBolillas()) >= 90) {
            $sorteo->finalizar();

            broadcast(new SorteoActualizado($sorteo))->toOthers();

            return back()->with('error', 'Bolillero completo. Juego finalizado.');
        }

        // Buscar bolilla no repetida
        do {
            $nueva = rand(1, 90);
        } while (!$sorteo->agregarBolilla($nueva));

        // 📡 Emitir evento ÚNICO
        broadcast(new SorteoActualizado($sorteo))->toOthers();

        return back();
    }

    /**
     * 🟦 Confirmar Línea
     */
    public function confirmarLinea(Request $request, $jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->latest()
            ->firstOrFail();

        $sorteo->estado = 'linea';
        $sorteo->save();

        broadcast(new SorteoActualizado($sorteo))->toOthers();

        return back();
    }

    /**
     * ▶ Reanudar sorteo
     */
    public function reanudar($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->latest()
            ->firstOrFail();

        $sorteo->estado = 'en_curso';
        $sorteo->save();

        broadcast(new SorteoActualizado($sorteo))->toOthers();

        return back();
    }

    /**
     * 🟥 Confirmar Bingo
     */
    public function confirmarBingo(Request $request, $jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->latest()
            ->firstOrFail();

        $sorteo->estado = 'bingo';
        $sorteo->save();

        broadcast(new SorteoActualizado($sorteo))->toOthers();

        return back();
    }

    /**
     * ⏹ Finalizar juego
     */
    public function finalizar($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->latest()
            ->firstOrFail();

        $sorteo->finalizar();

        broadcast(new SorteoActualizado($sorteo))->toOthers();

        return back();
    }

    /**
     * 🔄 Reiniciar jugada (modo pruebas)
     */
    public function reiniciar($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->latest()
            ->firstOrFail();

        $sorteo->iniciar();

        broadcast(new SorteoActualizado($sorteo))->toOthers();

        return back()->with('success', 'Jugada reiniciada correctamente.');
    }
}
