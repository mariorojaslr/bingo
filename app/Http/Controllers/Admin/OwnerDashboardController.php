<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TransaccionCajero;
use App\Models\PruebaParticipante;

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

        // Obtener transacciones pendientes
        $transaccionesPendientes = TransaccionCajero::with('participante')
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.owner.dashboard', compact('metricas', 'empresas', 'tarifas', 'transaccionesPendientes'));
    }

    public function aprobarTransaccion($id, Request $request)
    {
        $transaccion = TransaccionCajero::findOrFail($id);
        if ($transaccion->estado !== 'pendiente') {
            return back()->with('error', 'La transacción ya fue procesada.');
        }

        $transaccion->estado = 'aprobado';
        $transaccion->save();

        $participante = PruebaParticipante::findOrFail($transaccion->participante_id);
        $participante->saldo_fichas += $transaccion->fichas;
        $participante->save();

        return back()->with('success', "Transacción aprobada. Se han acreditado {$transaccion->fichas} fichas a {$participante->nombre}.");
    }

    public function rechazarTransaccion($id, Request $request)
    {
        $transaccion = TransaccionCajero::findOrFail($id);
        if ($transaccion->estado !== 'pendiente') {
            return back()->with('error', 'La transacción ya fue procesada.');
        }

        $transaccion->estado = 'rechazado';
        $transaccion->save();

        return back()->with('success', "Transacción rechazada.");
    }

    public function storeTarifa(Request $request)
    {
        DB::table('tarifas')->insert([
            'nombre' => $request->nombre,
            'canon_mensual' => $request->canon_mensual ?? 0,
            'comision_por_carton' => $request->comision_por_carton ?? 0,
            'max_cartones' => $request->max_cartones ?: null,
            'streaming_incluido' => $request->has('streaming_incluido')
        ]);
        return back()->with('success', 'Plan comercial creado exitosamente.');
    }

    public function updateTarifa($id, Request $request)
    {
        DB::table('tarifas')->where('id', $id)->update([
            'nombre' => $request->nombre,
            'canon_mensual' => $request->canon_mensual ?? 0,
            'comision_por_carton' => $request->comision_por_carton ?? 0,
            'max_cartones' => $request->max_cartones ?: null,
            'streaming_incluido' => $request->has('streaming_incluido')
        ]);
        return back()->with('success', 'Plan comercial actualizado exitosamente.');
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
            'tema_visual' => 'neon',
            'idioma_defecto' => 'es',
            'moneda_defecto' => 'ARS',
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Empresa creada exitosamente.');
    }

    public function updateEmpresa($id, Request $request)
    {
        DB::table('empresas')->where('id', $id)->update([
            'nombre' => $request->input('nombre'),
            'tarifa_id' => $request->input('tarifa_id') ?: null,
            'activo' => $request->has('activo') ? 1 : 0,
            'canon_personalizado' => $request->input('canon_personalizado') !== null ? floatval($request->input('canon_personalizado')) : null,
            'comision_personalizada' => $request->input('comision_personalizada') !== null ? floatval($request->input('comision_personalizada')) : null,
            'notas_owner' => $request->input('notas_owner'),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Empresa actualizada correctamente con tus condiciones particulares.');
    }

    public function impersonate($empresa_id)
    {
        session(['impersonating_empresa_id' => $empresa_id]);
        return redirect()->route('demo.empresa', ['id' => $empresa_id, 'pwd' => 'infinity2026']);
    }

    public function stopImpersonate()
    {
        session()->forget('impersonating_empresa_id');
        return redirect()->route('demo.owner', ['pwd' => 'infinity2026']);
    }
}
