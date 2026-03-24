<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinity Bingo | La Nueva Era del Entretenimiento</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@200;300;400;600;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 (Base) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bg-deep: #020202;
            --neon-green: #00FF88;
            --neon-green-glow: rgba(0, 255, 136, 0.4);
            --gold-premium: #D4AF37;
            --gold-glow: rgba(212, 175, 55, 0.3);
            --glass-bg: rgba(20, 20, 20, 0.6);
            --glass-border: rgba(255, 255, 255, 0.08);
            --text-main: #F4F4F5;
            --text-muted: #A1A1AA;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-deep);
            color: var(--text-main);
            overflow-x: hidden;
            position: relative;
        }

        /* Animated Ambient Background */
        .ambient-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            background: radial-gradient(circle at 50% 10%, rgba(0, 255, 136, 0.08) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(212, 175, 55, 0.05) 0%, transparent 40%);
            /* noise overlay */
            background-image: url('data:image/svg+xml,%3Csvg viewBox=\"0 0 200 200\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cfilter id=\"noiseFilter\"%3E%3CfeTurbulence type=\"fractalNoise\" baseFrequency=\"0.8\" numOctaves=\"3\" stitchTiles=\"stitch\"/%3E%3C/filter%3E%3Crect width=\"100%25\" height=\"100%25\" filter=\"url(%23noiseFilter)\" opacity=\"0.02\"/%3E%3C/svg%3E');
        }

        /* Floating Orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            animation: float 20s infinite ease-in-out alternate;
        }
        .orb-1 { width: 300px; height: 300px; background: var(--neon-green); top: -100px; left: -100px; }
        .orb-2 { width: 400px; height: 400px; background: rgba(0, 255, 136, 0.3); bottom: -150px; right: -100px; animation-delay: -5s; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 30px) scale(1.2); }
        }

        /* Navbar Custom */
        .navbar-premium {
            background: rgba(2, 2, 2, 0.8) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1.5rem 0;
        }

        /* SVG Logo - Custom crafted */
        .logo-svg {
            width: 45px;
            height: 45px;
            filter: drop-shadow(0 0 10px var(--neon-green-glow));
            transition: all 0.3s ease;
        }
        .logo-svg:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 0 20px var(--neon-green-glow));
        }

        /* Button Premium */
        .btn-neon {
            background: transparent;
            color: var(--neon-green);
            border: 1px solid var(--neon-green);
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .btn-neon::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 0%; height: 100%;
            background: var(--neon-green);
            z-index: -1;
            transition: all 0.4s ease;
        }
        .btn-neon:hover {
            color: var(--bg-deep);
            box-shadow: 0 0 25px var(--neon-green-glow);
        }
        .btn-neon:hover::before {
            width: 100%;
        }

        .btn-gold {
            background: linear-gradient(135deg, #D4AF37 0%, #aa8b26 100%);
            color: var(--bg-deep);
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            box-shadow: 0 10px 30px var(--gold-glow);
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px var(--gold-glow);
            color: #000;
        }

        /* Hero Typography */
        .hero-title {
            font-size: 4.5rem;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -1.5px;
            background: linear-gradient(to right, #FFF, #A1A1AA);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }
        
        .hero-subtitle {
            font-size: 1.4rem;
            font-weight: 300;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        /* Glass Cards */
        .glass-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 40px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        .glass-card::after {
            content: '';
            position: absolute;
            top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.5s;
            pointer-events: none;
        }
        .glass-card:hover {
            transform: translateY(-10px);
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }
        .glass-card:hover::after {
            opacity: 1;
        }

        /* Feature Icons */
        .feature-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: rgba(0, 255, 136, 0.05);
            border: 1px solid rgba(0, 255, 136, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--neon-green);
            margin-bottom: 25px;
        }

        /* Pricing Tags */
        .price-tag {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--text-main);
            margin: 20px 0;
            display: flex;
            align-items: center;
        }
        .price-currency {
            font-size: 1.5rem;
            color: var(--neon-green);
            margin-right: 5px;
            align-self: flex-start;
            margin-top: 10px;
        }
        .price-period {
            font-size: 1rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Upcoming Games Card Image replacement */
        .game-poster {
            width: 100%;
            height: 200px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(20,20,20,1) 0%, rgba(40,40,40,1) 100%);
            border: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .game-poster::before {
            content: '⚽';
            font-size: 4rem;
            opacity: 0.1;
            position: absolute;
            transform: rotate(-15deg) scale(1.5);
        }
        
        .live-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 0, 85, 0.2);
            color: #FF0055;
            border: 1px solid #FF0055;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 1px;
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(255, 0, 85, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(255, 0, 85, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 0, 85, 0); }
        }

    </style>
</head>
<body>

    <div class="ambient-background"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-premium fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3" href="#">
                <!-- Logo Exclusivo SVG (Creado a mano) -->
                <svg class="logo-svg" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Base Esfera -->
                    <circle cx="50" cy="50" r="45" stroke="#00FF88" stroke-width="2" stroke-dasharray="10 5" fill="rgba(0,255,136,0.05)"/>
                    <!-- Anillo Orbital -->
                    <circle cx="50" cy="50" r="30" stroke="#D4AF37" stroke-width="4" stroke-linecap="round" fill="none"/>
                    <!-- Núcleo Neón -->
                    <circle cx="50" cy="50" r="15" fill="#00FF88" filter="blur(2px)"/>
                    <circle cx="50" cy="50" r="10" fill="#FFFFFF"/>
                </svg>
                <span style="font-weight: 800; font-size: 1.5rem; letter-spacing: 1px;">INFINITY<span style="color: var(--neon-green)">BINGO</span></span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-toggle="target="#navbarNav">
                <i class="bi bi-list fs-1 text-white"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto gap-4">
                    <li class="nav-item"><a class="nav-link text-white" href="#plataforma">La Plataforma</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#juegos">Cartelera en Vivo</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#planes">Planes para Organizadores</a></li>
                </ul>
                <div class="d-flex gap-3">
                    <a href="/login" class="nav-link text-white d-flex align-items-center">Ingresar</a>
                    <a href="#contacto" class="btn btn-neon">Organizar Evento</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="min-vh-100 d-flex align-items-center pt-5 position-relative">
        <div class="container text-center position-relative" style="z-index: 2;">
            <div class="badge border border-secondary text-secondary rounded-pill px-3 py-2 mb-4 mb-4" style="background: rgba(255,255,255,0.03);">
                <i class="bi bi-broadcast me-2" style="color: var(--neon-green)"></i> TECNOLOGÍA LIVE STREAMING DE ULTRA-BAJA LATENCIA
            </div>
            
            <h1 class="hero-title">
                La Evolución Definitiva <br>del <span style="color: var(--neon-green);">Entretenimiento.</span>
            </h1>
            
            <p class="hero-subtitle mb-5">
                Organiza sorteos masivos, transmite en vivo con cero delay y gestiona miles de cartones en simultáneo. Una plataforma SaaS diseñada para conectar a organizadores y jugadores en una experiencia inmersiva.
            </p>
            
            <div class="d-flex justify-content-center gap-4">
                <button class="btn btn-gold">VER PLANES DE SUSCRIPCIÓN</button>
                <button class="btn btn-neon"><i class="bi bi-play-circle me-2"></i> EXPLORAR DEMO</button>
            </div>
            
            <!-- Dashboard Mockup Preview -->
            <div class="mt-5 pt-4 position-relative">
                <div style="background: linear-gradient(180deg, transparent, var(--bg-deep) 80%); width: 100%; height: 100%; position: absolute; z-index: 1;"></div>
                <div class="glass-card p-2 mx-auto" style="max-width: 900px; transform: perspective(1000px) rotateX(10deg); box-shadow: 0 -20px 60px rgba(0,255,136,0.1);">
                    <div class="w-100 rounded-3" style="height: 400px; background: #0a0a0a; border: 1px solid #222; overflow: hidden; position: relative;">
                        <!-- Tablero Falso -->
                        <div class="d-flex h-100">
                            <div class="w-25 border-end border-dark p-4 text-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:120px; height:120px; border: 4px solid var(--neon-green); box-shadow: 0 0 30px var(--neon-green-glow); font-size: 3rem; font-weight: 800; color: #fff;">
                                    23
                                </div>
                                <div class="text-muted small text-uppercase tracking-widest">Última Bolilla</div>
                            </div>
                            <div class="w-75 p-4 position-relative d-flex align-items-center justify-content-center">
                                <div class="position-absolute top-0 end-0 m-3 px-3 py-1 rounded border" style="color: #FF0055; border-color: rgba(255,0,85,0.3) !important; background: rgba(255,0,85,0.1);"><i class="bi bi-circle-fill me-2" style="font-size:8px;"></i>LIVE</div>
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100%25' height='100%25'%3E%3Crect width='100%25' height='100%25' fill='%23111'/%3E%3Ccircle cx='50%25' cy='50%25' r='100' fill='rgba(0,255,136,0.05)'/%3E%3C/svg%3E" alt="stream placeholder" class="w-100 h-100 object-fit-cover rounded" style="opacity: 0.5;">
                                <h1 class="position-absolute text-white" style="font-family: monospace; opacity: 0.2">OBS STREAM <br> BUNNY.NET PIPELINE</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Próximos Eventos (Cartelera) -->
    <section id="juegos" class="py-5">
        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-end mb-5">
                <div>
                    <h6 class="text-uppercase" style="color: var(--neon-green); font-weight: 600; letter-spacing: 2px;">Cartelera</h6>
                    <h2 class="display-5 fw-bold mb-0">Eventos en Vivo & Próximos Sorteos</h2>
                </div>
                <a href="#" class="btn btn-link text-white text-decoration-none">Ver todos <i class="bi bi-arrow-right"></i></a>
            </div>

            <div class="row g-4">
                <!-- Evento 1 -->
                <div class="col-md-4">
                    <div class="glass-card p-0">
                        <div class="game-poster">
                            <i class="bi bi-trophy" style="font-size: 4rem; opacity: 0.2;"></i>
                            <div class="live-badge">EN VIVO AHORA</div>
                        </div>
                        <div class="p-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge" style="background: rgba(212, 175, 55, 0.2); color: var(--gold-premium);">Club Atlético Sinergia</span>
                                <span class="text-muted small"><i class="bi bi-people-fill me-1"></i> 1,240 Jugando</span>
                            </div>
                            <h4 class="fw-bold mb-3">Mega Bingo Solidario</h4>
                            <p class="text-muted small mb-4">Sorteo de premios en efectivo, línea dorada y pozo acumulado. Transmisión en directo por la plataforma.</p>
                            <button class="btn btn-outline-light w-100 rounded-pill" style="border-color: rgba(255,255,255,0.1)">Entrar a la Sala</button>
                        </div>
                    </div>
                </div>

                <!-- Evento 2 -->
                <div class="col-md-4">
                    <div class="glass-card p-0">
                        <div class="game-poster" style="background: linear-gradient(135deg, rgba(10,20,40,1) 0%, rgba(20,40,60,1) 100%);">
                            <i class="bi bi-stars" style="font-size: 4rem; opacity: 0.2;"></i>
                            <div class="badge bg-dark border position-absolute top-0 end-0 m-3">Faltan 2 horas</div>
                        </div>
                        <div class="p-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge" style="background: rgba(0, 255, 136, 0.1); color: var(--neon-green);">Colegio San Alberto</span>
                                <span class="text-muted small">Cartones Volando</span>
                            </div>
                            <h4 class="fw-bold mb-3">Sorteo Fin de Curso</h4>
                            <p class="text-muted small mb-4">Compra tus cartones hasta 15 minutos antes de empezar. Tablero online interactivo.</p>
                            <button class="btn btn-neon w-100">Comprar Cartón ($5.00)</button>
                        </div>
                    </div>
                </div>

                <!-- Evento 3 -->
                <div class="col-md-4">
                    <div class="glass-card p-0" style="opacity: 0.7;">
                        <div class="game-poster" style="background: linear-gradient(135deg, rgba(30,10,10,1) 0%, rgba(50,20,20,1) 100%);">
                            <i class="bi bi-building" style="font-size: 4rem; opacity: 0.2;"></i>
                            <div class="badge bg-dark border position-absolute top-0 end-0 m-3">Mañana 22:00hs</div>
                        </div>
                        <div class="p-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-secondary bg-opacity-10 text-white">Casino Norte VIP</span>
                                <span class="text-muted small">Preventa Activa</span>
                            </div>
                            <h4 class="fw-bold mb-3">Edición High Roller</h4>
                            <p class="text-muted small mb-4">El pozo más grande de la semana. Sorteo guiado por locutor profesional en vivo.</p>
                            <button class="btn w-100 rounded-pill" style="background: rgba(255,255,255,0.05); color: #fff;">Ver Detalles</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Características (Arquitectura destacada) -->
    <section id="plataforma" class="py-5 position-relative">
        <div class="container py-5">
            <div class="text-center mb-5 pb-3">
                <h2 class="display-4 fw-bold">Tecnología de Otro Planeta.</h2>
                <p class="text-muted fs-5 max-w-700 mx-auto">Nuestro motor está construido para resistir cargas extremas, calcular ganadores en milisegundos y emitir video sin caídas.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="glass-card h-100">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-cpu"></i>
                        </div>
                        <h4 class="fw-bold">Motor Algorítmico 0% Colisiones</h4>
                        <p class="text-muted text-sm mt-3">Genera miles de cartones en segundos. Nuestro algoritmo asegura que ningún cartón sea idéntico a otro y dispersa los números para evitar avalanchas de ganadores simultáneos.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="glass-card h-100">
                        <div class="feature-icon-wrapper" style="color: var(--gold-premium); background: rgba(212, 175, 55, 0.05); border-color: rgba(212, 175, 55, 0.2);">
                            <i class="bi bi-broadcast"></i>
                        </div>
                        <h4 class="fw-bold">Streaming Integrado (Caño Directo)</h4>
                        <p class="text-muted text-sm mt-3">Soporte directo para OBS Studio. Integración nativa con Bunny Stream para emitir en 1080p sin latencia, sin lag, y sin usar tu servidor.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="glass-card h-100">
                        <div class="feature-icon-wrapper" style="color: #60A5FA; background: rgba(96, 165, 250, 0.05); border-color: rgba(96, 165, 250, 0.2);">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <h4 class="fw-bold">WebSockets Ultrarrápidos</h4>
                        <p class="text-muted text-sm mt-3">El juego corre en tiempo real. Cuando una bolilla sale, todos los dispositivos conectados parpadean instantáneamente sin necesidad de recargar la página.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing (El Negocio B2B SaaS) -->
    <section id="planes" class="py-5 mb-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h6 class="text-uppercase" style="color: var(--gold-premium); font-weight: 600; letter-spacing: 2px;">Comienza a operar</h6>
                <h2 class="display-4 fw-bold mb-3">Estructura Comercial para Organizadores</h2>
                <p class="text-muted">Elige el plan que mejor se adapte al tamaño de tu base de clientes.</p>
            </div>

            <div class="row g-4 justify-content-center">
                <!-- Plan Base -->
                <div class="col-lg-4">
                    <div class="glass-card h-100 d-flex flex-column">
                        <div class="mb-4">
                            <h4 class="fw-bold text-white">Plan Club</h4>
                            <p class="text-muted small">Ideal para eventos locales, colegios y reuniones físicas.</p>
                        </div>
                        <div class="price-tag">
                            <span class="price-currency">$</span>25.000<span class="price-period">/mes</span>
                        </div>
                        <ul class="list-unstyled flex-grow-1 mt-4 mb-5 text-muted">
                            <li class="mb-3"><i class="bi bi-check2 text-success me-2"></i> Impresión de Cartones Físicos</li>
                            <li class="mb-3"><i class="bi bi-check2 text-success me-2"></i> Visor Profesional (Sorteador)</li>
                            <li class="mb-3"><i class="bi bi-check2 text-success me-2"></i> Hasta 2,000 cartones/evento</li>
                            <li class="mb-3"><i class="bi bi-x text-black-50 me-2" style="font-size: 1.2rem;"></i> Sin Streaming ni Tablero Online</li>
                        </ul>
                        <button class="btn btn-outline-light rounded-pill py-3 w-100" style="border-color: rgba(255,255,255,0.1)">Comenzar Ahora</button>
                    </div>
                </div>

                <!-- Plan Pro (Recomendado) -->
                <div class="col-lg-4">
                    <div class="glass-card h-100 d-flex flex-column" style="border-color: var(--neon-green); box-shadow: 0 0 40px rgba(0, 255, 136, 0.1);">
                        <div class="position-absolute top-0 end-0 bg-neon-green text-black px-3 py-1 rounded-bl fw-bold" style="background: var(--neon-green); color: black; font-weight: 800; font-size: 0.8rem; border-bottom-left-radius: 10px;">RECOMENDADO</div>
                        <div class="mb-4">
                            <h4 class="fw-bold text-white">Plan Profesional</h4>
                            <p class="text-muted small">Para organizadores de eventos digitales que buscan escalar.</p>
                        </div>
                        <div class="price-tag">
                            <span class="price-currency">$</span>85.000<span class="price-period">/mes</span>
                        </div>
                        <ul class="list-unstyled flex-grow-1 mt-4 mb-5 text-muted">
                            <li class="mb-3"><i class="bi bi-check2 text-success me-2"></i> Todo lo del Plan Club</li>
                            <li class="mb-3"><i class="bi bi-check2 text-success me-2"></i> Tablero Live Sockets</li>
                            <li class="mb-3"><i class="bi bi-check2 text-success me-2"></i> Cálculo Automático de Ganadores</li>
                            <li class="mb-3"><i class="bi bi-check2 text-success me-2"></i> Hasta 15,000 cartones/evento</li>
                            <li class="mb-3"><i class="bi bi-x text-black-50 me-2" style="font-size: 1.2rem;"></i> Branding Básico</li>
                        </ul>
                        <button class="btn btn-neon py-3 w-100">Suscribirse al Plan Pro</button>
                    </div>
                </div>

                <!-- Plan Omnipotente -->
                <div class="col-lg-4">
                    <div class="glass-card h-100 d-flex flex-column" style="border-color: rgba(212, 175, 55, 0.4);">
                        <div class="mb-4">
                            <h4 class="fw-bold" style="color: var(--gold-premium);">Plan VIP / Omnipotente</h4>
                            <p class="text-muted small">Experiencia completa Rolls-Royce con transmisión Streaming.</p>
                        </div>
                        <div class="price-tag">
                            <span class="fs-4 mt-3" style="color:var(--gold-premium)">Consúltanos</span>
                        </div>
                        <ul class="list-unstyled flex-grow-1 mt-4 mb-5 text-muted">
                            <li class="mb-3"><i class="bi bi-check2 text-success me-2"></i> Streaming Video Integrado (Bunny)</li>
                            <li class="mb-3"><i class="bi bi-check2 text-success me-2"></i> Marca Blanca Total (Tus colores/logos)</li>
                            <li class="mb-3"><i class="bi bi-check2 text-success me-2"></i> Cartones Ilimitados</li>
                            <li class="mb-3"><i class="bi bi-check2 text-success me-2"></i> Roles Propios y Vendedores (<span class="badge bg-secondary opacity-50">Próximamente</span>)</li>
                            <li class="mb-3"><i class="bi bi-check2 text-success me-2"></i> Soporte 24/7 de Alta Prioridad</li>
                        </ul>
                        <button class="btn btn-gold py-3 w-100">Contactar a Ventas</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Elegante -->
    <footer class="border-top" style="border-color: var(--glass-border) !important; background: #010101;">
        <div class="container py-5">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <svg class="logo-svg" style="width: 30px; height: 30px;" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="45" stroke="#00FF88" stroke-width="2" stroke-dasharray="10 5" fill="none"/>
                            <circle cx="50" cy="50" r="15" fill="#00FF88" filter="blur(2px)"/>
                        </svg>
                        <span class="fw-bold fs-5 text-white">INFINITY<span style="color: var(--neon-green)">BINGO</span></span>
                    </div>
                    <p class="text-muted small w-75">La plataforma Multi-SaaS de cartones, monitor de sorteos y streaming inmersivo más potente del mercado. Arquitectura diseñada para grandes aforos.</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="text-white fw-bold mb-3">SaaS B2B</h5>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none hover-white">Características Principales</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none hover-white">Precios y Planes</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none hover-white">Para Colegios y Clubes</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none hover-white">Soporte Técnico</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="text-white fw-bold mb-3">Legal y Empresa</h5>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none hover-white">Términos de Servicio</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none hover-white">Políticas de Privacidad</a></li>
                    </ul>
                    <div class="mt-4">
                        <a href="/login" class="btn btn-sm btn-outline-light rounded-pill px-4">Owner Login</a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5 pt-4 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
                <p class="text-muted small mb-0">&copy; 2026 Infinity Bingo SaaS Platform. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efecto mínimo parallax en orbes
        document.addEventListener("mousemove", (e) => {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            document.querySelector('.orb-1').style.transform = `translate(${x * 20}px, ${y * 20}px)`;
            document.querySelector('.orb-2').style.transform = `translate(${x * -30}px, ${y * -30}px)`;
        });
    </script>
</body>
</html>
