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

        return view('admin.empresa.dashboard', compact('empresa'));
    }
}
