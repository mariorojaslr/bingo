@extends('admin.layout')

@section('contenido')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">🏢 Organizadores</h1>
        <a href="{{ route('organizadores.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Organizador
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <div class="mb-3">
                <input type="text" id="buscador" class="form-control" placeholder="Buscar organizador...">
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tabla-organizadores">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Razón Social</th>
                            <th>Nombre Fantasía</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($organizadores as $organizador)
                            <tr>
                                <td>{{ $organizador->id }}</td>
                                <td>{{ ucfirst(str_replace('_',' ',$organizador->tipo)) }}</td>
                                <td>{{ $organizador->razon_social }}</td>
                                <td>{{ $organizador->nombre_fantasia }}</td>
                                <td>{{ $organizador->email_contacto }}</td>
                                <td>{{ $organizador->telefono }}</td>
                                <td>
                                    @if($organizador->activo)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('organizadores.edit', $organizador) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('organizadores.destroy', $organizador) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Desactivar este organizador?')">
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
</div>

<script>
document.getElementById('buscador').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tabla-organizadores tbody tr');

    filas.forEach(function(fila) {
        let texto = fila.innerText.toLowerCase();
        fila.style.display = texto.includes(filtro) ? '' : 'none';
    });
});
</script>

@endsection
