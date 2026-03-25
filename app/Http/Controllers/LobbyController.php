<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jugada;

class LobbyController extends Controller
{
    /**
     * Muestra el Lobby público de salas de juego activas.
     */
    public function index()
    {
        // Obtenemos todas las salas (Jugadas) que están programadas o abiertas
        $salas = Jugada::with('organizador')
                       ->latest()
                       ->take(6)
                       ->get();

        // Para la vista Extraterrestre de "Salas Paralelas" que pidió el usuario,
        // vamos a añadir variables simuladas para la ocupación y precios de los cartones, 
        // hasta que expandamos la BD con esos campos específicos.
        
        $salasDecoradas = $salas->map(function ($sala) {
            
            // Simulación de Precios de Sala: 1000, 5000, 10000 según el ID
            $preciosBase = [1000, 2500, 5000, 10000];
            $precio = $preciosBase[$sala->id % count($preciosBase)];
            
            // Simulación de Capacidad de Sala
            $capacidadMaxima = $sala->cantidad_cartones > 0 ? $sala->cantidad_cartones : 1000;
            $ocupacionActual = rand(50, $capacidadMaxima - 10); // Llenado dinámico
            $porcentaje = ($ocupacionActual / $capacidadMaxima) * 100;
            
            $sala->precio_carton_virtual = $precio;
            $sala->ocupacion_actual = $ocupacionActual;
            $sala->capacidad_maxima = $capacidadMaxima;
            $sala->porcentaje_lleno = $porcentaje;
            
            // Estados de sala: Disponible, Por Llenarse, En Juego
            if ($sala->estado === 'en_produccion' || $sala->estado === 'en_juego') {
                $sala->estado_sala = 'en_curso';
            } elseif ($porcentaje > 95) {
                $sala->estado_sala = 'por_cerrar';
            } else {
                $sala->estado_sala = 'disponible';
            }

            return $sala;
        });

        return view('lobby.index', compact('salasDecoradas'));
    }
}
