<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $empresa->nombre }} | Premium Casino</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h5 class="mb-0" style="color: var(--color-primario); font-family: serif; font-style: italic;">{{ mb_strtoupper($empresa->nombre) }}</h5>
    </div>
    
    <div class="container p-3">
        <div class="bg-dark p-3 rounded text-center mb-4 border border-secondary">
            <small class="text-white-50">Balance Disponible</small>
            <h3 class="text-white mb-0">$0.00</h3>
        </div>

        @if($sorteoActivo)
            <a href="{{ route('tienda.show', $sorteoActivo->jugada_id) }}" class="game-card featured">
                <span class="badge-featured-live">SALA EN VIVO</span>
                <h3 class="fs-2 mb-2">BINGO</h3>
                <p class="text-white-50 small mb-3">Sorteo Activo - Entra ahora</p>
                <button class="btn btn-outline-warning btn-sm px-4 rounded-pill" style="border-color: var(--color-primario); color: var(--color-primario);">ENTRAR A JUGAR</button>
            </a>
        @else
            <div class="game-card featured" style="opacity: 0.5;">
                <h3 class="fs-2 mb-2" style="color: #666;">BINGO</h3>
                <p class="text-white-50 small mb-3">Sorteo Cerrado</p>
            </div>
        @endif

        <a href="#" class="game-card">
            <i class="bi bi-suit-spade fs-3 me-3 text-white-50"></i>
            <div>
                <h3 class="fs-6" style="color: #fff;">Blackjack</h3>
                <small class="text-white-50">Mesa Privada</small>
            </div>
        </a>
        
        <a href="#" class="game-card">
            <i class="bi bi-circle-fill fs-3 me-3 text-white-50"></i>
            <div>
                <h3 class="fs-6" style="color: #fff;">Ruleta</h3>
                <small class="text-white-50">Europea VIP</small>
            </div>
        </a>

        <a href="#" class="game-card">
            <i class="bi bi-trophy fs-3 me-3 text-white-50"></i>
            <div>
                <h3 class="fs-6" style="color: #fff;">Sportsbook</h3>
                <small class="text-white-50">Apuestas Deportivas</small>
            </div>
        </a>
    </div>
</body>
</html>
