<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mockups Lobby de Casino</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { background-color: #0b0c0f; color: #fff; font-family: 'Outfit', sans-serif; padding: 2rem; }
        
        /* Contenedor simulando un celular */
        .phone-frame {
            width: 375px;
            height: 812px;
            border-radius: 40px;
            border: 12px solid #222;
            overflow: hidden;
            position: relative;
            margin: 0 auto;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
        }

        /* OPCIÓN 1: NEÓN CYBERPUNK */
        .theme-neon { background: #07070a; }
        .theme-neon .header { padding: 20px; background: linear-gradient(180deg, rgba(0,255,136,0.1) 0%, transparent 100%); }
        .theme-neon .game-card { background: rgba(20,20,25,0.8); border: 1px solid rgba(0,255,136,0.3); border-radius: 20px; padding: 20px; text-decoration: none; display: block; margin-bottom: 15px; position: relative; overflow: hidden; }
        .theme-neon .game-card.featured { border-color: #00ff88; box-shadow: 0 0 20px rgba(0,255,136,0.2); background: linear-gradient(45deg, #072a1e, #0a1118); }
        .theme-neon .game-card h3 { color: #fff; font-weight: 900; margin: 0; }
        .theme-neon .game-card .badge-live { background: #ff0055; color: #fff; padding: 4px 10px; border-radius: 50px; font-size: 0.7rem; font-weight: bold; position: absolute; top: 15px; right: 15px; box-shadow: 0 0 10px #ff0055; animation: pulse 2s infinite; }

        /* OPCIÓN 2: PREMIUM ELEGANCE (ORO/NEGRO) */
        .theme-gold { background: #0a0a0a; }
        .theme-gold .header { padding: 20px; text-align: center; border-bottom: 1px solid rgba(212, 175, 55, 0.2); }
        .theme-gold .game-card { background: #111; border: 1px solid #333; border-radius: 12px; padding: 20px; text-decoration: none; display: flex; align-items: center; margin-bottom: 15px; }
        .theme-gold .game-card.featured { border-color: #d4af37; background: linear-gradient(135deg, #1a1811, #0a0a0a); }
        .theme-gold .game-card h3 { color: #d4af37; font-weight: 700; margin: 0; letter-spacing: 1px; text-transform: uppercase;}
        .theme-gold .game-card .badge-live { background: #d4af37; color: #000; padding: 4px 10px; border-radius: 4px; font-size: 0.7rem; font-weight: bold; margin-left: auto; }

        /* OPCIÓN 3: GLASSMORPHISM MODERNO */
        .theme-glass { background: linear-gradient(135deg, #1e003b, #001233); position: relative; }
        .theme-glass::before { content: ''; position: absolute; top: -50px; left: -50px; width: 200px; height: 200px; background: #ff007f; filter: blur(100px); border-radius: 50%; opacity: 0.5; }
        .theme-glass::after { content: ''; position: absolute; bottom: -50px; right: -50px; width: 200px; height: 200px; background: #00d4ff; filter: blur(100px); border-radius: 50%; opacity: 0.5; }
        .theme-glass .header { padding: 20px; position: relative; z-index: 2; }
        .theme-glass .game-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 24px; padding: 20px; text-decoration: none; display: block; margin-bottom: 15px; position: relative; z-index: 2; }
        .theme-glass .game-card h3 { color: #fff; font-weight: 700; margin: 0; }
        .theme-glass .badge-live { background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4); color: #fff; padding: 4px 10px; border-radius: 50px; font-size: 0.7rem; font-weight: bold; float: right; }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); box-shadow: 0 0 15px #ff0055; }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container text-center mb-5">
        <h1 class="fw-bold">Propuestas Visuales: Lobby del Jugador</h1>
        <p class="text-white-50">He preparado 3 líneas estéticas para la pantalla inicial (el HUB del Casino) donde el jugador elige la sala.</p>
    </div>

    <div class="row g-5 justify-content-center">
        <!-- OPCION 1 -->
        <div class="col-auto">
            <h4 class="text-center mb-3 text-info">Opción 1: Neón Infinity</h4>
            <p class="text-center text-white-50 small mb-4">Sigue la línea actual oscura/neón.</p>
            <div class="phone-frame theme-neon">
                <div class="header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-4 fw-bold">INFINITY</span>
                        <div class="bg-dark px-3 py-1 rounded-pill border border-secondary text-info"><i class="bi bi-wallet2"></i> $4.500</div>
                    </div>
                </div>
                <div class="p-3" style="overflow-y: auto;">
                    <h6 class="text-white-50 mb-3 text-uppercase" style="letter-spacing: 2px; font-size: 11px;">En Vivo Ahora</h6>
                    
                    <a href="#" class="game-card featured">
                        <span class="badge-live"><i class="bi bi-broadcast"></i> EN VIVO</span>
                        <i class="bi bi-play-circle text-info fs-1 mb-2 d-block"></i>
                        <h3>BINGO TOTAL</h3>
                        <p class="text-white-50 small mb-0 mt-1">Sala Principal • 450 Jugadores</p>
                        <div class="mt-3 bg-dark rounded p-2 text-center border border-success">
                            <small class="text-success text-uppercase" style="font-size: 10px;">Pozo Acumulado</small>
                            <div class="fw-bold fs-5">$1.200.000</div>
                        </div>
                    </a>

                    <h6 class="text-white-50 mt-4 mb-3 text-uppercase" style="letter-spacing: 2px; font-size: 11px;">Juegos Clásicos</h6>
                    
                    <a href="#" class="game-card">
                        <i class="bi bi-suit-spade fs-2 mb-2 d-block" style="color: #666;"></i>
                        <h3 class="text-light fs-5">Blackjack</h3>
                        <p class="text-white-50 small mb-0">Multijugador</p>
                    </a>
                    
                    <a href="#" class="game-card">
                        <i class="bi bi-suit-diamond text-danger fs-2 mb-2 d-block"></i>
                        <h3 class="text-light fs-5">Póker Texas</h3>
                        <p class="text-white-50 small mb-0">Mesas Cash</p>
                    </a>

                    <a href="#" class="game-card">
                        <i class="bi bi-trophy text-warning fs-2 mb-2 d-block"></i>
                        <h3 class="text-light fs-5">Apuestas Deportivas</h3>
                        <p class="text-white-50 small mb-0">Fútbol, NBA, F1 y más</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- OPCION 2 -->
        <div class="col-auto">
            <h4 class="text-center mb-3" style="color: #d4af37;">Opción 2: Premium Casino</h4>
            <p class="text-center text-white-50 small mb-4">Elegante, dorado, muy casino físico VIP.</p>
            <div class="phone-frame theme-gold">
                <div class="header">
                    <h5 class="mb-0" style="color: #d4af37; font-family: serif; font-style: italic;">Infinity Casino</h5>
                </div>
                <div class="p-3" style="overflow-y: auto;">
                    <div class="bg-dark p-3 rounded text-center mb-4 border border-secondary">
                        <small class="text-white-50">Balance Disponible</small>
                        <h3 class="text-white mb-0">$4,500.00</h3>
                    </div>

                    <a href="#" class="game-card featured" style="flex-direction: column; text-align: center;">
                        <span class="badge-live w-100 text-center mb-3">SALA EN VIVO</span>
                        <h3 class="fs-2 mb-2">BINGO</h3>
                        <p class="text-white-50 small mb-3">Sorteo Activo - Sala Principal</p>
                        <button class="btn btn-outline-warning btn-sm px-4 rounded-pill">ENTRAR A JUGAR</button>
                    </a>

                    <a href="#" class="game-card">
                        <i class="bi bi-suit-spade fs-3 me-3 text-white-50"></i>
                        <div>
                            <h3 class="fs-6">Blackjack</h3>
                            <small class="text-white-50">Mesa Privada</small>
                        </div>
                    </a>
                    
                    <a href="#" class="game-card">
                        <i class="bi bi-suit-club fs-3 me-3 text-white-50"></i>
                        <div>
                            <h3 class="fs-6">Póker</h3>
                            <small class="text-white-50">Texas Hold'em</small>
                        </div>
                    </a>

                    <a href="#" class="game-card">
                        <i class="bi bi-trophy fs-3 me-3 text-white-50"></i>
                        <div>
                            <h3 class="fs-6">Sportsbook</h3>
                            <small class="text-white-50">Apuestas Deportivas</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- OPCION 3 -->
        <div class="col-auto">
            <h4 class="text-center mb-3 text-light">Opción 3: Glassmorphism</h4>
            <p class="text-center text-white-50 small mb-4">Moderno, desenfocado, estilo app de Apple.</p>
            <div class="phone-frame theme-glass">
                <div class="header">
                    <div class="d-flex align-items-center">
                        <img src="https://ui-avatars.com/api/?name=Jugador&background=random" class="rounded-circle me-2" width="40">
                        <div>
                            <h6 class="mb-0 fw-bold">Hola, Jugador</h6>
                            <small class="text-white-50">Saldo: $4.500</small>
                        </div>
                    </div>
                </div>
                <div class="p-3" style="overflow-y: auto;">
                    
                    <a href="#" class="game-card">
                        <span class="badge-live">EN VIVO</span>
                        <div class="mt-4 mb-2">
                            <i class="bi bi-play-circle text-white fs-1"></i>
                        </div>
                        <h3 class="fs-2">Bingo en Vivo</h3>
                        <p class="text-white-50 small mb-0">Sala Activa - Participa ahora</p>
                    </a>

                    <div class="row g-3">
                        <div class="col-6">
                            <a href="#" class="game-card h-100 text-center">
                                <i class="bi bi-suit-spade fs-2 mb-2 d-block"></i>
                                <h6>Blackjack</h6>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="#" class="game-card h-100 text-center">
                                <i class="bi bi-dice-5 fs-2 mb-2 d-block"></i>
                                <h6>Dados</h6>
                            </a>
                        </div>
                        <div class="col-12">
                            <a href="#" class="game-card text-center">
                                <i class="bi bi-trophy fs-2 mb-2 d-block"></i>
                                <h6>Apuestas Deportivas</h6>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
