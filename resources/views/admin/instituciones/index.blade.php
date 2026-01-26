@extends('admin.layout')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">🏟 Instituciones</h1>
    <a href="{{ route('instituciones.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Institución
    </a>
</div>

<div class="card">
    <div class="card-body">

        <div class="mb-3">
            <input type="text" id="buscador" class="form-control" placeholder="🔎 Buscar institución...">
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="tablaInstituciones">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Ciudad</th>
                        <th>Provincia</th>
                        <th>Estado</th>
                        <th width="160">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instituciones as $institucion)
                        <tr>
                            <td>{{ $institucion->id }}</td>
                            <td>{{ $institucion->nombre }}</td>
                            <td>{{ $institucion->tipo }}</td>
                            <td>{{ $institucion->email_contacto }}</td>
                            <td>{{ $institucion->telefono }}</td>
                            <td>{{ $institucion->ciudad }}</td>
                            <td>{{ $institucion->provincia }}</td>
                            <td>
                                @if($institucion->activo)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Inactiva</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('instituciones.edit', $institucion->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('instituciones.destroy', $institucion->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Seguro que querés desactivar esta institución?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
document.getElementById('buscador').addEventListener('keyup', function () {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tablaInstituciones tbody tr');

    filas.forEach(fila => {
        let texto = fila.innerText.toLowerCase();
        fila.style.display = texto.includes(filtro) ? '' : 'none';
    });
});
</script>

@endsection
