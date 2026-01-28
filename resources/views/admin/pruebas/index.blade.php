@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>🧪 Pruebas Internas</h1>
    <ul>
        <li><a href="{{ route('admin.pruebas.jugadas') }}">Jugadas de Prueba</a></li>
        <li><a href="{{ route('admin.pruebas.participantes') }}">Participantes Piloto</a></li>
    </ul>
</div>
@endsection
