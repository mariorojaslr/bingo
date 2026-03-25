<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // El dashboard principal del OWNER. 
        $organizadores = \App\Models\Organizador::latest()->get();
        return view('admin.dashboard.index', compact('organizadores'));
    }
}
