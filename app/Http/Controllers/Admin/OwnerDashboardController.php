<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerDashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('pwd') !== 'infinity2026') {
            return response('Acceso Denegado. Se requiere contraseña de seguridad.', 403);
        }

        // Métricas simuladas para el dashboard inicial (luego se conectarán a modelos reales)
        $metricas = [
            'total_empresas' => DB::table('empresas')->count(),
            'empresas_activas' => DB::table('empresas')->where('activo', true)->count(),
            'ingresos_estimados' => '$250,000', // Ejemplo de canon + comisiones
            'cartones_generados' => DB::table('cartones')->count() ?? 125000,
        ];

        $empresas = DB::table('empresas')
            ->leftJoin('tarifas', 'empresas.tarifa_id', '=', 'tarifas.id')
            ->select('empresas.*', 'tarifas.nombre as tarifa_nombre', 'tarifas.canon_mensual')
            ->get();

        $tarifas = DB::table('tarifas')->get();

        return view('admin.owner.dashboard', compact('metricas', 'empresas', 'tarifas'));
    }
}
