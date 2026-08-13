<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $empresa->nombre }} | Casino</title>
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ route('casino.manifest', $empresa->subdominio) }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primario: {{ $empresa->color_primario ?? '#00ff88' }};
            --color-secundario: {{ $empresa->color_secundario ?? '#00a8ff' }};
        }
        body { 
            background-color: #07070a; 
            color: #fff; 
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
        }
        
        .header { 
            padding: 20px; 
            background: linear-gradient(180deg, color-mix(in srgb, var(--color-primario) 20%, transparent) 0%, transparent 100%); 
        }
        .game-card { 
            background: rgba(20,20,25,0.8); 
            border: 1px solid color-mix(in srgb, var(--color-primario) 30%, transparent); 
            border-radius: 20px; 
            padding: 20px; 
            text-decoration: none; 
            display: block; 
            margin-bottom: 15px; 
            position: relative; 
            overflow: hidden; 
        }
        .game-card.featured { 
            border-color: var(--color-primario); 
            box-shadow: 0 0 20px color-mix(in srgb, var(--color-primario) 20%, transparent); 
            background: linear-gradient(45deg, #072a1e, #0a1118); 
        }
        .game-card h3 { color: #fff; font-weight: 900; margin: 0; }
        .badge-live { 
            background: #ff0055; 
            color: #fff; 
            padding: 4px 10px; 
            border-radius: 50px; 
            font-size: 0.7rem; 
            font-weight: bold; 
            position: absolute; 
            top: 15px; 
            right: 15px; 
            box-shadow: 0 0 10px #ff0055; 
            animation: pulse 2s infinite; 
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); box-shadow: 0 0 15px #ff0055; }
            100% { transform: scale(1); }
        }

        /* Pilar 1: Diseño Adaptativo Inteligente */
        .app-container {
            max-width: 100%;
            margin: 0 auto;
            min-height: 100vh;
            background-color: #07070a;
            position: relative;
        }
        @media (min-width: 768px) {
            body { background-color: #020205; background-image: radial-gradient(circle at center, color-mix(in srgb, var(--color-primario) 15%, transparent) 0%, transparent 50%); }
            .app-container {
                max-width: 480px;
                margin-top: 4vh;
                margin-bottom: 4vh;
                min-height: 92vh;
                border-radius: 40px;
                overflow: hidden;
                box-shadow: 0 0 50px rgba(0,0,0,0.8), 0 0 0 10px #1a1a24;
            }
        }
    </style>
</head>
<body>
<div class="app-container">
    <div class="header">
        <div class="d-flex justify-content-between align-items-center">
            <span class="fs-4 fw-bold" style="color: var(--color-primario);">{{ mb_strtoupper($empresa->nombre) }}</span>
            <div class="bg-dark px-3 py-1 rounded-pill border border-secondary text-info">
                <i class="bi bi-wallet2"></i> $0.00
            </div>
        </div>
    </div>
    
    <div class="container p-3">
        <h6 class="text-white-50 mb-3 text-uppercase" style="letter-spacing: 2px; font-size: 11px;">En Vivo Ahora</h6>
        
        @if($sorteoActivo)
            <a href="{{ route('tienda.show', $sorteoActivo->jugada_id) }}" class="game-card featured">
                <span class="badge-live"><i class="bi bi-broadcast"></i> EN VIVO</span>
                <i class="bi bi-play-circle fs-1 mb-2 d-block" style="color: var(--color-primario);"></i>
                <h3>BINGO TOTAL</h3>
                <p class="text-white-50 small mb-0 mt-1">Sorteo Activo en Curso</p>
                <div class="mt-3 bg-dark rounded p-2 text-center" style="border: 1px solid var(--color-primario);">
                    <small class="text-uppercase" style="font-size: 10px; color: var(--color-primario);">ENTRAR A JUGAR</small>
                </div>
            </a>
        @else
            <div class="game-card opacity-50">
                <i class="bi bi-calendar-x fs-1 mb-2 d-block text-secondary"></i>
                <h3>BINGO</h3>
                <p class="text-white-50 small mb-0 mt-1">No hay sorteos en este momento</p>
            </div>
        @endif

        <h6 class="text-white-50 mt-4 mb-3 text-uppercase" style="letter-spacing: 2px; font-size: 11px;">Juegos Clásicos</h6>
        
        <a href="#" class="game-card">
            <i class="bi bi-suit-spade fs-2 mb-2 d-block" style="color: #666;"></i>
            <h3 class="text-light fs-5">Blackjack</h3>
            <p class="text-white-50 small mb-0">Próximamente</p>
        </a>

        <a href="#" class="game-card">
            <i class="bi bi-circle-fill fs-2 mb-2 d-block text-danger" style="border: 4px dashed #fff; border-radius: 50%; width: 40px; height: 40px; display:flex; align-items:center; justify-content:center;"></i>
            <h3 class="text-light fs-5">Ruleta Europea</h3>
            <p class="text-white-50 small mb-0">Mesas VIP</p>
        </a>

        <a href="#" class="game-card">
            <i class="bi bi-trophy text-warning fs-2 mb-2 d-block"></i>
            <h3 class="text-light fs-5">Apuestas Deportivas</h3>
            <p class="text-white-50 small mb-0">Fútbol, NBA, F1 y más</p>
        </a>
    </div>
</div>
</body>
</html>
