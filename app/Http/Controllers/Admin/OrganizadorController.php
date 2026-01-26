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
            'tipo' => 'required',
            'razon_social' => 'required',
            'email_contacto' => 'nullable|email',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id() ?? 1; // usuario creador (provisorio hasta multiusuario)

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
