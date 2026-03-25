<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Infinity Bingo | Lobby Principal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-dark: #000000;
            --bg-panel: rgba(15, 15, 20, 0.8);
            --border-glass: rgba(255, 255, 255, 0.08);
            --neon-green: #00FF88;
            --neon-blue: #00A8FF;
            --neon-red: #ff4757;
            --neon-gold: #D4AF37;
            --neon-purple: #9b59b6;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: #fff;
            min-height: 100vh;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(0, 255, 136, 0.05), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(0, 168, 255, 0.05), transparent 25%);
            margin: 0; padding: 0;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, .brand-font { font-family: 'Outfit', sans-serif; }

        .navbar-top {
            background: rgba(0,0,0,0.9);
            border-bottom: 1px solid var(--border-glass);
            padding: 1rem 3rem;
            display: flex; justify-content: space-between; align-items: center;
            backdrop-filter: blur(15px);
            position: sticky; top: 0; z-index: 1000;
        }

        .container-rooms { max-width: 1300px; margin: 3rem auto; padding: 0 1.5rem; }

        .title-section {
            display: flex; align-items: center; gap: 1rem; margin-bottom: 3rem;
        }
        .title-section h1 { font-weight: 900; letter-spacing: 1px; margin: 0; font-size: 2.5rem; }

        .room-card {
            background: var(--bg-panel);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .room-card::before { content: ''; position: absolute; top:0; left:0; width:5px; height:100%; background: var(--border-glass); transition: 0.4s; }
        .room-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.6); }

        .room-card.plata::before { background: #b0c4de; box-shadow: 0 0 15px #b0c4de; }
        .room-card.oro::before { background: var(--neon-gold); box-shadow: 0 0 15px var(--neon-gold); }
        .room-card.diamante::before { background: var(--neon-blue); box-shadow: 0 0 15px var(--neon-blue); }

        .brand-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.05); padding: 5px 12px;
            border-radius: 20px; font-size: 0.75rem; font-weight: 600;
            color: #ccc; border: 1px solid rgba(255,255,255,0.1);
        }

        .room-title { font-size: 1.6rem; font-weight: 800; margin: 15px 0 5px 0; }
        .room-time { color: var(--neon-green); font-weight: 600; font-size: 0.9rem; letter-spacing: 1px; }

        .capacity-bar { height: 8px; background: rgba(255,255,255,0.1); border-radius: 10px; margin: 1.5rem 0 0.5rem 0; overflow: hidden; }
        .capacity-fill { height: 100%; background: var(--neon-green); border-radius: 10px; box-shadow: 0 0 10px var(--neon-green); }
        .capacity-fill.high { background: var(--neon-red); box-shadow: 0 0 10px var(--neon-red); }

        .price-tag { font-family: 'Outfit'; font-size: 2rem; font-weight: 900; }
        .price-tag small { font-size: 1rem; color: #888; font-weight: 500; }

        .btn-enter {
            width: 100%; border-radius: 12px; padding: 16px;
            font-family: 'Outfit'; font-weight: 800; letter-spacing: 1px;
            text-transform: uppercase; border: none; transition: 0.3s;
            margin-top: 20px;
        }
        
        .btn-enter.available { background: var(--neon-blue); color: #000; box-shadow: 0 10px 20px rgba(0, 168, 255, 0.2); }
        .btn-enter.available:hover { background: #fff; box-shadow: 0 10px 30px rgba(0, 168, 255, 0.4); transform: translateY(-2px); }

        .btn-enter.closed { background: #222; color: #666; cursor: not-allowed; border: 1px solid #333; }

        .pulse-live { animation: pulseWarning 2s infinite; }
        @keyframes pulseWarning { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
    </style>
</head>
<body>

<header class="navbar-top">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-box" style="color: var(--neon-green); font-size: 1.5rem;"></i>
        <h4 class="mb-0 fw-bold brand-font" style="letter-spacing: 1px; color: #fff;">INFINITY LOBBY</h4>
    </div>
    <div class="d-flex align-items-center gap-4">
        <span class="text-white-50 small d-none d-md-block"><i class="bi bi-wallet2 me-2"></i> Mi Saldo: <strong>$0.00</strong></span>
        <button class="btn btn-sm btn-outline-light rounded-pill px-3"><i class="bi bi-person me-1"></i> Mi Cuenta</button>
    </div>
</header>

<div class="container-rooms">
    <div class="title-section">
        <i class="bi bi-controller fs-1" style="color: var(--neon-blue)"></i>
        <div>
            <h1 class="text-white">Salas Publicadas</h1>
            <p class="text-white-50 mb-0">Compra tu cartón y asegura tu asiento antes de que cierre la sala.</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($salasDecoradas as $sala)
            @php
                $claseTema = 'plata';
                if ($sala->precio_carton_virtual >= 5000) $claseTema = 'oro';
                if ($sala->precio_carton_virtual >= 10000) $claseTema = 'diamante';
            @endphp
            
            <div class="col-md-6 col-lg-4">
                <div class="room-card {{ $claseTema }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="brand-pill">
                            <i class="bi bi-building"></i> {{ mb_strtoupper($sala->organizador->nombre_fantasia ?? 'EMPRESA INDEPENDIENTE') }}
                        </div>
                        @if($sala->estado_sala === 'en_curso')
                            <span class="badge bg-danger rounded-pill pulse-live px-3">EN JUEGO</span>
                        @else
                            <span class="badge" style="background: rgba(255,255,255,0.1); color: #aaa;">{{ $sala->fecha_evento }}</span>
                        @endif
                    </div>
                    
                    <h3 class="room-title">{{ mb_strtoupper($sala->nombre_jugada) }}</h3>
                    <div class="room-time"><i class="bi bi-clock-history me-1"></i> INICIA: {{ $sala->hora_evento ?? '15:00 HS' }}</div>

                    <div class="d-flex justify-content-between align-items-end mt-4">
                        <div>
                            <span class="text-muted small text-uppercase" style="letter-spacing: 1px;">Precio del Cartón</span>
                            <div class="price-tag">${{ number_format($sala->precio_carton_virtual, 0, ',', '.') }}<small> ARS</small></div>
                        </div>
                        <div class="text-end">
                            <span class="text-white fw-bold">{{ $sala->ocupacion_actual }}</span>
                            <span class="text-muted small">/ {{ $sala->capacidad_maxima }}</span>
                        </div>
                    </div>

                    <div class="capacity-bar">
                        <div class="capacity-fill {{ $sala->porcentaje_lleno > 80 ? 'high' : '' }}" style="width: {{ $sala->porcentaje_lleno }}%;"></div>
                    </div>
                    <div class="text-end text-white-50" style="font-size: 0.75rem;">Capacidad: {{ number_format($sala->porcentaje_lleno, 1) }}%</div>

                    @if($sala->estado_sala === 'en_curso')
                        <button class="btn-enter closed" disabled><i class="bi bi-door-closed me-2"></i> SALA CERRADA (JUGANDO)</button>
                    @elseif($sala->estado_sala === 'por_cerrar')
                        <button class="btn-enter available" style="background: var(--neon-gold);"><i class="bi bi-ticket-perforated me-2"></i> ¡ÚLTIMOS CUPOS!</button>
                    @else
                        <button class="btn-enter available"><i class="bi bi-cart me-2"></i> COMPRAR Y ENTRAR</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-stars" style="font-size: 4rem; color: rgba(255,255,255,0.1);"></i>
                <h3 class="text-white-50 mt-3">No hay salas de juego publicadas en este momento.</h3>
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
