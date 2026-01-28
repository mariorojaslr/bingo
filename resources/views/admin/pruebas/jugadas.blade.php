@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Jugadas de Prueba</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Jugada</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jugadas as $j)
            <tr>
                <td>{{ $j->nombre_jugada }}</td>
                <td>{{ $j->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $j->estado ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
