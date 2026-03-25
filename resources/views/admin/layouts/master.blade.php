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
            --bg-panel: #0a0a0a;
            --border-glass: rgba(255, 255, 255, 0.05);
            --text-primary: #ffffff;
            --text-muted: #888888;
            --accent-gold: #D4AF37;
            --accent-emerald: #00FF88;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-primary);
            overflow-x: hidden;
            display: flex;
            min-height: 100vh;
        }

        h1, h2, h3, h4, .brand-font {
            font-family: 'Outfit', sans-serif;
        }

        /* Lateral Menu (Sidebar) Rolls Royce */
        .sidebar {
            width: 280px;
            background: rgba(5, 5, 5, 0.8);
            border-right: 1px solid var(--border-glass);
            backdrop-filter: blur(20px);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .brand-logo svg {
            width: 45px; height: 45px;
            filter: drop-shadow(0 0 10px rgba(212, 175, 55, 0.3));
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }
        
        .nav-link {
            color: var(--text-muted);
            padding: 0.8rem 1rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.03);
            border-left: 3px solid var(--accent-gold);
        }

        .nav-link.emerald-glow:hover, .nav-link.emerald-glow.active {
            border-left-color: var(--accent-emerald);
        }

        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            margin-left: 280px;
            padding: 2rem 3rem;
            min-height: 100vh;
        }

        /* Header Top */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-glass);
        }

        .owner-badge {
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid rgba(212, 175, 55, 0.2);
            color: var(--accent-gold);
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }

        .avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--bg-panel), #1a1a1a);
            border: 1px solid var(--border-glass);
            display: flex; align-items: center; justify-content: center;
        }

        /* Widget Cards (Glassmorphism) */
        .glass-card {
            background: var(--bg-panel);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            padding: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            border-color: rgba(255,255,255,0.1);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(90deg, #fff, #999);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .icon-box {
            width: 50px; height: 50px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
        }
        .icon-box.gold { background: rgba(212, 175, 55, 0.1); color: var(--accent-gold); }
        .icon-box.emerald { background: rgba(0, 255, 136, 0.1); color: var(--accent-emerald); }
        .icon-box.blue { background: rgba(0, 168, 255, 0.1); color: #00A8FF; }
        .icon-box.purple { background: rgba(162, 0, 255, 0.1); color: #A200FF; }

        .btn-logout {
            background: transparent;
            color: #ff4757;
            border: 1px solid rgba(255, 71, 87, 0.3);
            border-radius: 8px;
            padding: 8px 20px;
            transition: all 0.3s;
        }
        .btn-logout:hover {
            background: rgba(255, 71, 87, 0.1);
            color: #ff4757;
        }
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
