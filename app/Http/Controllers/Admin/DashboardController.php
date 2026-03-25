<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // El dashboard principal del OWNER. 
        // Más adelante inyectaremos aquí las métricas reales de los tenants.
        return view('admin.dashboard.index');
    }
}
