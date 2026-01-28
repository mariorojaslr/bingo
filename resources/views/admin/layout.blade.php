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

        .sidebar {
            width: 260px;
            background: #1f2937;
            min-height: 100vh;
            color: #fff;
            transition: width 0.3s;
        }

        .sidebar.collapsed { width: 70px; }

        .sidebar a, .sidebar span {
            color: #e5e7eb;
            text-decoration: none;
            white-space: nowrap;
        }

        .sidebar a:hover { background-color: #374151; color: #fff; }

        .sidebar .active { background-color: #2563eb; color: #fff; }

        .sidebar .section-title {
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #9ca3af;
            margin-top: 15px;
            margin-bottom: 5px;
            padding-left: 10px;
        }

        .sidebar.collapsed .section-title { display: none; }

        .sidebar .submenu { padding-left: 20px; font-size: 0.9rem; }

        .sidebar.collapsed .submenu {
            padding-left: 0;
            text-align: center;
        }

        .sidebar.collapsed .nav-link span.text { display: none; }

        .content {
            margin-left: 260px;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        .content.collapsed { margin-left: 70px; }

        .toggle-btn {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1000;
        }
    </style>
</head>
<body>

<button class="btn btn-primary toggle-btn" id="toggleSidebar">
    <i class="bi bi-list"></i>
</button>

<div class="d-flex">

    <div class="sidebar p-3 position-fixed" id="sidebar">
        <h4 class="text-center mb-4">🎯 <span class="text">Bingo</span></h4>

        <ul class="nav nav-pills flex-column gap-2">

            <li class="nav-item">
                <a class="nav-link active" href="/admin">
                    <i class="bi bi-speedometer2"></i> <span class="text">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/admin/cartones/generar">
                    <i class="bi bi-plus-square"></i> <span class="text">Generar Cartones</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/admin/cartones">
                    <i class="bi bi-grid-3x3-gap"></i> <span class="text">Ver Cartones</span>
                </a>
            </li>

            <div class="section-title">Cartones Físicos</div>

            <li class="nav-item submenu">
                <a class="nav-link" href="{{ route('admin.jugadas.index') }}">
                    📅 <span class="text">Jugadas</span>
                </a>
            </li>

            <li class="nav-item submenu">
                <a class="nav-link" href="{{ route('organizadores.index') }}">
                    🏢 <span class="text">Organizadores</span>
                </a>
            </li>

            <li class="nav-item submenu">
                <a class="nav-link" href="{{ route('instituciones.index') }}">
                    🏟 <span class="text">Instituciones</span>
                </a>
            </li>

            <div class="section-title">Impresión</div>

            <li class="nav-item submenu">
                <a class="nav-link" href="/admin/impresion">
                    🖨 <span class="text">Cartones Estándar</span>
                </a>
            </li>

            <!-- ===================== -->
            <!-- PRUEBAS INTERNAS -->
            <!-- ===================== -->
            <div class="section-title">Pruebas Internas</div>

            <li class="nav-item submenu">
                <a class="nav-link" href="{{ route('admin.pruebas.index') }}">
                    🧪 <span class="text">Panel de Pruebas</span>
                </a>
            </li>

            <li class="nav-item submenu">
                <a class="nav-link" href="{{ route('admin.pruebas.jugadas') }}">
                    🎯 <span class="text">Jugadas de Prueba</span>
                </a>
            </li>

            <li class="nav-item submenu">
                <a class="nav-link" href="{{ route('admin.pruebas.participantes') }}">
                    👥 <span class="text">Participantes</span>
                </a>
            </li>

            <hr class="text-secondary">

            <li class="nav-item">
                <span class="nav-link text-muted">
                    <i class="bi bi-cash-coin"></i> <span class="text">Ventas</span>
                </span>
            </li>

        </ul>
    </div>

    <div class="content w-100" id="mainContent">
        <div class="container-fluid">
            @yield('contenido')
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('mainContent');
    const toggleBtn = document.getElementById('toggleSidebar');

    if (localStorage.getItem('sidebar-collapsed') === 'true') {
        sidebar.classList.add('collapsed');
        content.classList.add('collapsed');
    }

    toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
        content.classList.toggle('collapsed');
        localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
