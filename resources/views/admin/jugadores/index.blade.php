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

    <!-- Buscador y Filtros -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('jugadores.index') }}" method="GET" class="row gx-3 gy-2 align-items-center">
                <!-- Buscador Principal -->
                <div class="col-sm-6 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-dark text-white border-secondary"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Buscar por nombre, DNI o teléfono..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Filtros (Botones) -->
                <div class="col-sm-6 col-md-8 d-flex justify-content-md-end gap-2 flex-wrap">
                    <select name="estado" class="form-select bg-dark text-white border-secondary w-auto" onchange="this.form.submit()">
                        <option value="">Todos los Estados</option>
                        <option value="online" {{ request('estado') === 'online' ? 'selected' : '' }}>En Línea</option>
                        <option value="offline" {{ request('estado') === 'offline' ? 'selected' : '' }}>Desconectados</option>
                    </select>

                    <select name="juego" class="form-select bg-dark text-white border-secondary w-auto" onchange="this.form.submit()">
                        <option value="">Todos los Juegos</option>
                        <option value="Ruleta" {{ request('juego') === 'Ruleta' ? 'selected' : '' }}>Ruleta</option>
                        <option value="Blackjack" {{ request('juego') === 'Blackjack' ? 'selected' : '' }}>Blackjack</option>
                        <option value="Lobby" {{ request('juego') === 'Lobby' ? 'selected' : '' }}>Lobby</option>
                    </select>
                    
                    <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filtrar</button>
                    <a href="{{ route('jugadores.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Nómina Activa ({{ $jugadores->total() }} registros)</h6>
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
                        @forelse($jugadores as $jugador)
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
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No se encontraron jugadores que coincidan con los filtros.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginador -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $jugadores->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
