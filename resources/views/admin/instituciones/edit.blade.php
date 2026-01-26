@extends('admin.layout')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">✏ Editar Institución</h1>
    <a href="{{ route('instituciones.index') }}" class="btn btn-secondary">
        ← Volver
    </a>
</div>

<div class="card">
    <div class="card-body">

        <form action="{{ route('instituciones.update', $institucion) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ $institucion->nombre }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Tipo</label>
                    <input type="text" name="tipo" class="form-control" value="{{ $institucion->tipo }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="Mail" class="form-control" value="{{ old('Mail', $institucion->Mail) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ $institucion->telefono }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label>Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="{{ $institucion->direccion }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Ciudad</label>
                    <input type="text" name="ciudad" class="form-control" value="{{ $institucion->ciudad }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Provincia</label>
                    <input type="text" name="provincia" class="form-control" value="{{ $institucion->provincia }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label>País</label>
                    <input type="text" name="pais" class="form-control" value="{{ $institucion->pais }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label>Texto Encabezado</label>
                    <input type="text" name="texto_encabezado" class="form-control" value="{{ $institucion->texto_encabezado }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label>Texto Pie</label>
                    <input type="text" name="texto_pie" class="form-control" value="{{ $institucion->texto_pie }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Logo</label>
                    <input type="file" name="logo" class="form-control">
                    @if($institucion->logo)
                        <img src="{{ asset('storage/'.$institucion->logo) }}" height="70" class="mt-2">
                    @endif
                </div>

                <div class="col-md-6 mb-3 d-flex align-items-center">
                    <div class="form-check mt-4">

                        <!-- Para que al destildar se envíe 0 -->
                        <input type="hidden" name="activo" value="0">

                        <input class="form-check-input"
                               type="checkbox"
                               name="activo"
                               value="1"
                               {{ $institucion->activo ? 'checked' : '' }}>

                        <label class="form-check-label">Institución Activa</label>
                    </div>
                </div>

            </div>

            <button class="btn btn-success">
                💾 Guardar Cambios
            </button>

        </form>

    </div>
</div>

@endsection
