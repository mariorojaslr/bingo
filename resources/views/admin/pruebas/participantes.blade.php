@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Participantes de Prueba</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participantes as $p)
            <tr>
                <td>{{ $p->nombre }} {{ $p->apellido }}</td>
                <td>{{ $p->dni }}</td>
                <td>{{ $p->telefono }}</td>
                <td>{{ $p->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
