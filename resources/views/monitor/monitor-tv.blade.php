<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor TV - Infinity Bingo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: transparent; /* Transparente para OBS/Vmix */
            font-family: 'Montserrat', sans-serif;
            overflow: hidden; /* Evitar scroll */
        }

        /* --- CONTENEDOR PRINCIPAL --- */
        .tv-container {
            display: flex;
            width: 100%;
            height: 100vh;
            flex-direction: column;
        }

        /* --- BARRA LATERAL (Panel Izquierdo) --- */
        .sidebar {
            width: 420px;
            height: calc(100vh - 60px); /* Restando el zócalo */
            background-color: #0f1115; /* Fondo muy oscuro */
            border-right: 2px solid #1a1d24;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 15px;
            box-shadow: 5px 0 25px rgba(0,0,0,0.8);
            position: absolute;
            left: 0;
            top: 0;
            z-index: 10;
        }

        /* --- BOLILLA PRINCIPAL --- */
        .header-title {
            color: #00ff88;
            font-size: 0.75rem;
            letter-spacing: 2px;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .header-title::before {
            content: '';
            width: 8px;
            height: 8px;
            background-color: #00ff88;
            border-radius: 50%;
            box-shadow: 0 0 10px #00ff88;
        }

        .main-ball {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #00ff88, #009955);
            box-shadow: 0 0 40px rgba(0, 255, 136, 0.5), inset -10px -10px 30px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
        }
        .main-number {
            font-size: 7.5rem;
            font-weight: 900;
            color: #111;
            line-height: 1;
            text-shadow: 1px 1px 2px rgba(255,255,255,0.3);
        }

        /* --- HISTORIAL DE BOLILLAS (4x2) --- */
        .history-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 30px;
            width: 100%;
        }
        .history-ball {
            aspect-ratio: 1;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #00a8ff, #005f99);
            box-shadow: 0 0 15px rgba(0, 168, 255, 0.4), inset -5px -5px 15px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 900;
            color: #fff;
        }

        /* --- TABLERO CENTRAL (90 Números) --- */
        .board-title {
            color: #888;
            font-size: 0.7rem;
            letter-spacing: 1px;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
            text-align: center;
        }
        .board-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 4px;
            width: 100%;
            margin-top: 10px;
        }
        .board-cell {
            aspect-ratio: 1;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            color: #aaa;
            transition: all 0.3s ease;
        }
        .board-cell.active {
            background: #ff0055;
            color: #fff;
            box-shadow: 0 0 10px #ff0055;
        }

        /* --- ZÓCALO INFERIOR --- */
        .bottom-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background-color: #0b0c0f;
            border-top: 2px solid #1a1d24;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 10;
        }
        .bottom-bar .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #888;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .bottom-bar .info-value {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 900;
        }
        .bottom-bar .sponsor {
            color: #ffd700; /* Dorado para destacar el sponsor */
        }

        /* --- BOTÓN EN VIVO --- */
        .live-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            background: rgba(255, 0, 85, 0.15);
            border: 1px solid #ff0055;
            color: #ff0055;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 900;
            font-size: 0.8rem;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 10;
        }
        .live-btn::before {
            content: '';
            width: 10px;
            height: 10px;
            background-color: #ff0055;
            border-radius: 50%;
            box-shadow: 0 0 10px #ff0055;
            animation: blink 1.5s infinite;
        }

        /* --- OVERLAYS --- */
        #takeover-ad, #winner-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background: rgba(0,0,0,0.95);
        }
        .winner-text {
            font-size: 8rem;
            font-weight: 900;
            color: #ffd700;
            text-transform: uppercase;
            text-shadow: 0 0 40px rgba(255, 215, 0, 0.6);
            animation: pulse 1s infinite alternate;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        @keyframes pulse {
            from { transform: scale(1); }
            to { transform: scale(1.05); }
        }
    </style>
</head>
<body>

    <!-- Botón En Vivo (Esquina Superior Derecha) -->
    <div class="live-btn">EN VIVO</div>

    <!-- BARRA LATERAL -->
    <div class="sidebar">
        <div class="header-title">BOLILLA PRINCIPAL</div>
        
        <div class="main-ball">
            <span class="main-number" id="last-number">--</span>
        </div>

        <div class="history-grid" id="history-box">
            <!-- Bolillas del historial (últimas 8) se cargan con JS -->
        </div>

        <div class="board-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-grid-3x3-gap-fill" viewBox="0 0 16 16">
              <path d="M1 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zM1 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zM1 12a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/>
            </svg>
            TABLERO CENTRAL
        </div>
        <div class="board-grid">
            @for($i = 1; $i <= 90; $i++)
                <div class="board-cell" id="cell-{{ $i }}">{{ $i }}</div>
            @endfor
        </div>
    </div>

    <!-- ZÓCALO INFERIOR -->
    <div class="bottom-bar">
        <div class="info-item">
            EVENTO <span class="info-value">JUGADA DE PRUEBA</span>
        </div>
        <div class="info-item">
            AUSPICIA <span class="info-value sponsor">CLUB DE PRUEBA</span>
        </div>
        <div class="info-item">
            EXTRACCIÓN <span class="info-value"><span id="extract-count">0</span>/90</span>
        </div>
    </div>

    <!-- Overlay Ganador -->
    <div id="winner-overlay">
        <div class="winner-text" id="winner-type">¡GANADOR!</div>
    </div>

    <!-- Overlay Publicidad -->
    <div id="takeover-ad">
        <img src="https://via.placeholder.com/1920x1080/000000/00bfa5?text=ESPACIO+PUBLICITARIO" style="max-width:100%; max-height:100%;">
    </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const bolillas = @json($sorteo->getBolillas() ?? []);
            const estado = "{{ $sorteo->estado ?? 'en_curso' }}";

            function renderizar(bolillas) {
                document.getElementById('extract-count').innerText = bolillas.length;

                if(bolillas.length > 0) {
                    const ultima = bolillas[bolillas.length - 1];
                    document.getElementById('last-number').innerText = ultima;
                    
                    // Pintar grilla
                    bolillas.forEach(num => {
                        const cell = document.getElementById('cell-' + num);
                        if(cell && !cell.classList.contains('active')) {
                            cell.classList.add('active');
                        }
                    });

                    // Historial (últimas 8 sin contar la actual, en reverso)
                    const historial = bolillas.slice(0, -1).slice(-8).reverse();
                    const historyBox = document.getElementById('history-box');
                    historyBox.innerHTML = '';
                    historial.forEach(num => {
                        historyBox.innerHTML += `<div class="history-ball">${num}</div>`;
                    });
                }
            }

            renderizar(bolillas);

            // WebSockets
            setTimeout(() => {
                if(window.Echo) {
                    window.Echo.channel('jugada.{{ $jugada->id ?? ($jugadaId ?? 1) }}')
                        .listen('.SorteoActualizado', (e) => {
                            console.log("Evento recibido:", e);
                            renderizar(e.bolillas);

                            const winnerOverlay = document.getElementById('winner-overlay');
                            if(e.estado === 'linea') {
                                document.getElementById('winner-type').innerText = '¡HAY LÍNEA!';
                                winnerOverlay.style.display = 'flex';
                            } else if(e.estado === 'bingo') {
                                document.getElementById('winner-type').innerText = '¡BINGO!';
                                winnerOverlay.style.display = 'flex';
                            } else if(e.estado === 'publicidad') {
                                document.getElementById('takeover-ad').style.display = 'flex';
                            } else {
                                winnerOverlay.style.display = 'none';
                                document.getElementById('takeover-ad').style.display = 'none';
                            }
                        });
                }
            }, 1000);
        });
    </script>
</body>
</html>
