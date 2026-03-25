@extends('admin.layout')

@section('contenido')

<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h6 class="text-uppercase" style="color: var(--neon-green); font-weight: 600; letter-spacing: 2px;">GESTIÓN DE EVENTOS</h6>
        <h2 class="display-6 fw-bold mb-0 text-white" style="font-family: 'Outfit';">Salas de Sorteo Activas</h2>
    </div>
    <a href="{{ route('admin.jugadas.create') }}" class="btn btn-neon rounded-pill px-4">
        <i class="bi bi-plus-circle me-2"></i> Crear Sala
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success bg-success border-0 text-white shadow" style="border-radius: 12px; background: rgba(0, 255, 136, 0.2) !important;">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
@endif

<div class="card glass-card border-0">
    <div class="card-body p-0">
        @if($jugadas->count() == 0)
            <div class="text-center py-5">
                <i class="bi bi-controller text-white-50" style="font-size: 4rem; opacity: 0.2;"></i>
                <h4 class="text-white-50 mt-3" style="font-family: 'Outfit';">No tienes salas de juego publicadas.</h4>
                <p class="text-secondary">Crea tu primer evento para generar cartones y habilitar el sorteador.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0" style="background: transparent;">
                    <thead style="background: rgba(0,0,0,0.5);">
                        <tr>
                            <th class="border-secondary text-secondary fw-normal py-3 px-4">N°</th>
                            <th class="border-secondary text-secondary fw-normal py-3">NOMBRE DE LA SALA</th>
                            <th class="border-secondary text-secondary fw-normal py-3">FECHA Y HORA</th>
                            <th class="border-secondary text-secondary fw-normal py-3">FORMATO LÁSER</th>
                            <th class="border-secondary text-secondary fw-normal py-3">ESTADO</th>
                            <th class="border-secondary text-end fw-normal py-3 px-4">ADMINISTRAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jugadas as $jugada)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td class="px-4 text-white-50">#{{ str_pad($jugada->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="fw-bold text-white">
                                    {{ mb_strtoupper($jugada->nombre_jugada) }}
                                    <div class="text-muted small fw-normal"><i class="bi bi-building me-1"></i> {{ $jugada->organizador->nombre_fantasia ?? 'Global' }}</div>
                                </td>
                                <td>
                                    <div class="text-white"><i class="bi bi-calendar-event me-2 text-white-50"></i>{{ \Carbon\Carbon::parse($jugada->fecha_evento)->format('d/m/Y') }}</div>
                                    <div class="text-white-50 small"><i class="bi bi-clock me-2"></i>{{ \Carbon\Carbon::parse($jugada->hora_evento)->format('H:i') }} HS</div>
                                </td>
                                <td>
                                    <span class="badge" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #ccc;">
                                        <i class="bi bi-printer me-1"></i> {{ $jugada->cartones_por_hoja }} por hoja
                                    </span>
                                </td>
                                <td>
                                    @if($jugada->estado === 'en_juego' || $jugada->estado === 'en_produccion')
                                        <span class="badge rounded-pill px-3 py-2" style="background: rgba(0, 255, 136, 0.1); color: var(--neon-green); border: 1px solid var(--neon-green);">
                                            <i class="bi bi-record-circle-fill me-1 pulse-live"></i> ACTIVADA
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2 bg-secondary bg-opacity-25 text-white-50 border border-secondary">
                                            {{ strtoupper($jugada->estado) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end px-4">
                                    <a href="{{ route('admin.jugadas.show', $jugada->id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                                        <i class="bi bi-cpu me-1"></i> Panel de Control
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
    .pulse-live { animation: pulseGreen 2s infinite; }
    @keyframes pulseGreen { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
</style>

@endsection
