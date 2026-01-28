<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PruebaParticipante;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PruebasController extends Controller
{
    public function index()
    {
        return view('admin.pruebas.index');
    }

    public function participantes()
    {
        $participantes = PruebaParticipante::all();
        return view('admin.pruebas.participantes', compact('participantes'));
    }

    public function storeParticipante(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'telefono' => 'nullable|string',
        ]);

        PruebaParticipante::create([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'token' => (string) Str::uuid(),
            'codigo_acceso' => strtoupper(Str::random(6)),
        ]);

        return redirect()->route('admin.pruebas.participantes');
    }

    public function jugadas()
    {
        return view('admin.pruebas.jugadas');
    }
}
