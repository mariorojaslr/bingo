@extends('admin.layout')

@section('contenido')

<div class="container-fluid">
    <h1 class="mb-4">✏️ Editar Organizador</h1>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('organizadores.update', $organizador->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-control" required>
                            <option value="persona_fisica" {{ $organizador->tipo == 'persona_fisica' ? 'selected' : '' }}>Persona Física</option>
                            <option value="persona_juridica" {{ $organizador->tipo == 'persona_juridica' ? 'selected' : '' }}>Persona Jurídica</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Razón Social</label>
                        <input type="text" name="razon_social" class="form-control" value="{{ $organizador->razon_social }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre Fantasía</label>
                        <input type="text" name="nombre_fantasia" class="form-control" value="{{ $organizador->nombre_fantasia }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">CUIT</label>
                        <input type="text" name="cuit" class="form-control" value="{{ $organizador->cuit }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email_contacto" class="form-control" value="{{ $organizador->email_contacto }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ $organizador->telefono }}">
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="{{ $organizador->direccion }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Estado</label>
                        <select name="activo" class="form-control">
                            <option value="1" {{ $organizador->activo ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ !$organizador->activo ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>

                    <a href="{{ route('organizadores.index') }}" class="btn btn-secondary">
                        Volver
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection
