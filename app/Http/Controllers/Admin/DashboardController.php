<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Si el owner est impersonando una franquicia
        if (session()->has('impersonating_empresa_id')) {
            $empresaId = session('impersonating_empresa_id');
            // Delegamos al EmpresaDashboardController
            return app(\App\Http\Controllers\Admin\EmpresaDashboardController::class)->index($request, $empresaId);
        }

        // Si es el dueo supremo (Mario) o un admin general
        if (auth()->check() && auth()->user()->email === 'mario.rojas.coach@gmail.com') {
            return app(\App\Http\Controllers\Admin\OwnerDashboardController::class)->index($request);
        }

        // Si es una franquicia normal (en el futuro, leemos su empresa_id)
        // Por ahora, asumimos que si no es Mario, no tiene acceso a menos que est mapeado
        return response('Acceso Denegado. Contacte a Soporte.', 403);
    }
}
