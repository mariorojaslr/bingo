<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Infinity Bingo | Panel de Empresa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-dark: #050505;
            --bg-panel: #0d0d0d;
            --border-glass: rgba(255, 255, 255, 0.08);
            --text-primary: #ffffff;
            --text-muted: #888888;
            --accent: #00A8FF; /* Azul Casino */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-primary);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .brand-font {
            font-family: 'Outfit', sans-serif;
        }

        /* Lateral Menu (Sidebar) */
        .sidebar {
            width: 280px;
            background: rgba(10, 10, 15, 0.95);
            border-right: 1px solid var(--border-glass);
            backdrop-filter: blur(20px);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .brand-logo { font-size: 1.5rem; font-weight: 800; color: #fff; letter-spacing: 1px; }

        .nav-item { margin-bottom: 0.3rem; }
        
        .nav-link {
            color: var(--text-muted);
            padding: 0.7rem 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.05);
            border-left: 3px solid var(--accent);
        }

        .section-title {
            font-size: 0.7rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
            padding-left: 10px;
        }

        /* Top Bar de Impersonation (Fantasma) */
        .impersonation-bar {
            background: linear-gradient(90deg, #ff4757, #ff6b81);
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            border-bottom: 1px solid rgba(0,0,0,0.5);
            margin-left: 280px;
        }

        .btn-panic {
            background: white;
            color: #ff4757;
            font-weight: 800;
            border: none;
            padding: 6px 15px;
            border-radius: 6px;
            font-size: 0.85rem;
            transition: 0.3s;
        }
        .btn-panic:hover { background: #f1f2f6; box-shadow: 0 0 15px rgba(255,255,255,0.5); }

        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            margin-left: 280px;
            padding: 2.5rem;
            min-height: 100vh;
        }
        
        /* Auto-Darking Fixes para las vistas internas viejas */
        .card { background: var(--bg-panel); border: 1px solid var(--border-glass); }
        .table { --bs-table-bg: transparent; --bs-table-color: var(--text-primary); }
    </style>
</head>
<body>

    <!-- MENÚ LATERAL (Sede del Cliente) -->
    <aside class="sidebar" id="sidebar">
        <div class="mb-4">
            <h4 class="brand-logo mb-0 text-center"><i class="bi bi-box me-2" style="color: var(--accent);"></i> {{ session('impersonating_organizador_name') ?? 'EMPRESA' }}</h4>
            <p class="text-muted text-center mt-1 mb-0" style="font-size: 0.7rem; letter-spacing: 2px;">PANEL OPERATIVO</p>
        </div>

        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a class="nav-link" href="/franquicia/dashboard">
                    <i class="bi bi-speedometer2"></i> Inicio (Dashboard)
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/admin/cartones/generar">
                    <i class="bi bi-plus-square"></i> Generador Cartones
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/admin/cartones">
                    <i class="bi bi-grid-3x3-gap"></i> Listado de Cartones
                </a>
            </li>

            <div class="section-title">Logística & Físicos</div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.jugadas.index') }}">
                    <i class="bi bi-calendar-event"></i> Jugadas (Eventos)
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="{{ route('organizadores.index') }}">
                    <i class="bi bi-buildings"></i> Organizadores
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('instituciones.index') }}">
                    <i class="bi bi-bank"></i> Instituciones (Premios)
                </a>
            </li>

            <div class="section-title">Producción</div>

            <li class="nav-item">
                <a class="nav-link" href="/admin/impresion">
                    <i class="bi bi-printer"></i> Lotes de Impresión
                </a>
            </li>

            <!-- PRUEBAS INTERNAS -->
            <div class="section-title">Pruebas (Sorteador V2)</div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.pruebas.index') }}">
                    <i class="bi bi-joystick"></i> Panel de Sorteos
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.pruebas.jugadas') }}">
                    <i class="bi bi-bullseye"></i> Jugadas Virtuales
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.pruebas.participantes') }}">
                    <i class="bi bi-people"></i> Padrón Participantes
                </a>
            </li>
            
            <div class="section-title">Terminal</div>
            <li class="nav-item">
                <a class="nav-link text-muted" href="#">
                    <i class="bi bi-cash-stack"></i> Módulo Ventas
                </a>
            </li>
        </ul>
    </aside>

    <div class="w-100 d-flex flex-column">
        <!-- BARRA ROJA DE ALERTA DE MODO DIOS -->
        @if(session()->has('impersonating_organizador_id'))
        <div class="impersonation-bar shadow">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-incognito fs-5"></i>
                <span class="text-uppercase" style="letter-spacing: 1px;">MODO FANTASMA: Viendo panel como "{{ session('impersonating_organizador_name') }}"</span>
            </div>
            <a href="{{ route('admin.leave_impersonation') }}" class="btn-panic text-decoration-none">
                <i class="bi bi-diagram-2-fill me-1"></i> VOLVER AL ÁTICO (OWNER)
            </a>
        </div>
        @endif

        <!-- ÁREA PRINCIPAL DONDE CARGAN TUS VIEJOS MÓDULOS -->
        <main class="main-content" id="mainContent">
            @if(session('error'))
                <div class="alert alert-danger mb-4 shadow-sm border-0"><i class="bi bi-shield-x"></i> {{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success mb-4 shadow-sm border-0 bg-success text-white"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
            @endif

            <div class="container-fluid p-0">
                @yield('contenido')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
