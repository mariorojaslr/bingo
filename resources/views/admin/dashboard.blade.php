@extends('admin.layout')

@section('contenido')
<h2 class="mb-4">Dashboard</h2>

<div class="row g-4">

    <!-- Total de Cartones -->
    <div class="col-md-4">
        <div class="card card-metric p-3">
            <div class="d-flex align-items-center">
                <div class="me-3 fs-1 text-primary">
                    <i class="bi bi-ticket-perforated"></i>
                </div>
                <div>
                    <div class="text-muted">Cartones Generados</div>
                    <div class="fs-3 fw-bold">{{ \App\Models\Carton::count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Series -->
    <div class="col-md-4">
        <div class="card card-metric p-3">
            <div class="d-flex align-items-center">
                <div class="me-3 fs-1 text-success">
                    <i class="bi bi-collection"></i>
                </div>
                <div>
                    <div class="text-muted">Series</div>
                    <div class="fs-3 fw-bold">
                        {{ \App\Models\Carton::select('serie')->distinct()->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Último Cartón -->
    <div class="col-md-4">
        <div class="card card-metric p-3">
            <div class="d-flex align-items-center">
                <div class="me-3 fs-1 text-warning">
                    <i class="bi bi-hash"></i>
                </div>
                <div>
                    <div class="text-muted">Último Cartón</div>
                    <div class="fs-3 fw-bold">
                        #{{ \App\Models\Carton::orderByDesc('id')->value('numero_carton') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<hr class="my-4">

<div class="row g-4">

    <!-- Impresiones (placeholder) -->
    <div class="col-md-6">
        <div class="card card-metric p-3">
            <div class="fs-5 fw-bold mb-2">
                <i class="bi bi-printer"></i> Módulo de Impresión
            </div>
            <p class="mb-0 text-muted">
                Desde aquí podrás generar y controlar los lotes impresos de cartones en PDF.
            </p>
        </div>
    </div>

    <!-- Ventas (futuro) -->
    <div class="col-md-6">
        <div class="card card-metric p-3">
            <div class="fs-5 fw-bold mb-2">
                <i class="bi bi-cash-stack"></i> Módulo de Ventas
            </div>
            <p class="mb-0 text-muted">
                Próximamente: control de ventas, clientes, cajeros y recaudación.
            </p>
        </div>
    </div>

</div>
@endsection
