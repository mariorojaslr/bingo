@extends('admin.layout')

@section('titulo', 'Gestión de Jugadores')

@section('contenido')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Jugadores</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Nómina Activa</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Saldo Fichas</th>
                            <th>Juego Actual</th>
                            <th>Límite Diario</th>
                            <th>Baneado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jugadores as $jugador)
                        @php
                            $isOnline = $jugador->last_activity_at && $jugador->last_activity_at->diffInMinutes(now()) < 10;
                            $defaultLimit = 4; // Por ahora fijo, después hereda
                            if($jugador->play_time_limit_minutes) {
                                $defaultLimit = $jugador->play_time_limit_minutes / 60;
                            }
                        @endphp
                        <tr>
                            <td class="text-center">
                                @if($isOnline)
                                    <span class="badge bg-success text-white"><i class="bi bi-circle-fill"></i> Online</span>
                                @else
                                    <span class="badge bg-secondary text-white">Offline</span>
                                @endif
                            </td>
                            <td>{{ $jugador->nombre }} {{ $jugador->apellido }}</td>
                            <td>{{ $jugador->telefono ?? '-' }}</td>
                            <td class="font-weight-bold text-success">${{ number_format($jugador->saldo_fichas, 0) }}</td>
                            <td>
                                @if($isOnline && $jugador->current_game)
                                    <span class="badge bg-info text-dark">{{ $jugador->current_game }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $defaultLimit }} hrs</td>
                            <td class="text-center">
                                @if($jugador->is_banned)
                                    <span class="badge bg-danger text-white">SÍ</span>
                                @else
                                    <span class="badge bg-success text-white">NO</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('jugadores.show', $jugador->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Ver Detalle
                                </a>
                                <form action="{{ route('jugadores.toggle_ban', $jugador->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $jugador->is_banned ? 'btn-success' : 'btn-danger' }}" onclick="return confirm('¿Estás seguro?')">
                                        <i class="bi {{ $jugador->is_banned ? 'bi-unlock' : 'bi-lock' }}"></i>
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
@endsection
