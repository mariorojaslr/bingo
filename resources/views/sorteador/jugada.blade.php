<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Sorteador Operativo | {{ $jugada->nombre_jugada }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-dark: #020202;
            --bg-panel: rgba(15, 15, 20, 0.7);
            --border-glass: rgba(255, 255, 255, 0.05);
            --neon-green: #00FF88;
            --neon-blue: #00A8FF;
            --neon-red: #ff4757;
            --neon-gold: #D4AF37;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: #fff;
            min-height: 100vh;
            background-image: radial-gradient(circle at 50% -20%, rgba(0, 168, 255, 0.15) 0%, transparent 60%);
            padding-bottom: 2rem;
            margin: 0;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, .brand-font { font-family: 'Outfit', sans-serif; }

        .top-navbar {
            background: rgba(0,0,0,0.8);
            border-bottom: 1px solid var(--border-glass);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            top: 0; position: sticky; z-index: 100;
            backdrop-filter: blur(10px);
        }

        .dashboard-container {
            width: 100%;
            max-width: 900px; /* Reducido para 2 paneles centrales */
            margin: 2rem auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .glass-panel {
            background: var(--bg-panel);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* BOLILLA CENTRAL */
        .bolilla-orb {
            width: 250px; height: 250px;
            margin: 1rem auto;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, var(--neon-green), #006633);
            display: flex; align-items: center; justify-content: center;
            font-size: 110px; font-weight: 900; font-family: 'Outfit';
            color: #000;
            box-shadow: 0 0 50px rgba(0, 255, 136, 0.4), inset -10px -10px 20px rgba(0,0,0,0.4);
            text-shadow: 2px 2px 5px rgba(255,255,255,0.4);
        }

        /* HISTORIAL 9 BOLILLAS (Derecha a Izquierda) */
        .history-container {
            display: grid;
            grid-template-columns: repeat(9, 1fr);
            gap: 5px;
            margin-top: 1.5rem;
            background: rgba(0,0,0,0.5);
            padding: 10px;
            border-radius: 12px;
            border: 1px solid var(--border-glass);
            direction: rtl; /* IMPORTANTE: Derecha a izquierda */
        }

        .mini-orb {
            aspect-ratio: 1;
            border-radius: 50%;
            background: #111;
            border: 1px solid #333;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 16px; color: #555;
            direction: ltr; /* Numeros se leen normales */
        }
        .mini-orb.active { background: var(--neon-blue); color: #fff; border-color: #fff; box-shadow: 0 0 10px var(--neon-blue); }

        /* CONTROLES ESTILO CONSOLA */
        .btn-launch {
            width: 100%; border-radius: 12px; padding: 20px;
            font-family: 'Outfit'; font-weight: 800; font-size: 1.5rem; letter-spacing: 2px;
            text-transform: uppercase; border: none; transition: 0.3s;
            background: var(--neon-green); color: #000; margin-bottom: 1.5rem;
        }
        .btn-launch:active { transform: scale(0.95); }
        .btn-launch:hover { background: #fff; box-shadow: 0 0 30px var(--neon-green); }

        .btn-action {
            width: 100%; padding: 15px; border-radius: 12px; font-weight: 700; font-family: 'Outfit';
            background: transparent; border: 2px solid; transition: 0.3s;
        }
        .btn-action.linea { border-color: var(--neon-blue); color: var(--neon-blue); }
        .btn-action.linea:hover { background: var(--neon-blue); color: #fff; box-shadow: 0 0 20px var(--neon-blue); }
        
        .btn-action.bingo { border-color: var(--neon-red); color: var(--neon-red); }
        .btn-action.bingo:hover { background: var(--neon-red); color: #fff; box-shadow: 0 0 20px var(--neon-red); }

        /* ÁREA DE GANADORES */
        .winner-box {
            display: none;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid var(--neon-gold);
            border-radius: 12px; padding: 15px; margin-top: 15px;
            animation: pulse-gold 2s infinite;
        }
        .winner-box.show { display: block; }
        @keyframes pulse-gold { 0% { box-shadow: 0 0 10px rgba(212,175,55,0.2); } 50% { box-shadow: 0 0 20px rgba(212,175,55,0.5); } 100% { box-shadow: 0 0 10px rgba(212,175,55,0.2); } }

        /* RESPONSIVE (Celular Primero Arriba Abajo) */
        @media (max-width: 900px) {
            .dashboard-container { grid-template-columns: 1fr; gap: 1rem; padding: 1rem; margin: 1rem auto; }
            .bolilla-orb { width: 200px; height: 200px; font-size: 80px; }
            .btn-launch { padding: 15px; font-size: 1.2rem; }
            .top-navbar { padding: 1rem; }
        }
    </style>
</head>

@php
    $bolillas = $sorteo->getBolillas();
    $ultimas = array_slice(array_reverse($bolillas), 0, 9);
@endphp

<body>

<header class="top-navbar">
    <div class="d-flex align-items-center gap-2">
        <i class="bi bi-controller fs-3 text-white"></i>
        <div>
            <h5 class="mb-0 fw-bold brand-font text-warning">{{ mb_strtoupper($jugada->nombre_jugada) }}</h5>
            <span class="badge" style="background: rgba(255,255,255,0.1); font-family: 'Inter';" id="estadoTxt"><i class="bi bi-broadcast me-1"></i> ESTADO: {{ strtoupper($sorteo->estado) }}</span>
        </div>
    </div>
    <a href="/admin/jugadas/{{ $jugada->id }}" class="btn btn-sm btn-outline-light"><i class="bi bi-box-arrow-left"></i> Salir</a>
</header>

<div class="dashboard-container">
    
    <!-- PANEL 1: BOLILLA Y SECUENCIA -->
    <div class="glass-panel" style="justify-content: space-between;">
        <h6 class="text-center text-white-50 fw-bold mb-0" style="letter-spacing: 2px;">EXTRACCIÓN ACTUAL</h6>
        
        <div class="bolilla-orb" id="bolillaActual">
            {{ $sorteo->bolilla_actual ?? '–' }}
        </div>
        
        <div class="w-100 mt-4">
            <h6 class="text-center text-white-50 fw-bold" style="font-size: 0.75rem; letter-spacing: 2px;">SECUENCIA (DER a IZQ)</h6>
            <div class="history-container" id="ultimas">
                @for($i=0; $i<9; $i++)
                    <div class="mini-orb {{ isset($ultimas[$i]) ? 'active' : '' }}">{{ $ultimas[$i] ?? '—' }}</div>
                @endfor
            </div>
        </div>
    </div>

    <!-- PANEL 3: MANDOS Y GANADORES -->
    <div class="glass-panel text-center">
        <h6 class="text-white-50 fw-bold mb-4" style="font-size: 0.8rem; letter-spacing: 2px;"><i class="bi bi-sliders"></i> CONSOLA DE MANDOS</h6>
        
        <button id="btnSacar" class="btn-launch"><i class="bi bi-play-fill me-1"></i> Extraer</button>
        
        <div class="d-flex gap-2 mb-3">
            <button id="btnLinea" class="btn-action linea"><i class="bi bi-pause"></i> PAUSA LÍNEA</button>
            <button id="btnBingo" class="btn-action bingo"><i class="bi bi-stop"></i> BINGO FINAL</button>
        </div>

        <button id="btnReiniciar" class="btn-action text-white-50 border-secondary mt-3"><i class="bi bi-arrow-counterclockwise"></i> Reiniciar Mesa</button>

        <!-- CAJA DE REPORTE AUTOMATICO DEL SISTEMA -->
        <div class="winner-box" id="winnerBox">
            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                <i class="bi bi-trophy-fill fs-4" style="color: var(--neon-gold)"></i>
                <h6 class="mb-0 fw-bold" style="color: var(--neon-gold); letter-spacing: 1px;">¡POSIBLE GANADOR!</h6>
            </div>
            <p class="small text-white mb-2">El sistema ha detectado un cartón ganador. Esperando grito en sala.</p>
            <div class="p-2 rounded bg-dark border border-secondary text-center font-monospace small text-white" id="winnerData">
                Buscando...
            </div>
        </div>
    </div>
</div>

<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
    const csrf = '{{ csrf_token() }}';
    function postCall(url) {
        fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf } });
    }

    document.getElementById('btnSacar').onclick     = () => postCall('{{ route("sorteador.extraer", $jugadaId) }}');
    document.getElementById('btnLinea').onclick     = () => postCall('{{ route("sorteador.confirmar.linea", $jugadaId) }}');
    document.getElementById('btnBingo').onclick     = () => postCall('{{ route("sorteador.confirmar.bingo", $jugadaId) }}');
    document.getElementById('btnReiniciar').onclick = () => postCall('{{ route("sorteador.reiniciar", $jugadaId) }}');

    const pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
        forceTLS: window.location.protocol === 'https:',
        enabledTransports: ['ws', 'wss']
    });

    const channel = pusher.subscribe('jugada.{{ $jugadaId }}');

    channel.bind('SorteoActualizado', data => {
        // 1. Bolilla Maestra
        document.getElementById('bolillaActual').innerText = data.bolilla ?? '–';
        
        // 2. Estado
        let est = data.estado.toUpperCase();
        let col = est === 'EXTRAYENDO' ? 'var(--neon-green)' : (est === 'LINEA' || est === 'BINGO' ? 'var(--neon-red)' : '#fff');
        document.getElementById('estadoTxt').innerHTML = `<i class="bi bi-broadcast me-1"></i> ESTADO: <span style="color:${col}; font-weight:bold;">${est}</span>`;

        // 3. Matriz 1-90
        document.querySelectorAll('.matrix-num').forEach(el => {
            const n = parseInt(el.innerText);
            if (data.bolillas.includes(n)) el.classList.add('drawn');
            else el.classList.remove('drawn');
        });

        // 4. Historial (Derecha a Izquierda - visualmente rtl CSS)
        const histCont = document.getElementById('ultimas');
        histCont.innerHTML = '';
        for(let i=0; i<9; i++){
            let val = data.ultimas[i] ?? '—';
            histCont.innerHTML += `<div class="mini-orb ${val!=='—' ? 'active':''}">${val}</div>`;
        }

        // 5. Caja de Ganadores Artificial
        const wBox = document.getElementById('winnerBox');
        if (data.estado === 'linea' || data.estado === 'bingo') {
            wBox.classList.add('show');
            document.getElementById('winnerData').innerText = "ESPERANDO CONFIRMACIÓN DE VENTA...";
        } else {
            wBox.classList.remove('show');
        }
    });
</script>

</body>
</html>
