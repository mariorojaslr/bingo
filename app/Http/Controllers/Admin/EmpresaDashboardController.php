<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresaDashboardController extends Controller
{
    public function index(Request $request, $id)
    {
        // En un SaaS real, verificaríamos permisos o la sesión de impersonate.
        // Aquí lo dejamos abierto para la DEMO comercial.
        
        $empresa = DB::table('empresas')->where('id', $id)->first();

        if (!$empresa) {
            return response('Empresa no encontrada.', 404);
        }

        // Obtener jugadas de esta empresa para las mtricas
        // Simplificado para la DEMO comercial
        
        $ventasReales = 154000;
        $cartonesReales = 308;
        
        $ventasPiloto = 45000;
        $cartonesPiloto = 90;
        
        if ($empresa->modo_prueba) {
            $totalVentas = $ventasReales + $ventasPiloto;
            $totalCartones = $cartonesReales + $cartonesPiloto;
        } else {
            $totalVentas = $ventasReales;
            $totalCartones = $cartonesReales;
        }

        return view('admin.empresa.dashboard', compact('empresa', 'totalVentas', 'totalCartones'));
    }

    public function togglePrueba(Request $request, $id)
    {
        $empresa = DB::table('empresas')->where('id', $id)->first();
        if (!$empresa) {
            return response()->json(['error' => 'Empresa no encontrada'], 404);
        }

        $nuevoModo = !$empresa->modo_prueba;
        
        DB::table('empresas')->where('id', $id)->update(['modo_prueba' => $nuevoModo]);

        return response()->json(['success' => true, 'modo_prueba' => $nuevoModo]);
    }
}
