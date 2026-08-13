<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $empresa->nombre }} | Glass Casino</title>
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ route('casino.manifest', $empresa->subdominio) }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primario: {{ $empresa->color_primario ?? '#ff007f' }};
            --color-secundario: {{ $empresa->color_secundario ?? '#00d4ff' }};
        }
        body { 
            background: linear-gradient(135deg, #1e003b, #001233); 
            color: #fff; 
            font-family: 'Outfit', sans-serif; 
            min-height: 100vh; 
            position: relative;
            overflow-x: hidden;
        }
        body::before { content: ''; position: absolute; top: -50px; left: -50px; width: 200px; height: 200px; background: var(--color-primario); filter: blur(100px); border-radius: 50%; opacity: 0.5; z-index: -1; }
        body::after { content: ''; position: fixed; bottom: -50px; right: -50px; width: 200px; height: 200px; background: var(--color-secundario); filter: blur(100px); border-radius: 50%; opacity: 0.5; z-index: -1; }
        
        .header { padding: 20px; position: relative; z-index: 2; }
        .game-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 24px; padding: 20px; text-decoration: none; display: block; margin-bottom: 15px; position: relative; z-index: 2; color: #fff;}
        .game-card h3 { color: #fff; font-weight: 700; margin: 0; }
        .badge-live { background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 4px 10px; border-radius: 50px; font-size: 0.7rem; font-weight: bold; float: right; }
        
        /* Pilar 1: Diseño Adaptativo Inteligente */
        .app-container { max-width: 100%; margin: 0 auto; min-height: 100vh; position: relative; z-index: 1;}
        @media (min-width: 768px) {
            body { background: #000; }
            .app-container { max-width: 480px; margin-top: 4vh; margin-bottom: 4vh; min-height: 92vh; border-radius: 40px; overflow: hidden; box-shadow: 0 0 50px rgba(0,0,0,0.8), 0 0 0 10px #111; background: linear-gradient(135deg, #1e003b, #001233); }
        }
    </style>
</head>
<body>
<div class="app-container">
    <div class="header">
        <div class="d-flex align-items-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($empresa->nombre) }}&background=random" class="rounded-circle me-2" width="40">
            <div>
                <h6 class="mb-0 fw-bold">{{ mb_strtoupper($empresa->nombre) }}</h6>
                <small class="text-white-50">Saldo: $0.00</small>
            </div>
        </div>
    </div>
    
    <div class="container p-3">
        @if($sorteoActivo)
            <a href="{{ route('tienda.show', $sorteoActivo->jugada_id) }}" class="game-card">
                <span class="badge-live">EN VIVO</span>
                <div class="mt-4 mb-2">
                    <i class="bi bi-play-circle text-white fs-1"></i>
                </div>
                <h3 class="fs-2">Bingo</h3>
                <p class="text-white-50 small mb-0">Sala Activa - Participa ahora</p>
            </a>
        @else
            <div class="game-card" style="opacity: 0.5;">
                <div class="mt-4 mb-2">
                    <i class="bi bi-calendar-x text-white fs-1"></i>
                </div>
                <h3 class="fs-2">Bingo</h3>
                <p class="text-white-50 small mb-0">No hay sorteos en vivo</p>
            </div>
        @endif

        <div class="row g-3">
            <div class="col-6">
                <a href="#" class="game-card h-100 text-center">
                    <i class="bi bi-suit-spade fs-2 mb-2 d-block text-white-50"></i>
                    <h6>Blackjack</h6>
                </a>
            </div>
            <div class="col-6">
                <a href="#" class="game-card h-100 text-center">
                    <i class="bi bi-circle-fill fs-2 mb-2 d-block text-white-50"></i>
                    <h6>Ruleta</h6>
                </a>
            </div>
            <div class="col-12">
                <a href="#" class="game-card text-center">
                    <i class="bi bi-trophy fs-2 mb-2 d-block text-white-50"></i>
                    <h6>Apuestas Deportivas</h6>
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
