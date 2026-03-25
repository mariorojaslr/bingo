<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Monitor Clásico 1-90 | {{ $jugada->nombre_jugada }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bg-dark: #000;
            --neon-green: #00FF88;
            --neon-blue: #00A8FF;
            --border-glass: rgba(255, 255, 255, 0.08);
            --panel-bg: rgba(10, 10, 15, 0.8);
        }

        body {
            margin: 0;
            background-color: var(--bg-dark);
            background-image: radial-gradient(circle at 50% 50%, rgba(0, 255, 136, 0.05), transparent 70%);
            color: white;
            font-family: 'Outfit', sans-serif;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            background: linear-gradient(180deg, rgba(0,0,0,0.9), transparent);
            padding: 1.5rem 3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-glass);
            height: 100px;
        }

        .main-container {
            flex: 1;
            display: grid;
            grid-template-columns: 350px 1fr;
            padding: 2rem;
            gap: 2rem;
        }

        .left-panel {
            background: var(--panel-bg);
            border: 1px solid var(--border-glass);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            box-shadow: 0 0 30px rgba(0,0,0,0.8);
        }

        .big-orb {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, var(--neon-green), #006633);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 110px;
            font-weight: 900;
            color: #000;
            box-shadow: 0 0 40px rgba(0, 255, 136, 0.5), inset -10px -10px 20px rgba(0,0,0,0.5);
            margin-bottom: 3rem;
        }

        .history-ribbon {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            width: 100%;
            direction: rtl; /* Ultima a la derecha visualmente si se llena asi, o ltr con reverse */
        }

        .mini-orb {
            aspect-ratio: 1;
            border-radius: 50%;
            background: rgba(0,0,0,0.5);
            border: 2px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 800;
            color: #777;
            direction: ltr;
        }

        .mini-orb.active {
            background: var(--neon-blue);
            color: #fff;
            border-color: #fff;
            box-shadow: 0 0 15px var(--neon-blue);
        }

        .matrix-panel {
            background: var(--panel-bg);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            padding: 2rem;
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 10px;
            align-content: stretch;
            box-shadow: 0 0 30px rgba(0,0,0,0.8);
        }

        .matrix-num {
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            font-size: 2.2rem;
            font-weight: 800;
            color: rgba(255,255,255,0.2);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .matrix-num.drawn {
            background: var(--neon-green);
            color: #000;
            border-color: #fff;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.6);
            transform: scale(1.05);
            z-index: 2;
        }

        /* OVERLAYS HOLOGRÁFICOS */
        .overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.85);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none; z-index: 9999;
            transition: 0.5s;
        }

        .overlay.activo { opacity: 1; }

        .overlay-texto {
            font-size: 15rem; font-weight: 900;
            color: var(--neon-blue); text-shadow: 0 0 50px var(--neon-blue);
            text-transform: uppercase; animation: popHolo 1s infinite alternate;
        }
        .overlay-texto.bingo {
            color: #ff4757; text-shadow: 0 0 50px #ff4757;
        }

        @keyframes popHolo { 0% { transform: scale(1); } 100% { transform: scale(1.05); filter: brightness(1.3); } }
    </style>
</head>
@php
    $bolillas = $sorteo->getBolillas();
    $ultimas = array_slice(array_reverse($bolillas), 0, 9);
@endphp
<body>

<!-- OVERLAYS LÍNEA Y BINGO -->
<div id="overlay-linea" class="overlay">
    <div class="overlay-texto">¡LÍNEA!</div>
</div>
<div id="overlay-bingo" class="overlay">
    <div class="overlay-texto bingo">¡BINGO!</div>
</div>

<header class="top-bar">
    <div class="d-flex align-items-center gap-3">
        <h2 class="mb-0 fw-bold" style="letter-spacing: 2px;">{{ mb_strtoupper($jugada->institucion->nombre ?? 'BINGO LOCAL') }}</h2>
    </div>
    <div class="d-flex align-items-center gap-4">
        <h3 class="mb-0 text-white-50">{{ mb_strtoupper($jugada->nombre_jugada) }}</h3>
        <span class="badge border py-2 px-3 fs-6 rounded-pill" style="background: rgba(0,255,136,0.1); color: var(--neon-green); border-color: var(--neon-green) !important;" id="estadoTxt">
            EN JUEGO
        </span>
    </div>
</header>

<div class="main-container">
    
    <div class="left-panel">
        <div class="text-white-50 fw-bold mb-4" style="letter-spacing: 3px;">EXTRACCIÓN ACTUAL</div>
        <div class="big-orb" id="numero-actual">
            {{ $sorteo->bolilla_actual ?? '—' }}
        </div>
        
        <div class="text-white-50 fw-bold mb-3 mt-5" style="letter-spacing: 3px;">ÚLTIMOS NÚMEROS</div>
        <div class="history-ribbon" style="direction: rtl;" id="historial">
            @for($i=0; $i<9; $i++)
                <div class="mini-orb {{ isset($ultimas[$i]) ? 'active' : '' }}">{{ $ultimas[$i] ?? '—' }}</div>
            @endfor
        </div>
    </div>

    <div class="matrix-panel">
        @for($i = 1; $i <= 90; $i++)
            <div class="matrix-num {{ in_array($i, $bolillas) ? 'drawn' : '' }}" id="num-{{ $i }}">{{ $i }}</div>
        @endfor
    </div>

</div>

<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
    const pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
        forceTLS: window.location.protocol === 'https:',
        enabledTransports: ['ws', 'wss']
    });

    const channel = pusher.subscribe('jugada.{{ $jugada->id }}');

    channel.bind('SorteoActualizado', data => {
        // 1. Bolilla Maestra
        document.getElementById('numero-actual').innerText = data.bolilla ?? '–';
        
        // 2. Estado Overlays
        let est = data.estado;
        document.getElementById('estadoTxt').innerText = est.toUpperCase();
        
        document.getElementById('overlay-linea').classList.toggle('activo', est === 'linea');
        document.getElementById('overlay-bingo').classList.toggle('activo', est === 'bingo');

        // 3. Matriz 1-90
        document.querySelectorAll('.matrix-num').forEach(el => {
            const n = parseInt(el.innerText);
            if (data.bolillas.includes(n)) el.classList.add('drawn');
            else el.classList.remove('drawn');
        });

        // 4. Historial (Derecha a Izquierda RTL)
        const histCont = document.getElementById('historial');
        histCont.innerHTML = '';
        for(let i=0; i<9; i++){
            let val = data.ultimas[i] ?? '—';
            histCont.innerHTML += `<div class="mini-orb ${val!=='—' ? 'active':''}">${val}</div>`;
        }
    });

    // Retro-compatibilidad de eventos sueltos si el controlador original los dispara
    channel.bind('BolillaSorteada', data => {
        document.getElementById('numero-actual').innerText = data.bolilla;
        const hist = document.getElementById('historial');
        hist.innerHTML = '';
        data.ultimas.forEach(val => { hist.innerHTML += `<div class="mini-orb active">${val}</div>`; });
        document.getElementById('num-' + data.bolilla).classList.add('drawn');
    });

    channel.bind('LineaConfirmada', () => { document.getElementById('overlay-linea').classList.add('activo'); });
    channel.bind('BingoConfirmado', () => { document.getElementById('overlay-bingo').classList.add('activo'); });
    channel.bind('JuegoReanudado', () => { document.getElementById('overlay-linea').classList.remove('activo'); document.getElementById('overlay-bingo').classList.remove('activo'); });
</script>

</body>
</html>
