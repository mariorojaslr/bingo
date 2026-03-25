<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Infinity Bingo | Transmisión Pública</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        * { box-sizing: border-box; }
        
        :root {
            --bg-deep: #020202;
            --panel: rgba(15, 15, 20, 0.65);
            --border: rgba(255, 255, 255, 0.08);
            --brand-green: #00FF88;
            --brand-blue: #00A8FF;
        }

        body {
            margin: 0;
            background: var(--bg-deep) url('https://www.transparenttextures.com/patterns/stardust.png');
            font-family: 'Inter', sans-serif;
            color: #fff;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* HEADER SUPREMO */
        .monitor-header {
            background: linear-gradient(180deg, rgba(0,0,0,0.9), transparent);
            padding: 1.5rem 3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            z-index: 10;
        }

        .logo-txt { font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 900; letter-spacing: 2px; background: linear-gradient(90deg, #fff, #999); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .live-badge { border: 1px solid var(--brand-green); color: var(--brand-green); padding: 5px 15px; border-radius: 20px; font-weight: bold; letter-spacing: 2px; animation: pulse-border 2s infinite; }
        
        @keyframes pulse-border { 0% { box-shadow: 0 0 0 0 rgba(0,255,136,0.4); } 70% { box-shadow: 0 0 0 10px rgba(0,255,136,0); } 100% { box-shadow: 0 0 0 0 rgba(0,255,136,0); } }

        /* GRID PRINCIPAL */
        .monitor-body {
            flex: 1;
            display: grid;
            grid-template-columns: 350px 1fr;
            padding: 2rem;
            gap: 2rem;
        }

        .glass-box {
            background: var(--panel);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* COLUMNA IZQUIERDA: BOLILLERO Y TABLERO */
        .orb-master {
            width: 200px;
            height: 200px;
            margin: 2rem auto;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #fff, #b3b3b3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 90px;
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            color: #000;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5), inset -10px -10px 20px rgba(0,0,0,0.2);
            transition: all 0.3s;
        }

        .orb-master.active-pop { animation: pop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes pop { 0% { transform: scale(1); box-shadow: 0 0 0 rgba(0,255,136,0); } 50% { transform: scale(1.15); box-shadow: 0 0 50px rgba(0,255,136,1); } 100% { transform: scale(1); box-shadow: 0 20px 40px rgba(0,0,0,0.5); } }

        /* TABLERO 90 NUMS */
        .matrix-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 4px;
            padding: 0 1.5rem 1.5rem 1.5rem;
            flex: 1;
            align-content: end;
        }

        .matrix-num {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.3);
            transition: all 0.3s;
        }

        .matrix-num.drawn {
            background: var(--brand-green);
            color: #000;
            border-color: #fff;
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
            transform: scale(1.1);
            z-index: 2;
        }

        /* COLUMNA DERECHA: STREAMING */
        .streaming-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.8);
            border: 1px solid var(--border);
            position: relative;
            background: #000;
        }

        iframe { width: 100%; height: 100%; border: none; }

        /* FOOTER DE HISTORIAL */
        .history-ribbon {
            background: rgba(0,0,0,0.8);
            border-top: 1px solid var(--border);
            padding: 1rem 3rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            overflow-x: hidden;
            white-space: nowrap;
        }
        
        .ribbon-label { font-family: 'Outfit'; font-weight: 800; color: var(--text-muted); letter-spacing: 2px; }
        
        .history-scroller { display: flex; gap: 15px; }

        .hist-orb {
            width: 50px; height: 50px;
            border-radius: 50%;
            background: var(--brand-blue);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 20px;
            box-shadow: 0 0 15px rgba(0, 168, 255, 0.4);
            border: 2px solid rgba(255,255,255,0.5);
        }

    </style>
</head>

@php
    $bolillas = $sorteo->getBolillas();
    $ultimas = array_slice(array_reverse($bolillas),0,15);
    $stream = $sorteo->jugada->streaming_url ?? 'https://www.youtube.com/embed/F7jzWJEIXmk';
@endphp

<body>

<!-- HEADER -->
<header class="monitor-header">
    <div class="logo-txt"><i class="bi bi-box me-3"></i>INFINITY BINGO</div>
    <div class="d-flex align-items-center gap-4">
        <div style="font-family: 'Outfit'; font-size: 1.5rem; font-weight: 600; color: #fff;">Sorteo #{{ $jugadaId }} <span style="opacity:0.3">|</span> <span style="color: var(--brand-gold);">{{ $sorteo->jugada->organizador->nombre_fantasia ?? 'EMPRESA' }}</span></div>
        <div class="live-badge"><i class="bi bi-record-circle-fill me-2"></i> EN VIVO</div>
    </div>
</header>

<!-- CUERPO PRINCIPAL -->
<div class="monitor-body">
    
    <!-- LATERAL TABLERO -->
    <div class="glass-box">
        <div class="orb-master" id="bolillaActual">
            {{ $sorteo->bolilla_actual ?? '—' }}
        </div>
        
        <h5 class="text-center mt-3 mb-4" style="font-family: 'Outfit'; letter-spacing: 5px; color: rgba(255,255,255,0.2);">TABLERO CERRADO</h5>
        
        <div class="matrix-grid">
            @for($i=1; $i<=90; $i++)
                <div class="matrix-num {{ in_array($i, $bolillas) ? 'drawn' : '' }}" id="num-{{$i}}">{{ $i }}</div>
            @endfor
        </div>
    </div>

    <!-- STREAMING -->
    <div class="streaming-container">
        <!-- El autoplay solo funciona tras interacción o con mute en la mayoría de navegadores públicos. 
             Se implementó con mute=0 bajo asunción de clic previo o bypass de kiosk -->
        <iframe src="{{ $stream }}?autoplay=1&mute=0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
    </div>

</div>

<!-- CINTA INFERIOR HISTORIAL -->
<div class="history-ribbon">
    <div class="ribbon-label">ÚLTIMAS EXTRACCIONES <i class="bi bi-chevron-double-right mx-2"></i></div>
    <div class="history-scroller" id="historialCinta">
        @foreach($ultimas as $u)
            <div class="hist-orb">{{ $u }}</div>
        @endforeach
    </div>
</div>


<!-- PUSHER ENGINE -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    const pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
        forceTLS: window.location.protocol === 'https:',
        enabledTransports: ['ws', 'wss']
    });

    const channel = pusher.subscribe('jugada.{{ $jugadaId }}');

    channel.bind('SorteoActualizado', function (data) {
        
        // 1. Bolilla Maestra Gigante
        if (data.bolilla !== null) {
            const master = document.getElementById('bolillaActual');
            master.innerText = data.bolilla;
            master.classList.remove('active-pop');
            void master.offsetWidth; // Trigger reflow
            master.classList.add('active-pop');
        }

        // 2. Tablero Matrix
        document.querySelectorAll('.matrix-num').forEach(el => {
            const n = parseInt(el.innerText);
            if(data.bolillas.includes(n)) {
                el.classList.add('drawn');
            } else {
                el.classList.remove('drawn');
            }
        });

        // 3. Cinta de Historial Inferior
        const cinta = document.getElementById('historialCinta');
        cinta.innerHTML = '';
        data.ultimas.slice(0, 15).forEach(num => {
            const orb = document.createElement('div');
            orb.className = 'hist-orb';
            orb.innerText = num;
            cinta.appendChild(orb);
        });

    });
</script>

</body>
</html>
