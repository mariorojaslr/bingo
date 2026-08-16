<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PruebaParticipante;
use App\Models\PlayerSession;

class JugadorController extends Controller
{
    public function index(Request $request)
    {
        $query = PruebaParticipante::query();

        // Búsqueda por texto (nombre, apellido, dni, telefono)
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%");
            });
        }

        // Filtro por Estado (Online / Offline)
        if ($estado = $request->get('estado')) {
            if ($estado === 'online') {
                $query->where('last_activity_at', '>=', now()->subMinutes(10));
            } elseif ($estado === 'offline') {
                $query->where(function($q) {
                    $q->whereNull('last_activity_at')
                      ->orWhere('last_activity_at', '<', now()->subMinutes(10));
                });
            }
        }

        // Filtro por Juego
        if ($juego = $request->get('juego')) {
            $query->where('current_game', $juego);
        }

        // Paginación de 15 elementos
        $jugadores = $query->orderBy('last_activity_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.jugadores.index', compact('jugadores'));
    }

    public function show($id)
    {
        $jugador = PruebaParticipante::findOrFail($id);
        $sesiones = PlayerSession::where('participante_id', $id)
            ->orderBy('session_start', 'desc')
            ->limit(50)
            ->get();
            
        return view('admin.jugadores.show', compact('jugador', 'sesiones'));
    }

    public function toggleBan($id)
    {
        $jugador = PruebaParticipante::findOrFail($id);
        $jugador->is_banned = !$jugador->is_banned;
        $jugador->save();

        return back()->with('success', $jugador->is_banned ? 'Jugador bloqueado exitosamente.' : 'Jugador desbloqueado exitosamente.');
    }

    public function updateLimits(Request $request, $id)
    {
        $request->validate([
            'play_time_limit_minutes' => 'nullable|integer|min:1',
            'daily_spend_limit' => 'nullable|numeric|min:0',
        ]);

        $jugador = PruebaParticipante::findOrFail($id);
        $jugador->play_time_limit_minutes = $request->play_time_limit_minutes;
        $jugador->daily_spend_limit = $request->daily_spend_limit;
        $jugador->save();

        return back()->with('success', 'Límites actualizados correctamente.');
    }
}
