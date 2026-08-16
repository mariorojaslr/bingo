<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PruebaParticipante;
use App\Models\PlayerSession;
use Carbon\Carbon;

class ActivityTrackerMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('participante_token');
        
        if (!$token) {
            return $next($request);
        }

        $participante = PruebaParticipante::where('token', $token)->first();

        if (!$participante) {
            return $next($request);
        }

        // 1. Verificación de Baneo
        if ($participante->is_banned) {
            // Eliminar la cookie y denegar acceso
            return redirect('/')->withCookie(cookie()->forget('participante_token'))->with('error', 'Tu cuenta ha sido suspendida. Contacta a soporte.');
        }

        // 2. Juego Responsable: Límite de Horas
        $limitMinutes = $participante->play_time_limit_minutes;
        if (is_null($limitMinutes)) {
            // Intentar heredar de la empresa (Provincia) a través de la jugada
            $empresa = null;
            if ($participante->jugada && $participante->jugada->institucion) {
                $empresa = $participante->jugada->institucion->empresa;
            }
            $limitMinutes = $empresa ? $empresa->default_play_time_limit_minutes : 240; 
        }

        // Calcular los minutos jugados hoy
        $playedToday = PlayerSession::where('participante_id', $participante->id)
            ->whereDate('session_start', Carbon::today())
            ->sum('duration_minutes');

        if ($playedToday >= $limitMinutes) {
            return redirect('/')->withCookie(cookie()->forget('participante_token'))->with('error', 'Has alcanzado tu límite diario de tiempo de juego (' . ($limitMinutes/60) . ' horas). Por favor, vuelve mañana.');
        }

        // 3. Rastreo de Sesión (Rango Horario)
        $now = now();
        $lastActivity = $participante->last_activity_at;

        // Si la última actividad fue hace más de 30 minutos, consideramos que es una sesión nueva
        if (!$lastActivity || $lastActivity->diffInMinutes($now) > 30) {
            PlayerSession::create([
                'participante_id' => $participante->id,
                'session_start' => $now,
                'session_end' => $now,
                'duration_minutes' => 0,
            ]);
        } else {
            // Actualizar la sesión actual
            $currentSession = PlayerSession::where('participante_id', $participante->id)
                ->orderBy('id', 'desc')
                ->first();

            if ($currentSession) {
                $currentSession->session_end = $now;
                $currentSession->duration_minutes = $currentSession->session_start->diffInMinutes($now);
                $currentSession->save();
            }
        }

        // 4. Actualizar Estado del Jugador
        $participante->last_activity_at = $now;
        
        // Identificar en qué juego está basado en la URL
        $path = $request->path();
        if (str_contains($path, 'blackjack')) {
            $participante->current_game = 'Blackjack';
        } elseif (str_contains($path, 'ruleta')) {
            $participante->current_game = 'Ruleta';
        } elseif (str_contains($path, 'lobby') || str_contains($path, 'tienda')) {
            $participante->current_game = 'Lobby';
        } else {
            $participante->current_game = 'Navegando';
        }

        $participante->save();

        return $next($request);
    }
}
