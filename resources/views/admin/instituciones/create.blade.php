@extends('admin.layout')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">➕ Nueva Institución</h1>
    <a href="{{ route('instituciones.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">

        <form action="{{ route('instituciones.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo</label>
                    <input type="text" name="tipo" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="Mail" class="form-control">
                </div>


                <div class="col-md-6 mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Ciudad</label>
                    <input type="text" name="ciudad" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Provincia</label>
                    <input type="text" name="provincia" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">País</label>
                    <input type="text" name="pais" class="form-control" value="Argentina">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Texto Encabezado</label>
                    <input type="text" name="texto_encabezado" class="form-control">
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Texto Pie</label>
                    <input type="text" name="texto_pie" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Logo</label>
                    <input type="file" name="logo" class="form-control">
                </div>

                <div class="col-md-6 mb-3 d-flex align-items-center">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="activo" value="1" checked>
                        <label class="form-check-label">Institución Activa</label>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Guardar Institución
            </button>

        </form>

    </div>
</div>

@endsection
