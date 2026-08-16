@extends('admin.layout')

@section('titulo', 'Detalle de Jugador')

@section('contenido')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Perfil: {{ $jugador->nombre }} {{ $jugador->apellido }}</h1>
        <a href="{{ route('jugadores.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver a la Nómina
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Columna de Datos y Configuración -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Datos y Estado</h6>
                    <form action="{{ route('jugadores.toggle_ban', $jugador->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $jugador->is_banned ? 'btn-success' : 'btn-danger' }}">
                            {{ $jugador->is_banned ? 'Desbloquear Acceso' : 'Bloquear Acceso (Ban)' }}
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Teléfono:</strong> <span>{{ $jugador->telefono ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>DNI:</strong> <span>{{ $jugador->dni ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Saldo:</strong> <span class="text-success font-weight-bold">${{ number_format($jugador->saldo_fichas, 0) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Última Conexión:</strong> 
                            <span>{{ $jugador->last_activity_at ? $jugador->last_activity_at->diffForHumans() : 'Nunca' }}</span>
                        </li>
                    </ul>

                    <h6 class="font-weight-bold text-warning mt-4"><i class="bi bi-shield-lock"></i> Controles de Juego Responsable</h6>
                    <hr>
                    <form action="{{ route('jugadores.limits', $jugador->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Límite de Tiempo Diario (Minutos)</label>
                            <input type="number" class="form-control" name="play_time_limit_minutes" value="{{ $jugador->play_time_limit_minutes }}" placeholder="Ej: 240 (Dejar en blanco para usar el de la provincia)">
                            <small class="text-muted">Si se deja vacío, heredará el límite por defecto (4 hrs).</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Límite de Gasto Diario ($)</label>
                            <input type="number" step="0.01" class="form-control" name="daily_spend_limit" value="{{ $jugador->daily_spend_limit }}" placeholder="Ej: 50000 (Dejar en blanco para ilimitado)">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Guardar Controles</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Columna de Historial de Sesiones -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Historial de Conexiones (Rango Horario)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Ingreso</th>
                                    <th>Salida</th>
                                    <th>Tiempo Jugado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sesiones as $sesion)
                                <tr>
                                    <td>{{ $sesion->session_start->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $sesion->session_end ? $sesion->session_end->format('d/m/Y H:i:s') : 'En curso...' }}</td>
                                    <td>
                                        @if($sesion->duration_minutes > 60)
                                            {{ floor($sesion->duration_minutes / 60) }}h {{ $sesion->duration_minutes % 60 }}m
                                        @else
                                            {{ $sesion->duration_minutes }} minutos
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No hay registros de conexión aún.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
