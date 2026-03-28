@extends('admin.layout')

@section('contenido')
<div class="container-fluid">

    <h1 class="mb-4">🧪 Centro de Control — Pruebas Internas</h1>

    <div class="row g-4">

        <!-- Jugadas de Prueba -->
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h5 class="fw-bold mb-2">🎯 Jugadas de Prueba</h5>
                <p class="text-muted">Crear, administrar y monitorear sorteos de test.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.pruebas.jugadas') }}" class="btn btn-primary">
                        Ver Jugadas
                    </a>
                </div>
            </div>
        </div>

        <!-- Participantes -->
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h5 class="fw-bold mb-2">👥 Participantes Piloto</h5>
                <p class="text-muted">Personas reales jugando con cartones virtuales.</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.pruebas.participantes') }}" class="btn btn-success">
                        Administrar Participantes
                    </a>
                </div>
            </div>
        </div>

        <!-- Enlaces Rápidos -->
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h5 class="fw-bold mb-2">🔗 Accesos Rápidos</h5>
                <p class="text-muted">Links para compartir por WhatsApp durante pruebas.</p>
                <ul class="list-unstyled mb-2">
                    <li>📺 Monitor público</li>
                    <li>🎫 Vista del jugador</li>
                    <li>🎛 Sorteador</li>
                </ul>
                <small class="text-muted">Se activan cuando hay jugada en curso.</small>
            </div>
        </div>

    </div>

    <hr class="my-4">

    <div class="row g-4">

        <!-- Estado General -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3">
                <h5 class="fw-bold mb-2">📊 Estado del Sistema</h5>
                <ul class="mb-0">
                    <li>Jugada activa</li>
                    <li>Bolillas extraídas</li>
                    <li>Línea detectada</li>
                    <li>Bingo detectado</li>
                    <li>En curso / Pausa / Finalizado</li>
                </ul>
            </div>
        </div>

        <!-- Próxima Etapa -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3 bg-light">
                <h5 class="fw-bold mb-2">🚀 Próxima Etapa</h5>
                <p class="mb-1">Juego virtual multiusuario</p>
                <ul class="mb-0">
                    <li>Salas privadas</li>
                    <li>Asignación de cartones</li>
                    <li>Detección en tiempo real</li>
                    <li>Modales de ganadores</li>
                </ul>
            </div>
        </div>

    </div>

</div>
@endsection
