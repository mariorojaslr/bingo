<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $empresa->nombre }} | Premium Casino</title>
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ route('casino.manifest', $empresa->subdominio) }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primario: {{ $empresa->color_primario ?? '#d4af37' }};
        }
        body { background-color: #0a0a0a; color: #fff; font-family: 'Outfit', sans-serif; min-height: 100vh; }
        .header { padding: 20px; text-align: center; border-bottom: 1px solid color-mix(in srgb, var(--color-primario) 20%, transparent); }
        .game-card { background: #111; border: 1px solid #333; border-radius: 12px; padding: 20px; text-decoration: none; display: flex; align-items: center; margin-bottom: 15px; color: #fff; }
        .game-card.featured { border-color: var(--color-primario); background: linear-gradient(135deg, #1a1811, #0a0a0a); flex-direction: column; text-align: center; }
        .game-card h3 { color: var(--color-primario); font-weight: 700; margin: 0; letter-spacing: 1px; text-transform: uppercase;}
        .badge-live { background: var(--color-primario); color: #000; padding: 4px 10px; border-radius: 4px; font-size: 0.7rem; font-weight: bold; margin-left: auto; }
        .badge-featured-live { background: var(--color-primario); color: #000; padding: 4px 10px; border-radius: 4px; font-size: 0.7rem; font-weight: bold; width: 100%; text-align: center; margin-bottom: 15px; }
        
        /* Pilar 1: Diseño Adaptativo Inteligente */
        .app-container { width: 100%; min-height: 100vh; background-color: #0a0a0a; position: relative; }
        @media (min-width: 768px) {
            body { background-image: radial-gradient(circle at center, color-mix(in srgb, var(--color-primario) 10%, transparent) 0%, transparent 70%); }
            .game-card { height: 100%; }
        }
    </style>
</head>
<body>
<div class="app-container">
    <div class="header d-flex justify-content-between align-items-center px-4 pt-4">
        <h5 class="mb-0" style="color: var(--color-primario); font-family: serif; font-style: italic; font-size: 1.8rem;">{{ mb_strtoupper($empresa->nombre) }}</h5>
        
        <div class="d-flex gap-3 align-items-center">
            <!-- Selector de Moneda y Lenguaje -->
            <select class="form-select form-select-sm bg-dark text-white" style="width: auto; border: 1px solid var(--color-primario);">
                <option value="es_ARS">🇪🇸 ARS ($)</option>
                <option value="en_USD">🇺🇸 USD ($)</option>
                <option value="pt_BRL">🇧🇷 BRL (R$)</option>
            </select>
            
            <div class="px-3 py-1 rounded text-dark fw-bold" style="background: var(--color-primario);">
                $0.00
            </div>
        </div>
    </div>
    
    <div class="container-fluid px-4 py-4">
        
        <div class="row g-4 mb-5">
            <div class="col-12 col-lg-8">
                @if($sorteoActivo)
                    <a href="{{ route('tienda.show', $sorteoActivo->jugada_id) }}" class="game-card featured" style="padding: 40px;">
                        <div class="badge-featured-live w-auto d-inline-block px-3 mb-4"><i class="bi bi-broadcast"></i> SALA EN VIVO</div>
                        <div class="d-flex align-items-center h-100">
                            <i class="bi bi-suit-diamond-fill d-block me-4" style="color: var(--color-primario); font-size: 4rem;"></i>
                            <div>
                                <h3 class="fs-1">CLUB BINGO VIP</h3>
                                <p class="text-white-50 mb-0 mt-1">Tu asiento está reservado. Entra a jugar.</p>
                            </div>
                        </div>
                    </a>
                @else
                    <div class="game-card opacity-50 p-5 d-flex align-items-center">
                        <i class="bi bi-suit-diamond d-block text-secondary me-4" style="font-size: 4rem;"></i>
                        <div>
                            <h3 class="fs-1 text-secondary">CLUB BINGO</h3>
                            <p class="text-white-50 mb-0 mt-1">Sala cerrada por el momento.</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-12 col-lg-4">
                <a href="#" class="game-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <i class="bi bi-trophy-fill fs-2" style="color: var(--color-primario);"></i>
                        <span class="text-white-50"><i class="bi bi-arrow-right"></i></span>
                    </div>
                    <h3 class="fs-4">DEPORTES</h3>
                    <p class="text-white-50 small mb-0 mt-1">Apuestas Premium</p>
                </a>
            </div>
        </div>

        <h6 class="text-white-50 mt-4 mb-4 text-uppercase" style="letter-spacing: 2px; font-size: 11px;">Mesa de Juegos</h6>
        
        <div class="row g-4">
            <div class="col-6 col-md-4">
                <a href="#" class="game-card p-4 text-center">
                    <i class="bi bi-suit-spade-fill fs-1 mb-3 d-block" style="color: var(--color-primario);"></i>
                    <h3 class="fs-5">BLACKJACK</h3>
                </a>
            </div>
            
            <div class="col-6 col-md-4">
                <a href="#" class="game-card p-4 text-center">
                    <i class="bi bi-circle-fill fs-1 mb-3 d-block text-danger"></i>
                    <h3 class="fs-5">RULETA</h3>
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
