<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MegaSorteoController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->cookie('participante_token');
        $participanteLogueado = null;
        if ($token) {
            $participanteLogueado = \App\Models\PruebaParticipante::where('token', $token)->first();
        }

        // Buscar el próximo Mega Sorteo pendiente
        $nextSorteo = \App\Models\MegaSorteo::where('status', 'pending')
                        ->orderBy('draw_date', 'asc')
                        ->first();

        // Si no hay sorteo, crear uno fake para la demo
        if (!$nextSorteo) {
            $nextSorteo = \App\Models\MegaSorteo::create([
                'draw_date' => now()->addDays(2)->setTime(22, 0),
                'status' => 'pending',
                'ticket_price' => 100.00,
                'accumulated_jackpot' => 500000.00,
            ]);
        }

        return view('casino.mega_sorteo.index', compact('participanteLogueado', 'nextSorteo'));
    }

    public function buyTicket(Request $request, $id)
    {
        $token = $request->cookie('participante_token');
        $participante = \App\Models\PruebaParticipante::where('token', $token)->first();

        if (!$participante) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 401);
        }

        $sorteo = \App\Models\MegaSorteo::where('id', $id)->where('status', 'pending')->firstOrFail();
        
        $request->validate([
            'numbers' => 'required|array|size:6',
            'numbers.*' => 'integer|min:0|max:45'
        ]);

        $numbers = $request->input('numbers');
        
        // Validar que no haya repetidos
        if (count(array_unique($numbers)) !== 6) {
            return response()->json(['success' => false, 'message' => 'Los números no pueden repetirse.'], 400);
        }

        if ($participante->saldo_fichas < $sorteo->ticket_price) {
            return response()->json(['success' => false, 'message' => 'Saldo insuficiente.'], 400);
        }

        // Descontar
        $participante->saldo_fichas -= $sorteo->ticket_price;
        $participante->save();

        // Sumar al pozo acumulado un porcentaje (ej: 50% va al pozo)
        $sorteo->accumulated_jackpot += ($sorteo->ticket_price * 0.5);
        $sorteo->save();

        // Crear ticket
        sort($numbers);
        \App\Models\MegaSorteoTicket::create([
            'mega_sorteo_id' => $sorteo->id,
            'participante_id' => $participante->id,
            'numbers' => $numbers
        ]);

        return response()->json([
            'success' => true, 
            'message' => '¡Ticket comprado con éxito!',
            'new_balance' => $participante->saldo_fichas,
            'new_jackpot' => $sorteo->accumulated_jackpot
        ]);
    }

    public function myTickets(Request $request)
    {
        $token = $request->cookie('participante_token');
        $participante = \App\Models\PruebaParticipante::where('token', $token)->firstOrFail();

        $tickets = \App\Models\MegaSorteoTicket::with('megaSorteo')
                    ->where('participante_id', $participante->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('casino.mega_sorteo.mis_tickets', compact('participante', 'tickets'));
    }
}
