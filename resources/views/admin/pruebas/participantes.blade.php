@extends('admin.layout')

@section('contenido')
<h2 class="mb-4">👥 Participantes Piloto</h2>

<form action="{{ route('admin.pruebas.participantes.store') }}" method="POST" class="row g-2 mb-4">
    @csrf
    <div class="col-md-4">
        <input type="text" name="nombre" class="form-control" placeholder="Nombre completo" required>
    </div>
    <div class="col-md-4">
        <input type="text" name="telefono" class="form-control" placeholder="Teléfono (opcional)">
    </div>
    <div class="col-md-4">
        <button class="btn btn-success w-100">Agregar Participante</button>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Link de acceso</th>
        </tr>
    </thead>
    <tbody>
        @foreach($participantes as $p)
            <tr>
                <td>{{ $p->nombre }}</td>
                <td>{{ $p->telefono }}</td>
                <td>
                    <input type="text" class="form-control" readonly
                        value="{{ url('/piloto/'.$p->token) }}">
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
