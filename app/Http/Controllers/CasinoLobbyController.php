<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Sorteo;

class CasinoLobbyController extends Controller
{
    /**
     * Muestra el Lobby de Casino para una Marca Blanca específica usando su subdominio.
     */
    public function index($subdominio)
    {
        // Buscar la empresa por su subdominio
        $empresa = Empresa::where('subdominio', $subdominio)
                    ->where('activo', true)
                    ->firstOrFail();

        // Determinar qué tema compró o seleccionó el cliente (por defecto 'neon')
        $tema = $empresa->tema_visual ?: 'neon';

        // Buscar si la empresa tiene una Sala de Bingo activa en este momento (Sorteo en curso)
        // Para simplificar, buscamos el último sorteo activo de las jugadas que pertenecen a esta empresa.
        // En una estructura real, Jugada pertenece a Empresa.
        $sorteoActivo = Sorteo::where('estado', 'en_curso')
                        ->whereHas('jugada', function($q) use ($empresa) {
                            $q->where('empresa_id', $empresa->id);
                        })
                        ->latest()
                        ->first();

        // Renderizar la vista correspondiente al TEMA del cliente, pasándole sus colores corporativos
        if (!view()->exists("casino.themes.{$tema}")) {
            // Fallback por si acaso borran un tema
            $tema = 'neon';
        }

        return view("casino.themes.{$tema}", compact('empresa', 'sorteoActivo'));
    }
}
