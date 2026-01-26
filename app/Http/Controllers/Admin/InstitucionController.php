<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstitucionController extends Controller
{
    public function index()
    {
        $instituciones = Institucion::orderBy('nombre')->get();
        return view('admin.instituciones.index', compact('instituciones'));
    }

    public function create()
    {
        return view('admin.instituciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'tipo'             => 'nullable|string|max:100',
            'email'            => 'nullable|email',
            'telefono'         => 'nullable|string|max:50',
            'direccion'        => 'nullable|string|max:255',
            'ciudad'           => 'nullable|string|max:100',
            'provincia'        => 'nullable|string|max:100',
            'pais'             => 'nullable|string|max:100',
            'texto_encabezado' => 'nullable|string',
            'texto_pie'        => 'nullable|string',
        ]);

        $data = $request->all();
        $data['activa'] = $request->has('activa') ? 1 : 0;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('instituciones', 'public');
        }

        Institucion::create($data);

        return redirect()->route('instituciones.index')
            ->with('success', 'Institución creada correctamente.');
    }

    public function edit(Institucion $institucion)
    {
        return view('admin.instituciones.edit', compact('institucion'));
    }

    public function update(Request $request, Institucion $institucion)
    {
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'tipo'             => 'nullable|string|max:100',
            'email'            => 'nullable|email',
            'telefono'         => 'nullable|string|max:50',
            'direccion'        => 'nullable|string|max:255',
            'ciudad'           => 'nullable|string|max:100',
            'provincia'        => 'nullable|string|max:100',
            'pais'             => 'nullable|string|max:100',
            'texto_encabezado' => 'nullable|string',
            'texto_pie'        => 'nullable|string',
        ]);

        $data = $request->all();
        $data['activa'] = $request->has('activa') ? 1 : 0;

        if ($request->hasFile('logo')) {
            if ($institucion->logo) {
                Storage::disk('public')->delete($institucion->logo);
            }
            $data['logo'] = $request->file('logo')->store('instituciones', 'public');
        }

        $institucion->update($data);

        return redirect()->route('instituciones.index')
            ->with('success', 'Institución actualizada correctamente.');
    }

    public function destroy(Institucion $institucion)
    {
        $institucion->update(['activa' => 0]);

        return redirect()->route('instituciones.index')
            ->with('success', 'Institución desactivada correctamente.');
    }
}
