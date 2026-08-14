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
    <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">VERSIÓN 5</span>
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
        
        <button id="btnExtraerAhora" class="btn-launch mb-3" style="margin-bottom: 0.5rem;"><i class="bi bi-play-circle-fill me-1"></i> EXTRAER AHORA</button>
        
        <div class="input-group mb-4" style="box-shadow: 0 0 20px rgba(0,0,0,0.5); border-radius: 12px; overflow: hidden;">
            <input type="number" id="inputManual" class="form-control bg-dark text-white border-0 text-center fw-bold font-monospace fs-3" placeholder="Nº (1-90)" min="1" max="90" style="font-family: 'Outfit';">
            <button class="btn px-4 text-dark fw-bold fs-5" id="btnManual" style="background: var(--neon-gold); font-family: 'Outfit';"><i class="bi bi-send-check-fill"></i> ENVIAR</button>
        </div>

        <div class="d-flex gap-2 mb-3">
            <button id="btnLinea" class="btn-action linea"><i class="bi bi-pause"></i> PAUSA LÍNEA</button>
            <button id="btnReanudar" class="btn-action" style="background: #00A8FF; color: white;"><i class="bi bi-play-circle"></i> REANUDAR</button>
            <button id="btnBingo" class="btn-action bingo"><i class="bi bi-stop"></i> BINGO FINAL</button>
        </div>
        
        <!-- MÓDULO AD-SERVER -->
        <h6 class="text-white-50 fw-bold mb-3 mt-4" style="font-size: 0.8rem; letter-spacing: 2px;"><i class="bi bi-robot"></i> MODO AUTOMÁTICO (TANDAS)</h6>
        
        <div class="d-flex gap-2 mb-3">
            <select id="selectIntervalo" class="form-select bg-dark text-white border-secondary" style="width: 120px; font-weight: bold; font-family: 'Outfit';">
                <option value="3000">Cada 3s</option>
                <option value="5000" selected>Cada 5s</option>
                <option value="10000">Cada 10s</option>
                <option value="15000">Cada 15s</option>
            </select>
            <button id="btnTandas" class="btn btn-outline-info w-100 fw-bold" style="font-family: 'Outfit'; letter-spacing: 1px;"><i class="bi bi-play-btn-fill"></i> INICIAR TANDAS</button>
        </div>

        <h6 class="text-white-50 fw-bold mb-3 mt-4" style="font-size: 0.8rem; letter-spacing: 2px;"><i class="bi bi-megaphone"></i> PUBLICIDAD (TV)</h6>
        <button id="btnPublicidad" class="btn btn-outline-warning w-100 fw-bold mb-3" style="font-family: 'Outfit'; letter-spacing: 1px;"><i class="bi bi-tv"></i> LANZAR PUBLICIDAD (10s)</button>

        <button id="btnReiniciar" class="btn btn-outline-danger w-100 fw-bold mt-4" style="font-family: 'Outfit'; letter-spacing: 1px;"><i class="bi bi-arrow-counterclockwise"></i> Reiniciar Mesa</button>

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

<script>
    const csrf = '{{ csrf_token() }}';
    function postCall(url, dataPayload = null) {
        let opts = { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } };
        
        if (dataPayload) {
            opts.headers['Content-Type'] = 'application/json';
            opts.body = JSON.stringify(dataPayload);
        }
        
        fetch(url, opts)
            .then(async res => {
                if (!res.ok) {
                    let msg = res.status;
                    let isGameOverError = false;
                    try { 
                        const data = await res.json(); 
                        msg += ' - ' + (data.error || JSON.stringify(data)); 
                        if (data.error && (data.error.includes('no está en curso') || data.error.includes('finalizado'))) {
                            isGameOverError = true;
                        }
                    } catch(e){}
                    
                    if (!isGameOverError) {
                        alert('Error: ' + msg);
                    }
                    if (typeof stopAutoExtraer === 'function') stopAutoExtraer();
                } else {
                    const data = await res.json().catch(() => ({}));
                    if (data.success === false) {
                        if (data.error && data.error.includes('90 bolillas')) {
                            console.log('Límite de 90 bolillas alcanzado.');
                            if (typeof stopAutoExtraer === 'function') stopAutoExtraer();
                        } else {
                            alert('Aviso: ' + (data.error || 'Operación no completada.'));
                        }
                    }
                    if (document.getElementById('inputManual')) {
                        document.getElementById('inputManual').value = '';
                        if (!autoExtraerInterval) {
                            document.getElementById('inputManual').focus();
                        }
                    }
                }
            })
            .catch(err => {
                console.error('Fetch error:', err);
                const toast = document.createElement('div');
                toast.style.cssText = 'position:fixed; bottom:20px; left:50%; transform:translateX(-50%); background:rgba(255,0,0,0.8); color:white; padding:10px 20px; border-radius:10px; z-index:9999; font-weight:bold; pointer-events:none;';
                toast.innerText = 'Reconectando con el servidor...';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            });
    }

    // WAKE LOCK API
    let wakeLock = null;
    const requestWakeLock = async () => {
        try {
            if ('wakeLock' in navigator) {
                wakeLock = await navigator.wakeLock.request('screen');
            }
        } catch (err) { console.error(`${err.name}, ${err.message}`); }
    };
    document.addEventListener('visibilitychange', async () => {
        if (wakeLock !== null && document.visibilityState === 'visible') { requestWakeLock(); }
    });
    document.body.addEventListener('click', () => {
        if(!wakeLock) requestWakeLock();
    }, { once: true });
    requestWakeLock();

    let autoExtraerInterval = null;
    const btnExtraerAhora = document.getElementById('btnExtraerAhora');
    const btnTandas = document.getElementById('btnTandas');
    const selectIntervalo = document.getElementById('selectIntervalo');

    function toggleAutoExtraer() {
        if (autoExtraerInterval) {
            stopAutoExtraer();
        } else {
            const ms = parseInt(selectIntervalo.value);
            postCall('{{ route("sorteador.extraer", $jugadaId) }}');
            autoExtraerInterval = setInterval(() => {
                postCall('{{ route("sorteador.extraer", $jugadaId) }}');
            }, ms);
            
            btnExtraerAhora.innerHTML = '<i class="bi bi-cpu-fill me-1"></i> EXTRACCIÓN AUTOMÁTICA';
            btnExtraerAhora.style.background = '#333';
            btnExtraerAhora.style.color = '#fff';
            btnExtraerAhora.disabled = true;
            
            if (document.getElementById('inputManual')) document.getElementById('inputManual').disabled = true;
            if (document.getElementById('btnManual')) document.getElementById('btnManual').disabled = true;
            
            btnTandas.innerHTML = '<i class="bi bi-stop-btn-fill"></i> DETENER TANDAS';
            btnTandas.classList.remove('btn-outline-info');
            btnTandas.classList.add('btn-danger');
            selectIntervalo.disabled = true;
        }
    }

    function stopAutoExtraer() {
        if (autoExtraerInterval) {
            clearInterval(autoExtraerInterval);
            autoExtraerInterval = null;
            btnExtraerAhora.innerHTML = '<i class="bi bi-play-circle-fill me-1"></i> EXTRAER AHORA';
            btnExtraerAhora.style.background = 'var(--neon-green)';
            btnExtraerAhora.style.color = '#000';
            btnExtraerAhora.disabled = false;
            
            if (document.getElementById('inputManual')) document.getElementById('inputManual').disabled = false;
            if (document.getElementById('btnManual')) document.getElementById('btnManual').disabled = false;
            
            btnTandas.innerHTML = '<i class="bi bi-play-btn-fill"></i> INICIAR TANDAS';
            btnTandas.classList.remove('btn-danger');
            btnTandas.classList.add('btn-outline-info');
            selectIntervalo.disabled = false;
        }
    }

    btnTandas.onclick = toggleAutoExtraer;
    btnExtraerAhora.onclick = () => { if(!autoExtraerInterval) postCall('{{ route("sorteador.extraer", $jugadaId) }}'); };
    document.getElementById('btnManual').onclick = () => {
        let num = document.getElementById('inputManual').value;
        if(num) { stopAutoExtraer(); postCall('{{ route("sorteador.extraer", $jugadaId) }}', { numero: num }); }
    };
    document.getElementById('btnLinea').onclick = () => { stopAutoExtraer(); postCall('{{ route("sorteador.confirmar.linea", $jugadaId) }}'); };
    document.getElementById('btnReanudar').onclick = () => { 
        postCall('{{ route("sorteador.reanudar", $jugadaId) }}'); 
        document.getElementById('winnerBox').classList.remove('show');
        if (!autoExtraerInterval) toggleAutoExtraer();
    };
    document.getElementById('btnBingo').onclick = () => { stopAutoExtraer(); postCall('{{ route("sorteador.confirmar.bingo", $jugadaId) }}'); };
    document.getElementById('btnReiniciar').onclick = () => { stopAutoExtraer(); postCall('{{ route("sorteador.reiniciar", $jugadaId) }}'); };
    document.getElementById('btnPublicidad').onclick = () => {
        postCall('{{ route("sorteador.publicidad", $jugadaId) }}');
        setTimeout(() => postCall('{{ route("sorteador.reanudar", $jugadaId) }}'), 10000);
    };

    // Sincronización (Polling)
    const estadoUrl = '/api/monitor/jugada/{{ $jugadaId }}';
    function actualizarPantalla(data) {
        document.getElementById('bolillaActual').innerText = data.bolilla ?? '–';
        let est = data.estado ? data.estado.toUpperCase() : 'ESPERANDO';
        let col = est === 'EXTRAYENDO' ? 'var(--neon-green)' : (est === 'LINEA' || est === 'BINGO' ? 'var(--neon-red)' : '#fff');
        document.getElementById('estadoTxt').innerHTML = `<i class="bi bi-broadcast me-1"></i> ESTADO: <span style="color:${col}; font-weight:bold;">${est}</span>`;
        // 3. Matriz 1-90
        document.querySelectorAll('.matrix-num').forEach(el => {
            const n = parseInt(el.innerText);
            if (data.bolillas && data.bolillas.includes(n)) el.classList.add('drawn');
            else el.classList.remove('drawn');
        });
        const histCont = document.getElementById('ultimas');
        histCont.innerHTML = '';
        if (data.ultimas) {
            for(let i=0; i<9; i++){
                let val = data.ultimas[i] ?? '—';
                histCont.innerHTML += `<div class="mini-orb ${val!=='—' ? 'active':''}">${val}</div>`;
            }
        }
        const wBox = document.getElementById('winnerBox');
        if (data.ganadores && (data.ganadores.lineas.length > 0 || data.ganadores.bingos.length > 0)) {
            wBox.classList.add('show');
            let content = `<div class="d-flex align-items-center justify-content-center gap-2 mb-2"><i class="bi bi-robot fs-4" style="color: var(--neon-gold)"></i><h6 class="mb-0 fw-bold" style="color: var(--neon-gold); letter-spacing: 1px;">¡REPORTE DEL SISTEMA!</h6></div>`;
            if (data.ganadores.bingos.length > 0) {
                content += `<p class="small text-danger fw-bold mb-2">¡BINGO DETECTADO!</p>`;
                data.ganadores.bingos.forEach(g => content += `<div class="p-2 rounded bg-dark border border-danger text-center font-monospace small text-white mb-2">Cartón: ${g.numero} <br> <span class="text-danger">${g.nombre}</span></div>`);
            } else if (data.ganadores.lineas.length > 0) {
                content += `<p class="small text-info fw-bold mb-2">LÍNEA DETECTADA</p>`;
                data.ganadores.lineas.forEach(g => content += `<div class="p-2 rounded bg-dark border border-info text-center font-monospace small text-white mb-2">Cartón: ${g.numero} <br> <span class="text-info">${g.nombre}</span></div>`);
            }
            wBox.innerHTML = content;
        } else { wBox.classList.remove('show'); }
    }
    function syncEstado() {
        fetch(estadoUrl).then(r => r.json()).then(data => { if (!data.error) actualizarPantalla(data); }).catch(e => console.error(e));
    }
    setInterval(syncEstado, 2500);
    syncEstado();
</script>

</body>
</html>
