<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organizador;

class ImpersonateController extends Controller
{
    /**
     * El "Teletransportador" de Owner a Franquicia/Casino
     */
    public function enter(Organizador $organizador)
    {
        // 1. Validamos que solo el Owner pueda hacer este salto cuántico.
        if (auth()->user()->email !== 'mario.rojas.coach@gmail.com') {
            abort(403, 'Acesso Denegado Nivel Dios.');
        }

        // 2. Colocar la máscara de la empresa en la sesión
        session(['impersonating_organizador_id' => $organizador->id]);
        session(['impersonating_organizador_name' => $organizador->nombre_fantasia]);

        // 3. Empujar al usuario al panel de la franquicia
        return redirect()->route('tenant.dashboard');
    }

    /**
     * El botón de Pánico para Volver al Ático Owner
     */
    public function leave()
    {
        session()->forget('impersonating_organizador_id');
        session()->forget('impersonating_organizador_name');

        return redirect()->route('admin.dashboard');
    }
}
