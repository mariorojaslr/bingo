<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organizador;
use Illuminate\Http\Request;

class OrganizadorController extends Controller
{
    public function index()
    {
        $organizadores = Organizador::orderBy('razon_social')->get();
        return view('admin.organizadores.index', compact('organizadores'));
    }

    public function create()
    {
        return view('admin.organizadores.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'tipo' => 'required|string',
        'razon_social' => 'required|string',
        'nombre_fantasia' => 'required|string',
        'cuit' => 'required|string',
        'email_contacto' => 'required|email',
        'telefono' => 'required|string',
        'direccion' => 'required|string',
        'activo' => 'required|boolean',
    ]);

    $data = $request->all();

    // Usuario creador (por ahora fijo hasta tener login completo)
    $data['user_id'] = \App\Models\User::first()->id;

    Organizador::create($data);

    return redirect()
        ->route('organizadores.index')
        ->with('success', 'Organizador creado correctamente.');
}


    public function edit(Organizador $organizador)
    {
        return view('admin.organizadores.edit', compact('organizador'));
    }

    public function update(Request $request, Organizador $organizador)
    {
        $request->validate([
            'tipo' => 'required',
            'razon_social' => 'required',
            'email_contacto' => 'nullable|email',
        ]);

        $organizador->update($request->all());

        return redirect()
            ->route('organizadores.index')
            ->with('success', 'Organizador actualizado correctamente.');
    }

    public function destroy(Organizador $organizador)
    {
        $organizador->update(['activo' => false]);

        return redirect()
            ->route('organizadores.index')
            ->with('success', 'Organizador desactivado correctamente.');
    }
}
