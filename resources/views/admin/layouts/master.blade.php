<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaaS Dashboard | Infinity Bingo</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-dark: #020202;
            --bg-panel: #080808;
            --border-glass: rgba(255, 255, 255, 0.08); /* Crystalline borders */
            --text-primary: #ffffff;
            --text-muted: #71717a;
            --accent-gold: #d4af37;
            --accent-emerald: #00ff88;
            --neon-blue: #00d2ff;
            --neon-purple: #9d50bb;
            --font-main: 'Inter', sans-serif;
            --font-headings: 'Outfit', sans-serif;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-dark);
            color: var(--text-primary);
            overflow-x: hidden;
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, .brand-font {
            font-family: var(--font-headings);
            letter-spacing: -0.02em;
        }

        /* Lateral Menu (Sidebar) Rolls Royce */
        .sidebar {
            width: 280px;
            background: rgba(5, 5, 5, 0.95);
            border-right: 1px solid var(--border-glass);
            backdrop-filter: blur(40px);
            padding: 2.5rem 1.7rem;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            box-shadow: 20px 0 50px rgba(0,0,0,0.5);
        }

        .brand-logo svg {
            width: 50px; height: 50px;
            filter: drop-shadow(0 0 15px rgba(212, 175, 55, 0.4));
        }

        .nav-item {
            margin-bottom: 0.75rem;
        }
        
        .nav-link {
            color: var(--text-muted);
            padding: 0.9rem 1.2rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 14px;
            font-weight: 500;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid transparent;
        }

        .nav-link i {
            font-size: 1.25rem;
            opacity: 0.7;
        }

        .nav-link:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }

        .nav-link.active {
            color: var(--text-primary);
            background: linear-gradient(90deg, rgba(212, 175, 55, 0.1), transparent);
            border-left: 4px solid var(--accent-gold);
            border-color: var(--border-glass);
            backdrop-filter: blur(10px);
        }

        .nav-link.emerald-glow.active {
            border-left-color: var(--accent-emerald);
            background: linear-gradient(90deg, rgba(0, 255, 136, 0.05), transparent);
        }

        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            margin-left: 280px;
            padding: 3rem 4rem;
            min-height: 100vh;
            background: radial-gradient(circle at top right, rgba(20, 20, 30, 0.4), transparent);
        }

        /* Header Top */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-glass);
        }

        .owner-badge {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-glass);
            color: #fff;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .owner-badge i {
            color: var(--accent-gold);
            filter: drop-shadow(0 0 5px var(--accent-gold));
        }

        /* Widget Cards (Glassmorphism) */
        .glass-card {
            background: linear-gradient(135deg, rgba(15, 15, 20, 0.8), rgba(5, 5, 5, 0.9));
            border: 1px solid var(--border-glass);
            border-radius: 24px;
            padding: 2rem;
            transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
        }
        
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        }

        .glass-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0,0,0,0.8);
            border-color: rgba(255,255,255,0.15);
        }

        .stat-value {
            font-size: 2.8rem;
            font-weight: 800;
            font-family: var(--font-headings);
            letter-spacing: -1px;
            color: #fff;
            text-shadow: 0 0 20px rgba(255,255,255,0.1);
        }

        .icon-box {
            width: 56px; height: 56px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .icon-box.gold { background: rgba(212, 175, 55, 0.1); color: var(--accent-gold); box-shadow: 0 0 20px rgba(212, 175, 55, 0.1); }
        .icon-box.emerald { background: rgba(0, 255, 136, 0.1); color: var(--accent-emerald); box-shadow: 0 0 20px rgba(0, 255, 136, 0.1); }
        .icon-box.blue { background: rgba(0, 210, 255, 0.1); color: var(--neon-blue); box-shadow: 0 0 20px rgba(0, 210, 255, 0.1); }
        .icon-box.purple { background: rgba(157, 80, 187, 0.1); color: var(--neon-purple); box-shadow: 0 0 20px rgba(157, 80, 187, 0.1); }

        .btn-logout {
            background: transparent;
            color: #ff4d4d;
            border: 1px solid rgba(255, 77, 77, 0.2);
            border-radius: 12px;
            padding: 10px 20px;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .btn-logout:hover {
            background: rgba(255, 77, 77, 0.1);
            border-color: #ff4d4d;
            box-shadow: 0 0 15px rgba(255, 77, 77, 0.2);
        }

        .badge-neon {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border-glass);
            color: var(--text-muted);
            border-radius: 50px;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Estilos de tabla premium */
        .table thead th {
            font-family: var(--font-headings);
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid var(--border-glass) !important;
        }

        .table tbody tr {
            transition: background 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(255,255,255,0.02) !important;
        }

        /* Scrollbar Personalizado */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: #222; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #333; }
    </style>
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="d-flex align-items-center gap-3 mb-5 brand-logo">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="45" stroke="#D4AF37" stroke-width="2" stroke-dasharray="10 5" fill="rgba(212,175,55,0.05)"/>
                <circle cx="50" cy="50" r="30" stroke="#FFFFFF" stroke-width="4" stroke-linecap="round" fill="none"/>
                <circle cx="50" cy="50" r="10" fill="#D4AF37"/>
            </svg>
            <div>
                <h4 class="mb-0 fw-bold brand-font text-white" style="letter-spacing: 1px;">INFINITY</h4>
                <p class="mb-0 text-muted" style="font-size: 0.75rem; letter-spacing: 3px;">SYSTEMS</p>
            </div>
        </div>

        <nav class="flex-column mb-auto">
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link active text-decoration-none">
                    <i class="bi bi-grid-1x2"></i> Panel Principal
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link text-decoration-none">
                    <i class="bi bi-buildings"></i> Empresas (Tenants)
                </a>
            </div>
            
            <p class="text-muted small fw-bold mt-4 mb-2 text-uppercase" style="letter-spacing: 1px; font-size: 0.7rem;">Infraestructura</p>
            
            <div class="nav-item">
                <a href="#" class="nav-link emerald-glow text-decoration-none">
                    <i class="bi bi-cloud-arrow-up"></i> Archivos (Bunny.net)
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link emerald-glow text-decoration-none">
                    <i class="bi bi-broadcast"></i> Streamings Activos
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link text-decoration-none">
                    <i class="bi bi-credit-card"></i> Facturación & Planes
                </a>
            </div>
        </nav>

        <div class="mt-auto pt-4 border-top" style="border-color: var(--border-glass) !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar"><i class="bi bi-person-fill text-muted"></i></div>
                    <div class="small">
                        <strong class="d-block text-white">Administrador</strong>
                        <span class="text-muted" style="font-size: 0.75rem;">Modo Dios</span>
                    </div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-logout w-100"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</button>
            </form>
        </div>
    </aside>

    <!-- CONTENT -->
    <main class="main-content">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
