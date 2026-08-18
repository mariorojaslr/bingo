@extends('layouts.app') <!-- Asumiendo layout principal -->

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center">Salas de Bingo Virtual</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        @forelse($salas as $sala)
            @php
                $fechaHora = \Carbon\Carbon::parse($sala->fecha_evento . ' ' . $sala->hora_evento);
                $isPast = $fechaHora->isPast();
            @endphp
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-{{ $isPast ? 'danger' : 'primary' }}">
                    <div class="card-header bg-{{ $isPast ? 'danger' : 'primary' }} text-white">
                        <h5 class="card-title mb-0">{{ $sala->nombre_jugada }}</h5>
                    </div>
                    <div class="card-body">
                        <h4 class="text-success text-center mb-3">
                            Pozo: ${{ number_format($sala->pozo_acumulado, 2) }}
                        </h4>
                        <p class="card-text">
                            <strong>Inicio:</strong> {{ $fechaHora->format('d/m/Y H:i') }} <br>
                            <strong>Cartón:</strong> ${{ number_format($sala->precio_hoja, 2) }}
                        </p>

                        <div class="text-center mb-3">
                            <span class="badge bg-warning text-dark fs-5 countdown" data-time="{{ $fechaHora->timestamp }}">
                                Calculando tiempo...
                            </span>
                        </div>

                    </div>
                    <div class="card-footer bg-white text-center">
                        <a href="{{ route('casino.bingo_virtual.show', $sala->id) }}" class="btn btn-outline-primary w-100 fw-bold">
                            Ingresar a la Sala
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    No hay salas programadas en este momento. Vuelve más tarde.
                </div>
            </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countdowns = document.querySelectorAll('.countdown');
        
        setInterval(() => {
            const now = Math.floor(Date.now() / 1000);
            
            countdowns.forEach(el => {
                const targetTime = parseInt(el.getAttribute('data-time'));
                const diff = targetTime - now;
                
                if (diff <= 0) {
                    el.innerHTML = "¡Empezó / En curso!";
                    el.classList.remove('bg-warning');
                    el.classList.add('bg-danger', 'text-white');
                } else {
                    const m = Math.floor(diff / 60);
                    const s = diff % 60;
                    el.innerHTML = `Faltan ${m}m ${s}s`;
                }
            });
        }, 1000);
    });
</script>
@endsection
