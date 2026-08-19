<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jugada;
use App\Models\Carton;
use App\Models\ParticipanteCartonPrueba;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VirtualBingoController extends Controller
{
    public function index(Request $request)
    {
        // En un entorno multiempresa real, la empresa vendría del subdominio o sesión.
        // Simulamos obtener la empresa actual (ej: empresa_id = 1)
        $empresaId = session('empresa_id', 1);

        $ahora = Carbon::now();

        // Mostrar salas generadas que todavía no han pasado o están "creadas" / "abierta_venta"
        $salas = Jugada::whereNotNull('plantilla_sala_virtual_id')
            ->where('empresa_id', $empresaId)
            ->where('estado', 'creada')
            ->where(DB::raw("CONCAT(fecha_evento, ' ', hora_evento)"), '>=', $ahora->subMinutes(10)) // Mostrar incluso si pasaron unos minutos y no empezó
            ->orderBy('fecha_evento', 'asc')
            ->orderBy('hora_evento', 'asc')
            ->get();

        return view('casino.bingo_virtual.index', compact('salas'));
    }

    public function show($id)
    {
        $sala = Jugada::findOrFail($id);
        
        // Obtener el participante logueado en la sesión del casino
        // En esta demo asumimos ID de participante en sesión, o usamos el primero por defecto
        $participanteId = session('participante_id', 1);

        // Obtener cartones ya comprados por este jugador para ESTA jugada
        $cartonesJugador = ParticipanteCartonPrueba::where('jugada_id', $sala->id)
            ->where('participante_prueba_id', $participanteId)
            ->with('carton')
            ->get();

        return view('casino.bingo_virtual.show', compact('sala', 'cartonesJugador'));
    }

    public function comprar(Request $request, $id)
    {
        $sala = Jugada::findOrFail($id);
        $cantidad = (int) $request->input('cantidad', 1);
        $participanteId = session('participante_id', 1); // Mock participante

        // Validar si tiene saldo suficiente
        $participante = \App\Models\PruebaParticipante::findOrFail($participanteId);
        $costoTotal = $sala->precio_hoja * $cantidad;

        if ($participante->saldo_fichas < $costoTotal) {
            return back()->with('error', 'Saldo insuficiente.');
        }

        DB::beginTransaction();

        try {
            // Descontar saldo
            $participante->decrement('saldo_fichas', $costoTotal);

            // Seleccionar cartones:
            // Regla de oro: No puede haber recibido este cartón nunca (pp_carton_unique)
            $cartonesAsignadosHistorial = ParticipanteCartonPrueba::where('participante_prueba_id', $participanteId)
                ->pluck('carton_id')
                ->toArray();

            $cartonesDisponibles = Carton::whereNotIn('id', $cartonesAsignadosHistorial)
                ->where('estado', 'disponible') // O sin importar estado si es virtual y multiuso
                ->inRandomOrder()
                ->limit($cantidad)
                ->get();

            if ($cartonesDisponibles->count() < $cantidad) {
                throw new \Exception('No hay suficientes cartones únicos disponibles para asignar.');
            }

            foreach ($cartonesDisponibles as $carton) {
                ParticipanteCartonPrueba::create([
                    'participante_prueba_id' => $participanteId,
                    'jugada_id' => $sala->id,
                    'carton_id' => $carton->id,
                ]);
            }

            // Aumentar el pozo de la sala:
            $aportePozo = $costoTotal * 0.05; // 5% configurable
            $sala->increment('pozo_acumulado', $aportePozo);

            DB::commit();

            return back()->with('success', "Has comprado $cantidad cartones con éxito.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function estado($id)
    {
        $sala = Jugada::with('sorteo')->findOrFail($id);
        
        $estado = [
            'estado_jugada' => $sala->estado,
            'estado_sorteo' => $sala->sorteo ? $sala->sorteo->estado : 'pendiente',
            'bolillas' => $sala->sorteo ? $sala->sorteo->getBolillas() : [],
            'bolilla_actual' => $sala->sorteo ? $sala->sorteo->bolilla_actual : null,
            'ganadores' => []
        ];

        // Podemos buscar ganadores en SorteoGanador si existe la tabla, 
        // o si Sorteo maneja los ganadores al final.
        // Simulamos un array de ganadores
        if ($sala->sorteo && $sala->sorteo->estado == 'finalizado') {
             // Opcional: Cargar nombre de ganadores
        }

        return response()->json($estado);
    }
}
