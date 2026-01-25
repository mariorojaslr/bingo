@extends('admin.layout')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📅 Jugadas</h2>
    <a href="{{ route('admin.jugadas.create') }}" class="btn btn-primary">
        ➕ Nueva Jugada
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($jugadas->count() == 0)
            <p class="text-muted">No hay jugadas creadas todavía.</p>
        @else
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Institución</th>
                        <th>Organizador</th>
                        <th>Fecha</th>
                        <th>Formato</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jugadas as $jugada)
                        <tr>
                            <td>{{ $jugada->id }}</td>
                            <td>{{ $jugada->nombre_jugada }}</td>
                            <td>{{ $jugada->institucion->nombre ?? '—' }}</td>
                            <td>{{ $jugada->organizador->nombre_fantasia ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($jugada->fecha_evento)->format('d/m/Y') }}</td>
                            <td>{{ $jugada->cartones_por_hoja }} por hoja</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ ucfirst($jugada->estado) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.jugadas.show', $jugada->id) }}" class="btn btn-sm btn-info">
                                    👁 Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@endsection
