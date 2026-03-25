<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Transmisión TV | {{ $sorteo->jugada->nombre_jugada ?? 'Event' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-dark: #050505;
            --neon-green: #00FF88;
            --neon-blue: #00A8FF;
            --neon-gold: #D4AF37;
            --panel-bg: rgba(20, 20, 25, 0.8);
            --border-glass: rgba(255, 255, 255, 0.1);
        }

        body {
            margin: 0;
            background: var(--bg-dark);
            color: white;
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* =======================================================
           1. ZONA SUPERIOR: NÚMEROS SORTEADOS
        ======================================================= */
        .top-zone {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 40px;
            background: linear-gradient(180deg, rgba(0,0,0,0.9), rgba(0,0,0,0.2));
            border-bottom: 1px solid var(--border-glass);
            height: 140px;
        }

        /* Bolilla Mestra */
        .big-orb-container {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .big-orb {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, var(--neon-green), #004422);
            color: #000;
            font-family: 'Outfit';
            font-weight: 900;
            font-size: 60px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.4), inset -5px -5px 15px rgba(0,0,0,0.5);
            transition: 0.3s;
        }
        .big-orb.pop { animation: popOrb 0.5s ease-out; }
        @keyframes popOrb { 0% { transform: scale(1); } 50% { transform: scale(1.3); box-shadow: 0 0 50px #00FF88; } 100% { transform: scale(1); } }

        .orb-label { font-family: 'Outfit'; font-weight: 800; letter-spacing: 2px; color: #888; font-size: 0.9rem; text-transform: uppercase;}

        /* Historial */
        .history-ribbon {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .history-ribbon span {
            width: 60px; height: 60px;
            border-radius: 50%;
            background: #111;
            border: 2px solid #333;
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Outfit'; font-weight: 800; font-size: 28px;
        }
        .history-ribbon span.hi {
            background: var(--neon-blue);
            color: #fff;
            border-color: #fff;
            box-shadow: 0 0 20px rgba(0, 168, 255, 0.5);
        }

        /* =======================================================
           2. ZONA MEDIA: TRANSMISIÓN EN VIVO
        ======================================================= */
        .mid-zone {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
            position: relative;
            box-shadow: inset 0 0 50px rgba(0,0,0,1);
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
            object-fit: cover;
        }
        
        .live-tag {
            position: absolute;
            top: 20px; right: 30px;
            background: rgba(255, 0, 85, 0.2);
            color: #FF0055;
            border: 1px solid #FF0055;
            padding: 5px 15px;
            border-radius: 20px;
            font-family: 'Outfit';
            font-weight: 800;
            letter-spacing: 2px;
            z-index: 10;
            animation: blinkLive 2s infinite;
        }
        @keyframes blinkLive { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

        /* =======================================================
           3. ZONA AL FONDO: DATOS DEL SORTEO
        ======================================================= */
        .bottom-zone {
            height: 80px;
            background: var(--panel-bg);
            border-top: 1px solid var(--border-glass);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
        }

        .data-item {
            display: flex; align-items: center; gap: 10px;
        }
        .data-label { color: #888; font-family: 'Outfit'; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem; }
        .data-value { color: #fff; font-family: 'Outfit'; font-weight: 800; font-size: 1.5rem; }
        .data-value.gold { color: var(--neon-gold); }

        /* OVERLAYS de PREMIO */
        .overlay-premio {
            position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 999;
            display: flex; align-items: center; justify-content: center; flex-direction: column;
            opacity: 0; pointer-events: none; transition: 0.5s;
        }
        .overlay-premio.activo { opacity: 1; pointer-events: all; }
        .texto-premio { font-size: 12rem; font-weight: 900; font-family: 'Outfit'; }
        .premio-linea { color: var(--neon-blue); text-shadow: 0 0 50px var(--neon-blue); }
        .premio-bingo { color: #FF0055; text-shadow: 0 0 80px #FF0055; }
        
    </style>
</head>

@php
    $bolillas = $sorteo->getBolillas();
    $ultimas = array_slice(array_reverse($bolillas), 0, 9);
    $streamUrl = $sorteo->jugada->streaming_url ?? 'https://www.youtube.com/embed/live_stream?channel=UC4R8DWoMoI7CAwX8_LjQHig'; // Default random live
@endphp

<body>

<!-- OVERLAYS -->
<div id="overlay-linea" class="overlay-premio">
    <div class="texto-premio premio-linea">¡LÍNEA!</div>
</div>
<div id="overlay-bingo" class="overlay-premio">
    <div class="texto-premio premio-bingo">¡BINGO!</div>
</div>

<!-- ================= 1. ARRIBA ================= -->
<div class="top-zone">
    <div class="big-orb-container">
        <div class="orb-label">Extracción<br>Actual</div>
        <div class="big-orb" id="bolillaActual">
            {{ $sorteo->bolilla_actual ?? '—' }}
        </div>
    </div>

    <div>
        <div class="history-ribbon" id="historialCinta">
            @for($i=0; $i<8; $i++)
                <span class="{{ isset($ultimas[$i]) ? 'hi' : '' }}">{{ $ultimas[$i] ?? '' }}</span>
            @endfor
        </div>
    </div>
</div>

<!-- ================= 2. MEDIO ================= -->
<div class="mid-zone">
    <div class="live-tag">🔴 EN DIRECTO</div>
    <iframe src="{{ $streamUrl }}?autoplay=1&mute=0&controls=0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
</div>

<!-- ================= 3. FONDO ================= -->
<div class="bottom-zone">
    <div class="data-item">
        <div class="data-label"><i class="bi bi-box me-1"></i> Serie Evento:</div>
        <div class="data-value">{{ mb_strtoupper($sorteo->jugada->nombre_jugada) }}</div>
    </div>
    <div class="data-item">
        <div class="data-label"><i class="bi bi-building me-1"></i> Auspicia:</div>
        <div class="data-value gold">{{ mb_strtoupper($sorteo->jugada->institucion->nombre ?? 'CLUB LOCAL') }}</div>
    </div>
    <div class="data-item">
        <div class="data-label"><i class="bi bi-hash me-1"></i> Extracciones:</div>
        <div class="data-value text-white" id="conteoBolas">{{ count($bolillas) }} / 90</div>
    </div>
    <div class="data-item">
        <div class="data-label"><i class="bi bi-broadcast me-1"></i> Estado:</div>
        <div class="data-value" style="color: var(--neon-green);" id="estadoTxt">{{ strtoupper($sorteo->estado) }}</div>
    </div>
</div>

<!-- ================= SCRIPTS PUSHER CORREGIDO ================= -->
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: window.location.protocol === 'https:',
        enabledTransports: ['ws', 'wss']
    });

    const channel = pusher.subscribe('jugada.{{ $jugadaId ?? $sorteo->jugada_id }}');
    let totalBolas = {{ count($bolillas) }};

    channel.bind('SorteoActualizado', data => {
        
        // 1. Efecto en la bolilla principal
        const bActual = document.getElementById('bolillaActual');
        bActual.innerText = data.bolilla ?? '—';
        bActual.classList.remove('pop');
        void bActual.offsetWidth; // Reflow
        bActual.classList.add('pop');

        // 2. Historial Top Right
        const cinta = document.getElementById('historialCinta');
        cinta.innerHTML = '';
        data.ultimas.slice(0, 8).forEach((num, i) => {
            const orb = document.createElement('span');
            orb.innerText = num;
            if(i === 0) orb.classList.add('hi');
            cinta.appendChild(orb);
        });

        // 3. Datos Inferiores
        document.getElementById('estadoTxt').innerText = data.estado.toUpperCase();
        document.getElementById('conteoBolas').innerText = data.bolillas.length + " / 90";

        // 4. Overlays
        document.getElementById('overlay-linea').classList.toggle('activo', data.estado === 'linea');
        document.getElementById('overlay-bingo').classList.toggle('activo', data.estado === 'bingo');
    });

});
</script>

</body>
</html>
