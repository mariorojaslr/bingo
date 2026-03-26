<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Monitor TV | {{ $sorteo->jugada->nombre_jugada ?? 'Evento' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --neon-green: #00FF88;
            --neon-blue: #00A8FF;
            --neon-gold: #D4AF37;
            --neon-red: #FF0055;
            --bg-dark: #020202;
            --border-glass: rgba(255, 255, 255, 0.05);
        }

        body {
            margin: 0; padding: 0;
            background: var(--bg-dark);
            color: white;
            font-family: 'Inter', sans-serif;
            height: 100vh;
            overflow: hidden; /* Fundamental para un Monitor TV */
            display: flex;
        }

        .monitor-wrapper {
            display: grid;
            grid-template-columns: 420px 1fr;
            width: 100%; height: 100%;
        }

        /* =======================================================
           1. BARRA LATERAL IZQUIERDA (Bolillero y Matriz)
        ======================================================= */
        .sidebar {
            background: rgba(10, 10, 12, 0.95);
            border-right: 1px solid var(--border-glass);
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 20px 0 50px rgba(0,0,0,0.5);
            z-index: 10;
        }

        .panel-title {
            font-family: 'Outfit'; font-size: 0.85rem; color: #888;
            text-transform: uppercase; letter-spacing: 2px;
            text-align: center; border-bottom: 1px dashed rgba(255,255,255,0.1);
            padding-bottom: 10px; margin-bottom: 20px;
        }

        /* Bolilla Mestra */
        .bolilla-box {
            background: rgba(0,0,0,0.4);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        .orb-gigante {
            width: 220px; height: 220px; margin: 0 auto;
            background: radial-gradient(circle at 30% 30%, var(--neon-green), #004422);
            border-radius: 50%;
            color: #000; font-family: 'Outfit'; font-weight: 900; font-size: 110px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 50px rgba(0, 255, 136, 0.4), inset -10px -10px 25px rgba(0,0,0,0.5);
            transition: 0.3s;
        }
        .orb-gigante.pop { animation: popOrb 0.5s ease-out; }
        @keyframes popOrb { 0% { transform: scale(1); } 50% { transform: scale(1.15); box-shadow: 0 0 80px #00FF88; } 100% { transform: scale(1); } }

        /* Historial de Bolillas */
        .history-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-top: 20px;
        }
        .history-item {
            aspect-ratio: 1; border-radius: 50%; background: #111; color: #555;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 1.5rem; font-family: 'Outfit';
            border: 2px solid #222;
        }
        .history-item.hi {
            background: var(--neon-blue); color: #fff; border-color: transparent;
            box-shadow: 0 0 20px rgba(0, 168, 255, 0.5);
        }

        /* Matriz Sorteador (Abajo a la izquierda) */
        .matriz-box {
            flex: 1; background: rgba(0,0,0,0.4); border: 1px solid var(--border-glass);
            border-radius: 20px; padding: 20px;
            display: flex; flex-direction: column;
        }
        .matrix-grid {
            display: grid; grid-template-columns: repeat(10, 1fr); gap: 4px; flex: 1;
            align-content: start;
        }
        .matrix-num {
            aspect-ratio: 1; background: rgba(255,255,255,0.03); color: rgba(255,255,255,0.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; font-weight: 900; border-radius: 4px;
        }
        .matrix-num.drawn { background: var(--neon-red); color: #fff; box-shadow: 0 0 10px rgba(255, 0, 85, 0.5); }


        /* =======================================================
           2. ÁREA PRINCIPAL DERECHA (Transmisión y Footer)
        ======================================================= */
        .main-stage {
            display: flex; flex-direction: column; position: relative;
        }

        .tv-container {
            flex: 1; background: #000; position: relative;
        }
        .tv-container iframe {
            width: 100%; height: 100%; border: none; object-fit: cover;
        }
        .live-badge {
            position: absolute; top: 30px; right: 30px;
            background: rgba(255, 0, 85, 0.2); color: var(--neon-red);
            border: 1px solid var(--neon-red); padding: 8px 20px; border-radius: 30px;
            font-family: 'Outfit'; font-weight: 800; letter-spacing: 2px;
            animation: blinkLive 2s infinite; z-index: 10;
        }

        /* Footer Info */
        .info-footer {
            height: 90px; background: rgba(10, 10, 12, 0.95);
            border-top: 1px solid var(--border-glass);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 50px; box-shadow: 0 -10px 30px rgba(0,0,0,0.5);
        }

        .data-item { display: flex; align-items: center; gap: 15px; }
        .data-label { color: #888; font-family: 'Outfit'; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; }
        .data-value { color: #fff; font-family: 'Outfit'; font-weight: 900; font-size: 1.8rem; }
        .data-value.gold { color: var(--neon-gold); }

        /* OVERLAYS de PREMIO */
        .overlay-premio {
            position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 9999;
            display: flex; align-items: center; justify-content: center; flex-direction: column;
            opacity: 0; pointer-events: none; transition: 0.4s;
        }
        .overlay-premio.activo { opacity: 1; pointer-events: all; }
        .texto-premio { font-size: 15rem; font-weight: 900; font-family: 'Outfit'; animation: pulseText 1s infinite alternate; }
        .premio-linea { color: var(--neon-blue); text-shadow: 0 0 80px var(--neon-blue); }
        .premio-bingo { color: var(--neon-red); text-shadow: 0 0 80px var(--neon-red); }
        @keyframes pulseText { from { transform: scale(0.95); } to { transform: scale(1.05); } }
        
    </style>
</head>

@php
    $bolillas = $sorteo->getBolillas();
    $ultimas = array_slice(array_reverse($bolillas), 0, 8);
    $streamUrl = $sorteo->jugada->streaming_url ?? 'https://www.youtube.com/embed/live_stream?channel=UC4R8DWoMoI7CAwX8_LjQHig'; 
@endphp

<body>

<!-- OVERLAYS DE PREMIO -->
<div id="overlay-linea" class="overlay-premio">
    <div class="texto-premio premio-linea">¡LÍNEA!</div>
    <div class="h3 text-white-50 mt-4 font-monospace">VERIFICANDO GANADORES EN SALA...</div>
</div>
<div id="overlay-bingo" class="overlay-premio">
    <div class="texto-premio premio-bingo">¡BINGO!</div>
    <div class="h3 text-white-50 mt-4 font-monospace">SORTEO EXTRAORDINARIO FINALIZADO</div>
</div>

<div class="monitor-wrapper">

    <!-- ================= 1. SIDEBAR IZQUIERDA ================= -->
    <div class="sidebar">
        
        <!-- Extracción Actual -->
        <div class="bolilla-box">
            <div class="panel-title text-success"><i class="bi bi-circle-fill me-1"></i> BOLILLA PRINCIPAL</div>
            <div class="orb-gigante" id="bolillaActual">
                {{ $sorteo->bolilla_actual ?? '—' }}
            </div>
            
            <div class="history-grid" id="historialCinta">
                @for($i=0; $i<8; $i++)
                    <div class="history-item {{ isset($ultimas[$i]) ? 'hi' : '' }}">{{ $ultimas[$i] ?? '' }}</div>
                @endfor
            </div>
        </div>

        <!-- Sorteador Matriz 1-90 -->
        <div class="matriz-box">
            <div class="panel-title"><i class="bi bi-grid-3x3-gap-fill me-1"></i> TABLERO CENTRAL (SORTEADOR)</div>
            <div class="matrix-grid">
                @for($i=1; $i<=90; $i++)
                    <div class="matrix-num {{ in_array($i, $bolillas) ? 'drawn' : '' }}" id="mat-{{$i}}">{{ $i }}</div>
                @endfor
            </div>
        </div>

    </div>


    <!-- ================= 2. ESCENARIO PRINCIPAL ================= -->
    <div class="main-stage">
        
        <!-- Transmisión de TV -->
        <div class="tv-container">
            <div class="live-badge">🔴 EN VIVO</div>
            <iframe src="{{ $streamUrl }}?autoplay=1&mute=0&controls=0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>

        <!-- Footer / Ticker de Información -->
        <div class="info-footer">
            <div class="data-item">
                <div class="data-label"><i class="bi bi-box me-1"></i> EVENTO</div>
                <div class="data-value">{{ mb_strtoupper($sorteo->jugada->nombre_jugada) }}</div>
            </div>
            <div class="data-item">
                <div class="data-label"><i class="bi bi-building me-1"></i> AUSPICIA</div>
                <div class="data-value gold">{{ mb_strtoupper($sorteo->jugada->institucion->nombre ?? 'CLUB OFICIAL') }}</div>
            </div>
            <div class="data-item">
                <div class="data-label"><i class="bi bi-hash me-1"></i> EXTRACCIÓN</div>
                <div class="data-value text-white" id="conteoBolas">{{ count($bolillas) }} / 90</div>
            </div>
        </div>

    </div>

</div>

<!-- ================= SCRIPTS PUSHER ================= -->
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: window.location.protocol === 'https:',
        enabledTransports: ['ws', 'wss']
    });

    const channel = pusher.subscribe('jugada.{{ $jugadaId ?? $sorteo->jugada_id }}');

    channel.bind('SorteoActualizado', data => {
        
        // REINICIO
        if(data.bolilla === null) {
            document.getElementById('bolillaActual').innerText = '—';
            document.getElementById('historialCinta').innerHTML = '';
            document.querySelectorAll('.matrix-num').forEach(c => c.classList.remove('drawn'));
            document.getElementById('conteoBolas').innerText = "0 / 90";
            document.getElementById('overlay-linea').classList.remove('activo');
            document.getElementById('overlay-bingo').classList.remove('activo');
            return;
        }

        // 1. Efecto en la bolilla principal
        const bActual = document.getElementById('bolillaActual');
        bActual.innerText = data.bolilla;
        bActual.classList.remove('pop');
        void bActual.offsetWidth; // Reflow
        bActual.classList.add('pop');

        // 2. Historial Top Left
        const cinta = document.getElementById('historialCinta');
        cinta.innerHTML = '';
        data.ultimas.slice(0, 8).forEach((num, i) => {
            const orb = document.createElement('div');
            orb.className = 'history-item ' + (i === 0 ? 'hi' : '');
            orb.innerText = num;
            cinta.appendChild(orb);
        });

        // 3. Marcar Tablero (Sorteador 1-90)
        const mat = document.getElementById('mat-' + data.bolilla);
        if(mat) mat.classList.add('drawn');

        // 4. Datos Inferiores
        document.getElementById('conteoBolas').innerText = data.bolillas.length + " / 90";

        // 5. Overlays
        document.getElementById('overlay-linea').classList.toggle('activo', data.estado === 'linea');
        document.getElementById('overlay-bingo').classList.toggle('activo', data.estado === 'bingo');
    });

});
</script>

</body>
</html>
