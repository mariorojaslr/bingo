@extends('admin.layout')

@section('contenido')
<div class="container-fluid">

    <h2 class="mb-3">➕ Crear Nueva Jugada</h2>

    <div class="card card-metric">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.jugadas.store') }}">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Organizador</label>
                        <select name="organizador_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($organizadores as $org)
                                <option value="{{ $org->id }}">
                                    {{ $org->razon_social ?? $org->nombre_fantasia ?? 'Organizador #' . $org->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Institución</label>
                        <select name="institucion_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($instituciones as $inst)
                                <option value="{{ $inst->id }}">
                                    {{ $inst->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre de la Jugada</label>
                    <input type="text" name="nombre_jugada" class="form-control" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Fecha del Evento</label>
                        <input type="date" name="fecha_evento" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Hora</label>
                        <input type="time" name="hora_evento" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Lugar</label>
                        <input type="text" name="lugar" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Formato de impresión</label>
                    <select name="cartones_por_hoja" class="form-select" required>
                        <option value="3">3 cartones por hoja (A4)</option>
                        <option value="6">6 cartones por hoja (doble corte)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Precio por hoja</label>
                    <input type="number" step="0.01" name="precio_hoja" class="form-control" required>
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.jugadas.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar Jugada
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
