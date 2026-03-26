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
     * - Saca bolilla
     * - Evalúa automáticamente línea / bingo
     * - Emite UN SOLO evento
     */
    public function extraer(Request $request, $jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->where('estado', 'en_curso')
            ->latest()
            ->firstOrFail();

        try {
            // 🛑 Corte si ya salieron 90 bolillas
            if (count($sorteo->getBolillas()) >= 90) {
                return response()->json(['success' => false, 'error' => 'Ya salieron 90 bolillas'], 400);
            }

            $numeroManual = $request->input('numero');

            if ($numeroManual) {
                $numeroManual = (int)$numeroManual;
                if ($numeroManual < 1 || $numeroManual > 90) {
                    return response()->json(['success' => false, 'error' => 'El número debe estar entre 1 y 90.'], 400);
                }
                if (!$sorteo->agregarBolilla($numeroManual)) {
                    return response()->json(['success' => false, 'error' => "El número $numeroManual ya salió previamente."], 400);
                }
            } else {
                /**
                 * 🎲 Bucle Mágico: extrae aleatorio hasta encontrar una bolilla que no haya salido
                 */
                do {
                    $nueva = rand(1, 90);
                } while (!$sorteo->agregarBolilla($nueva));
            }

            // $sorteo->evaluarGanadores(); // TODO: Implementar logica de ganadores despues

            // 📡 Evento ÚNICO (todas las pantallas escuchan)
            event(new SorteoActualizado($sorteo));

            // 🚀 Tecnología rápida (sin redirect)
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    /**
     * 🟦 Confirmar Línea (manual)
     * Útil como respaldo humano
     */
    public function confirmarLinea(Request $request, $jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->latest()
            ->firstOrFail();

        $sorteo->estado = 'linea';
        $sorteo->save();

        event(new SorteoActualizado($sorteo));

        return response()->noContent();
    }

    /**
     * ▶ Reanudar sorteo
     * Oculta overlays y vuelve a jugar
     */
    public function reanudar($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->latest()
            ->firstOrFail();

        $sorteo->estado = 'en_curso';
        $sorteo->save();

        event(new SorteoActualizado($sorteo));

        return response()->noContent();
    }

    /**
     * 🟥 Confirmar Bingo (manual)
     */
    public function confirmarBingo(Request $request, $jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->latest()
            ->firstOrFail();

        $sorteo->estado = 'bingo';
        $sorteo->save();

        event(new SorteoActualizado($sorteo));

        return response()->noContent();
    }

    /**
     * ⏹ Finalizar juego completamente
     */
    public function finalizar($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->latest()
            ->firstOrFail();

        $sorteo->finalizar();

        event(new SorteoActualizado($sorteo));

        return response()->noContent();
    }

    /**
     * 🔄 Reiniciar jugada (modo pruebas)
     * - Limpia bolillas
     * - Vuelve a estado inicial
     * - Todas las pantallas se resetean
     */
    public function reiniciar($jugadaId)
    {
        $sorteo = Sorteo::where('jugada_id', $jugadaId)
            ->latest()
            ->firstOrFail();

        $sorteo->iniciar();

        event(new SorteoActualizado($sorteo));

        return response()->noContent();
    }
}
