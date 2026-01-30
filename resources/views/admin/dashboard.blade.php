@extends('admin.layout')

@section('contenido')
<h2 class="mb-4">📊 Dashboard General</h2>

<div class="row g-4">

    <!-- Cartones -->
    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-ticket-perforated fs-1 text-primary me-3"></i>
                <div>
                    <div class="text-muted">Cartones Generados</div>
                    <div class="fs-3 fw-bold">{{ \App\Models\Carton::count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jugadas -->
    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-calendar-event fs-1 text-success me-3"></i>
                <div>
                    <div class="text-muted">Jugadas Totales</div>
                    <div class="fs-3 fw-bold">{{ \App\Models\Jugada::count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jugadas en Curso -->
    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-play-circle fs-1 text-warning me-3"></i>
                <div>
                    <div class="text-muted">Jugadas Activas</div>
                    <div class="fs-3 fw-bold">{{ \App\Models\Sorteo::where('estado','en_curso')->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pruebas -->
    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-flask fs-1 text-danger me-3"></i>
                <div>
                    <div class="text-muted">Pruebas Internas</div>
                    <div class="fs-3 fw-bold">{{ \App\Models\Jugada::where('nombre_jugada','like','%prueba%')->count() }}</div>
                </div>
            </div>
        </div>
    </div>

</div>

<hr class="my-4">

<h4>💰 Información Económica</h4>

<div class="row g-4">

    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <i class="bi bi-cash-stack text-success fs-2"></i>
            <div class="text-muted">Recaudación Total</div>
            <div class="fs-4 fw-bold">$ 0.00</div>
            <small class="text-muted">(simulado, futuro módulo)</small>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <i class="bi bi-award text-warning fs-2"></i>
            <div class="text-muted">Premios Pagados</div>
            <div class="fs-4 fw-bold">$ 0.00</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <i class="bi bi-graph-up-arrow text-primary fs-2"></i>
            <div class="text-muted">Balance</div>
            <div class="fs-4 fw-bold">$ 0.00</div>
        </div>
    </div>

</div>

<hr class="my-4">

<h4>🎯 Estado del Sistema</h4>

<div class="row g-4">

        <div class="col-md-6">
            <div class="card p-3 shadow-sm">


               <h6><i class="bi bi-broadcast"></i> Sorteos en Tiempo Real</h6>

<div class="d-flex gap-4 flex-wrap">
    <div>
        Monitor:
        <a href="/monitor/jugada/1"
           target="_blank"
           class="link-primary">
            Abrir
        </a>
    </div>

    <div>
        Sorteador:
        <a href="/sorteador/jugada/1"
           target="_blank"
           class="link-primary">
            Abrir
        </a>
    </div>

    <div>
        Monitor TV:
        <a href="/monitor-tv"
           target="_blank"
           class="link-primary fw-semibold">
            Abrir
        </a>
    </div>
</div>



    </div>
</div>











    <div class="col-md-6">
        <div class="card p-3 shadow-sm">
            <h6><i class="bi bi-people"></i> Pruebas Remotas</h6>
            <p class="mb-1">Panel de Pruebas:
                <a href="{{ route('admin.pruebas.index') }}">Entrar</a>
            </p>
            <p class="mb-1">Participantes:
                <a href="{{ route('admin.pruebas.participantes') }}">Ver</a>
            </p>
        </div>
    </div>

</div>

<hr class="my-4">

<h4>📦 Módulos</h4>

<div class="row g-4">

    <div class="col-md-6">
        <div class="card p-3 shadow-sm">
            <h6><i class="bi bi-printer"></i> Impresión</h6>
            <p>Generación y control de lotes impresos de cartones.</p>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-3 shadow-sm">
            <h6><i class="bi bi-cart-check"></i> Ventas</h6>
            <p>Módulo comercial: recaudación, clientes, cajeros (en desarrollo).</p>
        </div>
    </div>

</div>
@endsection
