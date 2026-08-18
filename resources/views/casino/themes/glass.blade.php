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
        .app-container { width: 100%; min-height: 100vh; position: relative; z-index: 1;}
        @media (min-width: 768px) {
            body { background: #000; }
            .app-container { background: linear-gradient(135deg, #1e003b, #001233); }
            .game-card { height: 100%; }
        }
    </style>
</head>
<body>
<div class="app-container">
    <div class="header d-flex justify-content-between align-items-center px-4 py-3 border-bottom border-secondary" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(10px);">
        <div class="d-flex align-items-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($empresa->nombre) }}&background=random" class="rounded-circle me-3" width="45">
            <div>
                <h6 class="mb-0 fw-bold fs-5">{{ mb_strtoupper($empresa->nombre) }}</h6>
            </div>
        </div>
        
        <div class="d-flex gap-3 align-items-center">
            <!-- Selector de Moneda y Lenguaje -->
            <select class="form-select form-select-sm bg-transparent text-white border-light" style="width: auto;">
                <option value="es_ARS">🇪🇸 ARS ($)</option>
                <option value="en_USD">🇺🇸 USD ($)</option>
                <option value="pt_BRL">🇧🇷 BRL (R$)</option>
            </select>
            
            @if($participanteLogueado)
            <div class="d-flex flex-column text-end">
                <span class="text-white-50" style="font-size: 0.7rem; margin-bottom: -5px;">{{ $participanteLogueado->nombre }}</span>
                <a href="{{ route('cajero.show') }}?t={{ $participanteLogueado->telefono }}" class="px-3 py-1 rounded text-white fw-bold mt-1 text-decoration-none d-flex align-items-center gap-1" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);" title="Cargar Fichas">
                    <i class="bi bi-gem"></i> {{ number_format($participanteLogueado->saldo_fichas, 0) }}
                    <span class="bg-info text-dark rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 18px; height: 18px; font-size: 0.7rem;"><i class="bi bi-plus-lg"></i></span>
                </a>
            </div>
            @else
            <a href="{{ route('cajero.show') }}" class="px-3 py-1 rounded text-info text-decoration-none d-flex align-items-center gap-1" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
                <i class="bi bi-wallet2"></i> Cargar Fichas
            </a>
            @endif
        </div>
    </div>
    
    <div class="container-fluid px-4 py-4">
        
        <div class="row g-4 mb-5">
            <div class="col-12 col-lg-8">
                @if($sorteoActivo)
                    <a href="{{ route('tienda.show', $sorteoActivo->jugada_id) }}" class="game-card" style="padding: 40px; background: rgba(0, 212, 255, 0.1); border-color: rgba(0, 212, 255, 0.3);">
                        <span class="badge-live mb-4 d-inline-block position-static float-none">EN VIVO</span>
                        <div class="d-flex align-items-center h-100 mt-2">
                            <i class="bi bi-play-circle text-info me-4" style="font-size: 5rem;"></i>
                            <div>
                                <h3 class="fs-1 text-info">BINGO TOTAL</h3>
                                <p class="text-white-50 mb-0 mt-2 fs-5">Participa en el sorteo ahora</p>
                            </div>
                        </div>
                    </a>
                @else
                    <div class="game-card" style="opacity: 0.5; padding: 40px;">
                        <div class="d-flex align-items-center h-100">
                            <i class="bi bi-calendar-x text-secondary me-4" style="font-size: 5rem;"></i>
                            <div>
                                <h3 class="fs-1 text-secondary">BINGO</h3>
                                <p class="text-white-50 mb-0 mt-2 fs-5">No hay sorteos en vivo</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="col-12 col-lg-4">
                <a href="#" class="game-card p-4">
                    <i class="bi bi-trophy text-warning mb-3 d-block" style="font-size: 3rem;"></i>
                    <h3 class="fs-3 mb-2">Deportes</h3>
                    <p class="text-white-50 mb-0">Fútbol, Tenis, NBA</p>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-6 col-md-4">
                <a href="/demo/mockups/blackjack" class="game-card text-center p-4">
                    <i class="bi bi-suit-spade text-white-50 mb-3 d-block" style="font-size: 3rem;"></i>
                    <h3 class="fs-4">Blackjack</h3>
                </a>
            </div>
            <div class="col-6 col-md-4">
                <a href="/demo/mockups/ruleta" class="game-card text-center p-4">
                    <i class="bi bi-circle-fill text-danger mb-3 d-block" style="font-size: 3rem;"></i>
                    <h3 class="fs-4">Ruleta</h3>
                </a>
            </div>
            <div class="col-6 col-md-4">
                <a href="{{ route('casino.megasorteo.index') }}" class="game-card text-center p-4">
                    <i class="bi bi-ticket-perforated text-warning mb-3 d-block" style="font-size: 3rem;"></i>
                    <h3 class="fs-4">Mega Sorteo</h3>
                    <p class="text-white-50 small mb-0">Pozo Acumulado</p>
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
