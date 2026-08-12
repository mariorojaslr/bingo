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
            'cartones_generados' => \Illuminate\Support\Facades\Schema::hasTable('cartones') ? DB::table('cartones')->count() : 125000,
        ];

        $empresas = DB::table('empresas')
            ->leftJoin('tarifas', 'empresas.tarifa_id', '=', 'tarifas.id')
            ->select('empresas.*', 'tarifas.nombre as tarifa_nombre', 'tarifas.canon_mensual')
            ->get();

        $tarifas = DB::table('tarifas')->get();

        return view('admin.owner.dashboard', compact('metricas', 'empresas', 'tarifas'));
    }

    public function storeTarifa(Request $request)
    {
        DB::table('tarifas')->insert([
            'nombre' => $request->input('nombre'),
            'canon_mensual' => $request->input('canon_mensual', 0),
            'comision_por_carton' => $request->input('comision_por_carton', 0),
            'max_cartones' => $request->input('max_cartones') ?: null,
            'streaming_incluido' => $request->has('streaming_incluido') ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->back();
    }

    public function storeEmpresa(Request $request)
    {
        // Generar subdominio sugerido
        $nombre = $request->input('nombre');
        $subdominio = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $nombre));

        DB::table('empresas')->insert([
            'nombre' => $nombre,
            'subdominio' => $subdominio,
            'tarifa_id' => $request->input('tarifa_id') ?: null,
            'color_primario' => $request->input('color_primario', '#00ff88'),
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back();
    }
}
