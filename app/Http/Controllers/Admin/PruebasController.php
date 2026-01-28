<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParticipantePrueba;
use App\Models\Jugada;

class PruebasController extends Controller
{
    public function index()
    {
        return view('admin.pruebas.index');
    }

    public function participantes()
    {
        $participantes = ParticipantePrueba::all();
        return view('admin.pruebas.participantes', compact('participantes'));
    }

    public function jugadas()
    {
        $jugadas = Jugada::where('nombre_jugada', 'like', '%PRUEBA%')->get();
        return view('admin.pruebas.jugadas', compact('jugadas'));
    }
}
