<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Sorteador Extraterrestre | {{ $jugada->nombre_jugada }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-dark: #020202;
            --bg-panel: rgba(15, 15, 20, 0.6);
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
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: radial-gradient(circle at 50% -20%, rgba(0, 168, 255, 0.15) 0%, transparent 60%);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, .brand-font { font-family: 'Outfit', sans-serif; }

        .dashboard-container {
            width: 100%;
            max-width: 1200px;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .glass-panel {
            background: var(--bg-panel);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        /* BOLILLA PRINCIPAL */
        .bolilla-orb {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #00FF88, #006633);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 110px;
            font-weight: 900;
            font-family: 'Outfit', sans-serif;
            color: #000;
            box-shadow: 0 0 50px rgba(0, 255, 136, 0.6), inset -15px -15px 30px rgba(0,0,0,0.5);
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.5);
            margin: 20px 0;
            position: relative;
            z-index: 10;
        }

        /* HISTORIAL (ÚLTIMAS) */
        .history-container {
            display: flex;
            gap: 10px;
            margin-top: auto;
            width: 100%;
            justify-content: center;
        }

        .mini-orb {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #111;
            border: 2px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            color: #777;
        }

        .mini-orb.active {
            background: var(--neon-blue);
            color: #fff;
            border-color: #fff;
            box-shadow: 0 0 15px rgba(0, 168, 255, 0.5);
        }

        /* CONTROLES (OPERADOR) */
        .controls-panel {
            justify-content: center;
            align-items: stretch;
            gap: 1.5rem;
        }

        .action-btn {
            width: 100%;
            padding: 20px;
            border-radius: 16px;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.2rem;
            letter-spacing: 2px;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
        }

        .btn-sacar { background: var(--neon-green); color: #000; box-shadow: 0 10px 20px rgba(0, 255, 136, 0.3); }
        .btn-sacar:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0, 255, 136, 0.5); background: #fff; }
        .btn-sacar:active { transform: translateY(2px); }

        .btn-linea { background: transparent; color: var(--neon-blue); border: 2px solid var(--neon-blue); }
        .btn-linea:hover { background: var(--neon-blue); color: #fff; box-shadow: 0 0 20px rgba(0, 168, 255, 0.4); }

        .btn-bingo { background: transparent; color: var(--neon-red); border: 2px solid var(--neon-red); }
        .btn-bingo:hover { background: var(--neon-red); color: #fff; box-shadow: 0 0 20px rgba(255, 71, 87, 0.4); }

        .btn-reset { background: transparent; color: var(--neon-gold); border: 1px solid var(--border-glass); font-size: 1rem; padding: 15px; }
        .btn-reset:hover { background: rgba(212, 175, 55, 0.1); border-color: var(--neon-gold); }

        /* CARTELES ANIMADOS */
        .hologram-sign {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.5);
            font-size: 150px;
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            text-transform: uppercase;
            opacity: 0;
            pointer-events: none;
            z-index: 100;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-shadow: 0 0 50px currentColor;
        }

        .hologram-sign.show.linea { color: var(--neon-blue); opacity: 1; transform: translate(-50%, -50%) scale(1); animation: blink 0.5s infinite alternate; }
        .hologram-sign.show.bingo { color: var(--neon-red); opacity: 1; transform: translate(-50%, -50%) scale(1); animation: blink 0.3s infinite alternate; }

        @keyframes blink { from { opacity: 1; filter: brightness(1.5); } to { opacity: 0.8; filter: brightness(0.8); } }
        
        @media (max-width: 900px) {
            .dashboard-container { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="hologram-sign linea" id="cartelLinea">LÍNEA</div>
<div class="hologram-sign bingo" id="cartelBingo">BINGO</div>

<div class="dashboard-container">
    
    <!-- PANEL VISUAL -->
    <div class="glass-panel">
        <div class="w-100 d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 fw-bold" style="color: var(--neon-gold);">{{ mb_strtoupper($jugada->nombre_jugada) }}</h4>
                <p class="text-muted small mb-0">MONITOR OFICIAL DE OPERACIONES</p>
            </div>
            <div class="badge border py-2 px-3" style="background: rgba(255,255,255,0.05); border-color: var(--border-glass) !important;" id="estadoTxt">
                ESTADO: <span class="text-white">{{ strtoupper($sorteo->estado) }}</span>
            </div>
        </div>

        <div class="bolilla-orb" id="bolillaActual">
            {{ $sorteo->bolilla_actual ?? '–' }}
        </div>
        
        <p class="text-muted text-uppercase mt-4 mb-3" style="letter-spacing: 5px; font-size: 0.8rem;">Historial del Bolillero</p>
        
        <div class="history-container" id="ultimas">
            <!-- Las ultimas 7 bolillas se inyectan via JS -->
            @for($i=0; $i<7; $i++)
                <div class="mini-orb">—</div>
            @endfor
        </div>
    </div>

    <!-- PANEL OPERATIVO -->
    <div class="glass-panel controls-panel">
        <h3 class="w-100 text-center fw-bold text-white mb-2">PANEL DE CONTROL</h3>
        <p class="text-white-50 text-center small mb-4">Transmisión Criptográfica en Tiempo Real</p>

        <button id="btnSacar" class="action-btn btn-sacar mt-2">
            <i class="bi bi-play-circle-fill"></i> Extraer Bolilla
        </button>

        <div class="d-flex w-100 gap-3 mt-4">
            <button id="btnLinea" class="action-btn btn-linea w-50" style="padding: 15px; font-size: 1rem;">
                <i class="bi bi-pause-fill"></i> Pausa por Línea
            </button>
            <button id="btnBingo" class="action-btn btn-bingo w-50" style="padding: 15px; font-size: 1rem;">
                <i class="bi bi-stop-fill"></i> Cortar Bingo
            </button>
        </div>

        <button id="btnReiniciar" class="action-btn btn-reset mt-auto mb-0">
            <i class="bi bi-arrow-counterclockwise"></i> Reiniciar Mesa y Purgar Tubo
        </button>
    </div>

</div>

<!-- SCRIPT ENGINE -->
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
    const csrf = '{{ csrf_token() }}';
    function postCall(url) {
        fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf } });
    }

    // DISPARADORES
    document.getElementById('btnSacar').onclick     = () => postCall('{{ route("sorteador.extraer", $jugadaId) }}');
    document.getElementById('btnLinea').onclick     = () => postCall('{{ route("sorteador.confirmar.linea", $jugadaId) }}');
    document.getElementById('btnBingo').onclick     = () => postCall('{{ route("sorteador.confirmar.bingo", $jugadaId) }}');
    document.getElementById('btnReiniciar').onclick = () => postCall('{{ route("sorteador.reiniciar", $jugadaId) }}');

    // PUSHER REALTIME V2
    const pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
        forceTLS: window.location.protocol === 'https:',
        enabledTransports: ['ws', 'wss']
    });

    const channel = pusher.subscribe('jugada.{{ $jugadaId }}');

    channel.bind('SorteoActualizado', data => {
        // Actualizar Orbe Central
        const bolillaActual = document.getElementById('bolillaActual');
        bolillaActual.innerText = data.bolilla ?? '–';
        
        // Animacion Pop
        bolillaActual.style.transform = 'scale(1.1)';
        bolillaActual.style.boxShadow = '0 0 100px rgba(0, 255, 136, 1)';
        setTimeout(() => {
            bolillaActual.style.transform = 'scale(1)';
            bolillaActual.style.boxShadow = '0 0 50px rgba(0, 255, 136, 0.6), inset -15px -15px 30px rgba(0,0,0,0.5)';
        }, 200);

        // Actualizar Estado
        document.getElementById('estadoTxt').innerHTML = `ESTADO: <span class="${data.estado === 'extrayendo' ? 'text-success' : 'text-danger'} fw-bold">${data.estado.toUpperCase()}</span>`;

        // Historial
        const ultimasCont = document.getElementById('ultimas');
        ultimasCont.innerHTML = '';
        for(let i=0; i<7; i++){
            let span = document.createElement('div');
            span.className = 'mini-orb ' + (data.ultimas[i] ? 'active' : '');
            span.innerText = data.ultimas[i] ?? '—';
            ultimasCont.appendChild(span);
        }

        // Hologramas Gigantes de Estado
        document.getElementById('cartelLinea').classList.toggle('show', data.estado === 'linea');
        document.getElementById('cartelBingo').classList.toggle('show', data.estado === 'bingo');
    });
</script>

</body>
</html>
