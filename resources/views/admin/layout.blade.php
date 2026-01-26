<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bingo Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background-color: #f4f6f9; }
        .sidebar { width: 260px; background: #1f2937; min-height: 100vh; color: #fff; }
        .sidebar a, .sidebar span { color: #e5e7eb; text-decoration: none; }
        .sidebar a:hover { background-color: #374151; color: #fff; }
        .sidebar .active { background-color: #2563eb; color: #fff; }
        .sidebar .section-title { font-size: 0.75rem; letter-spacing: 1px; text-transform: uppercase; color: #9ca3af; margin-top: 15px; margin-bottom: 5px; padding-left: 10px; }
        .sidebar .submenu { padding-left: 20px; font-size: 0.9rem; }
        .content { margin-left: 260px; padding: 20px; }
        .card-metric { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    </style>
</head>
<body>

<div class="d-flex">

    <div class="sidebar p-3 position-fixed">
        <h4 class="text-center mb-4">🎯 Bingo Admin</h4>

        <ul class="nav nav-pills flex-column gap-2">

            <li class="nav-item">
                <a class="nav-link active" href="/admin">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/admin/cartones/generar">
                    <i class="bi bi-plus-square"></i> Generar Cartones
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/admin/cartones">
                    <i class="bi bi-grid-3x3-gap"></i> Ver Cartones
                </a>
            </li>

            <div class="section-title">Cartones Físicos</div>

            <li class="nav-item submenu">
                <a class="nav-link" href="{{ route('admin.jugadas.index') }}">
                    📅 Jugadas
                </a>
            </li>

            <!-- ORGANIZADORES ACTIVO Y FUNCIONAL -->
            <li class="nav-item submenu">
                <a class="nav-link" href="{{ route('organizadores.index') }}">
                    🏢 Organizadores
                </a>
            </li>

            <li class="nav-item submenu">
                <span class="nav-link text-muted">
                    🏟 Instituciones (próximamente)
                </span>
            </li>

            <div class="section-title">Impresión</div>

            <li class="nav-item submenu">
                <a class="nav-link" href="/admin/impresion">
                    🖨 Cartones Estándar (3 por hoja)
                </a>
            </li>

            <li class="nav-item submenu">
                <span class="nav-link text-muted">
                    ✂ Cartones Doble Corte (6 por hoja) – próximamente
                </span>
            </li>

            <li class="nav-item submenu">
                <span class="nav-link text-muted">
                    📘 Padrón Auditable – próximamente
                </span>
            </li>

            <hr class="text-secondary">

            <li class="nav-item">
                <span class="nav-link text-muted">
                    <i class="bi bi-cash-coin"></i> Ventas (en desarrollo)
                </span>
            </li>

            <li class="nav-item">
                <span class="nav-link text-muted">
                    <i class="bi bi-graph-up"></i> Reportes (en desarrollo)
                </span>
            </li>

            <li class="nav-item">
                <span class="nav-link text-muted">
                    <i class="bi bi-gear"></i> Configuración (en desarrollo)
                </span>
            </li>

        </ul>
    </div>

    <div class="content w-100">
        <div class="container-fluid">
            @yield('contenido')
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
