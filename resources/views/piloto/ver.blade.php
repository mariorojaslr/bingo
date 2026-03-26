@extends('dashboard')

@section('content')

<style>
/* =========================================================
   ESTÉTICA ROLLS-ROYCE OLED | VISTA DEL TUGADOR (PILOTO)
========================================================= */
body { 
    background: #020202; 
    margin: 0; 
    font-family: 'Inter', sans-serif;
    color: #fff;
}

.piloto-wrapper {
    max-width: 1600px;
    margin: 0 auto;
    padding: 1rem;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

/* ===== 1. ENCABEZADO Y CONTROLES ===== */
.piloto-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(15, 15, 20, 0.7);
    border: 1px solid rgba(255,255,255,0.05);
    border-radius: 16px;
    padding: 15px 25px;
    margin-bottom: 20px;
    backdrop-filter: blur(10px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}

.player-info {
    font-family: 'Outfit';
    font-weight: 800;
    color: var(--neon-gold, #D4AF37);
    letter-spacing: 1px;
    font-size: 1.2rem;
}

.switch-control {
    display: flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.05); padding: 5px 15px;
    border-radius: 20px; font-size: 0.9rem; border: 1px solid rgba(255,255,255,0.1);
}

/* ===== 2. LAYOUT PRINCIPAL (GRID) ===== */
.piloto-grid-top {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

@media (max-width: 992px) {
    .piloto-grid-top { grid-template-columns: 1fr; }
}

.glass-panel {
    background: rgba(15, 15, 20, 0.7);
    border: 1px solid rgba(255,255,255,0.05);
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    display: flex; flex-direction: column;
}

.panel-title {
    font-family: 'Outfit';
    font-size: 0.85rem;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-align: center;
    border-bottom: 1px dashed rgba(255,255,255,0.1);
    padding-bottom: 10px;
    margin-bottom: 20px;
}

/* Bolilla Gigante */
.orb-gigante {
    width: 160px; height: 160px; margin: 0 auto;
    background: radial-gradient(circle at 30% 30%, #00FF88, #004422);
    border-radius: 50%;
    color: #000; font-size: 75px; font-weight: 900; font-family: 'Outfit';
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 0 40px rgba(0, 255, 136, 0.4), inset -5px -5px 15px rgba(0,0,0,0.5);
    transition: 0.3s;
}
.orb-gigante.pop { animation: popOrb 0.5s ease-out; }
@keyframes popOrb { 0% { transform: scale(1); } 50% { transform: scale(1.15); box-shadow: 0 0 60px #00FF88; } 100% { transform: scale(1); } }

/* Secuencia historial */
.history-ribbon {
    display: flex; gap: 8px; justify-content: center; margin-top: 20px;
}
.history-ribbon span {
    width: 40px; height: 40px; border-radius: 50%; background: #111; border: 1px solid #333; 
    color: #fff; font-weight: bold; font-family: 'Outfit';
    display: flex; align-items: center; justify-content: center;
}
.history-ribbon span.hi { background: #00A8FF; color: #fff; border-color: transparent; box-shadow: 0 0 15px rgba(0,168,255,0.4); }

/* Streaming TV */
.tv-container {
    width: 100%; height: 100%; min-height: 350px;
    border-radius: 16px; overflow: hidden;
    background: #050505;
    border: 1px solid rgba(255,255,255,0.05);
    position: relative;
    display: flex; align-items: center; justify-content: center;
}
.tv-container iframe {
    position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;
}
.live-tag {
    position: absolute; top: 15px; right: 20px;
    background: rgba(255, 0, 85, 0.2); color: #FF0055;
    border: 1px solid #FF0055; padding: 5px 15px; border-radius: 20px;
    font-family: 'Outfit'; font-weight: 800; letter-spacing: 2px;
    font-size: 0.8rem; z-index: 10; animation: blinkLive 2s infinite;
}
@keyframes blinkLive { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

/* ===== 3. ZONA DE CARTONES (MÁXIMO 4) ===== */
.zona-cartones-title {
    font-family: 'Outfit'; font-weight: 800; font-size: 1.2rem;
    color: #fff; letter-spacing: 1px; margin-bottom: 15px;
    display: flex; align-items: center; gap: 10px;
}

.piloto-cartones-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 20px;
}

.carton-wrapper {
    background: linear-gradient(135deg, #e0e0e0, #c0c0c0);
    border-radius: 16px;
    padding: 12px;
    border: 3px solid #444;
    box-shadow: 0 15px 30px rgba(0,0,0,0.6), inset 0 0 20px rgba(0,0,0,0.1);
}

.carton-header {
    background: #111; color: #D4AF37; text-align: center;
    font-weight: 900; font-family: 'Outfit'; padding: 8px; 
    border-radius: 10px; margin-bottom: 12px;
    letter-spacing: 2px; font-size: 0.9rem;
    border: 1px solid #333;
}

.bingo-grid {
    display: grid; grid-template-columns: repeat(9, 1fr); gap: 4px;
}

.bingo-cell {
    aspect-ratio: 1; 
    background: #fff;
    display: flex; align-items: center; justify-content: center;
    font-weight: 900; font-size: 1.1rem; color: #000;
    border-radius: 6px;
    box-shadow: inset 0 -2px 0 rgba(0,0,0,0.1);
    transition: all 0.2s;
}

.bingo-empty { background: transparent; box-shadow: none; }

.bingo-hit { 
    background: #00FF88 !important; color: #000 !important; 
    box-shadow: 0 0 15px rgba(0,255,136,0.6), inset 0 -2px 0 rgba(0,0,0,0.2) !important; 
    transform: scale(1.05); z-index: 2;
}

.bingo-pendiente { 
    background: #fde047; 
    cursor: pointer;
    box-shadow: 0 0 10px rgba(253, 224, 71, 0.5);
    animation: pulseItem 1.5s infinite; 
}

@keyframes pulseItem { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }

/* ===== OVERLAYS ====== */
.overlay-cartel {
    position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 9999;
    display: flex; align-items: center; justify-content: center; flex-direction: column;
    opacity: 0; pointer-events: none; transition: 0.3s;
}
.overlay-cartel.mostrar { opacity: 1; pointer-events: all; }
.cartel-text { font-size: 8rem; font-weight: 900; font-family: 'Outfit'; animation: blink 0.5s infinite alternate;}
.linea { color: #00A8FF; text-shadow: 0 0 50px #00A8FF; }
.bingo { color: #FF0055; text-shadow: 0 0 50px #FF0055; }
@keyframes blink { from { transform: scale(0.9); } to { transform: scale(1.1); } }
</style>

@php
    $bolillasIniciales = $bolillasMarcadas ?? [];
    $streamUrl = $jugada->streaming_url ?? 'https://www.youtube.com/embed/live_stream?channel=UC4R8DWoMoI7CAwX8_LjQHig';
    
    // REGLA DE JUEGO: Limitamos visualmente a un máximo de 4 cartones simultáneos en pantalla.
    $cartonesVisibles = $cartones->take(4);
@endphp

<div class="piloto-wrapper">
    
    <!-- 1. ENCABEZADO -->
    <header class="piloto-header">
        <div class="player-info">
            <i class="bi bi-person-fill text-white-50 me-2"></i>{{ mb_strtoupper($participante->nombre) }}
        </div>
        
        <div class="d-flex gap-3">
            <div class="switch-control">
                <label for="modoAuto" class="text-white fw-bold" style="font-family: 'Outfit'; font-size: 0.8rem; letter-spacing: 1px;">MODO AUTO</label>
                <div class="form-check form-switch m-0 pt-1">
                    <input class="form-check-input" type="checkbox" id="modoAuto" checked style="background-color: var(--neon-green); border-color: var(--neon-green);">
                </div>
            </div>
            <div class="switch-control">
                <label for="sonidoOn" class="text-white-50"><i class="bi bi-volume-up-fill fs-5"></i></label>
                <div class="form-check form-switch m-0 pt-1">
                    <input class="form-check-input" type="checkbox" id="sonidoOn" checked>
                </div>
            </div>
            <a href="/salas" class="btn btn-sm btn-outline-secondary d-flex align-items-center"><i class="bi bi-door-closed me-1"></i> Salir</a>
        </div>
    </header>

    <!-- 2. ZONA SUPERIOR (BOLILLERO + TV) -->
    <div class="piloto-grid-top">
        
        <!-- Izquierda: Bolillero -->
        <div class="glass-panel text-center">
            <div class="panel-title"><i class="bi bi-diagram-3"></i> SORTEADOR</div>
            
            <div class="orb-gigante" id="bolillaActual">
                {{ $bolillaActual ?? '—' }}
            </div>
            
            <div class="history-ribbon" id="ultimos">
                @php $ultR = array_reverse(array_slice(is_array($ultimasBolillas) ? $ultimasBolillas : $ultimasBolillas->toArray(), 0, 8)); @endphp
                @foreach($ultR as $idx => $val)
                    <span class="{{ $idx === count($ultR) - 1 ? 'hi' : '' }}">{{ $val }}</span>
                @endforeach
            </div>
            
            <div class="mt-4 pt-3" style="border-top: 1px dashed rgba(255,255,255,0.05);">
                <div class="text-white-50 small" style="font-family: 'Outfit'; letter-spacing: 1px;">SALA / FRANQUICIA</div>
                <div class="text-warning fw-bold">{{ mb_strtoupper($jugada->organizador->nombre_fantasia) }}</div>
            </div>
        </div>

        <!-- Derecha: TV Transmisión -->
        <div class="glass-panel p-1">
            <div class="tv-container">
                <div class="live-tag">🔴 EN DIRECTO</div>
                <iframe src="{{ $streamUrl }}?autoplay=1&mute=0&controls=0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <!-- 3. ZONA INFERIOR (CARTONES - MAX 4) -->
    <div class="zona-cartones-title">
        <i class="bi bi-grid-3x2-gap-fill text-warning"></i> TUS CARTONES ACTIVOS (MÁX 4)
    </div>
    
    <div class="piloto-cartones-grid">
        @foreach($cartonesVisibles as $pcp)
            <div class="carton-wrapper">
                <div class="carton-header">
                    CARTÓN Nº {{ str_pad($pcp->carton->numero_carton, 6, '0', STR_PAD_LEFT) }} | S.C: {{ 1000 + $pcp->carton->numero_carton }}
                </div>
                
                <div class="bingo-grid">
                    @foreach($pcp->carton->grilla_ordenada as $fila)
                        @foreach($fila as $celda)
                            @if($celda == 0)
                                <div class="bingo-cell bingo-empty"></div>
                            @else
                                <div class="bingo-cell numero {{ in_array($celda, $bolillasIniciales) ? 'bingo-hit' : '' }}" data-numero="{{ $celda }}">
                                    {{ $celda }}
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

</div>

<!-- AUDIOS -->
<audio id="audioHit" src="/sounds/hit.mp3" preload="auto"></audio>
<audio id="audioLine" src="/sounds/linea.mp3" preload="auto"></audio>
<audio id="audioBingo" src="/sounds/bingo.mp3" preload="auto"></audio>

<!-- OVERLAYS -->
<div class="overlay-cartel" id="cartelLinea">
    <div class="cartel-text linea">¡LÍNEA!</div>
    <div class="text-white-50 mt-4 fs-4">Verificando ganadores en sala...</div>
</div>
<div class="overlay-cartel" id="cartelBingo">
    <div class="cartel-text bingo">¡BINGO!</div>
    <div class="text-white-50 mt-4 fs-4">Juego finalizado.</div>
</div>

<!-- SCRIPTS DE LÓGICA -->
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* =========================================================
       CONFIGURACIÓN DE AUTO-DABBER Y SONIDO
    ========================================================= */
    let modoAuto = document.getElementById('modoAuto').checked;
    let sonido = document.getElementById('sonidoOn').checked;

    document.getElementById('modoAuto').addEventListener('change', e => {
        modoAuto = e.target.checked;
        if(modoAuto) {
            // Convierte todos los pendientes olvidados a aciertos automáticos
            document.querySelectorAll('.bingo-pendiente').forEach(c => {
                c.classList.remove('bingo-pendiente');
                c.classList.add('bingo-hit');
            });
        }
    });
    
    document.getElementById('sonidoOn').addEventListener('change', e => sonido = e.target.checked);
    
    // Toque manual del cartón
    document.querySelectorAll('.numero').forEach(c => {
        c.addEventListener('click', function() {
            if(!modoAuto && this.classList.contains('bingo-pendiente')) {
                this.classList.remove('bingo-pendiente');
                this.classList.add('bingo-hit');
            }
        });
    });

    function playAudio(id) { 
        if(sonido) {
            let a = document.getElementById(id);
            if(a) { a.currentTime = 0; a.play().catch(()=>{}); }
        }
    }

    /* =========================================================
       CONEXIÓN PUSHER TIEMPO REAL
    ========================================================= */
    const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: window.location.protocol === 'https:',
        enabledTransports: ['ws', 'wss']
    });

    const channel = pusher.subscribe('jugada.{{ $jugada->id }}');

    channel.bind('SorteoActualizado', data => {

        // REINICIO
        if(data.bolilla === null) {
            document.getElementById('bolillaActual').innerText = '—';
            document.getElementById('ultimos').innerHTML = '';
            document.querySelectorAll('.numero').forEach(c => {
                c.classList.remove('bingo-hit', 'bingo-pendiente');
            });
            document.getElementById('cartelLinea').classList.remove('mostrar');
            document.getElementById('cartelBingo').classList.remove('mostrar');
            return;
        }

        // BOLILLA PRINCIPAL
        const bActual = document.getElementById('bolillaActual');
        bActual.innerText = data.bolilla;
        bActual.classList.remove('pop');
        void bActual.offsetWidth; // Force reflow
        bActual.classList.add('pop');

        // SECUENCIA HISTORIAL (CINTA)
        const ult = document.getElementById('ultimos');
        ult.innerHTML = '';
        data.ultimas.slice(0, 8).forEach((n, i) => {
            const s = document.createElement('span');
            s.innerText = n;
            if(i === 0) s.classList.add('hi');
            ult.appendChild(s);
        });

        // MARCADOR DE CARTONES PROPIOS (MODO AUTO VS PENDIENTE)
        document.querySelectorAll('.numero').forEach(c => {
            const n = parseInt(c.dataset.numero);
            if(modoAuto && n === data.bolilla) {
                c.classList.add('bingo-hit');
            } else if(!modoAuto && n === data.bolilla) {
                c.classList.add('bingo-pendiente');
            }
        });

        playAudio('audioHit');

        // PREMIOS
        if(data.estado === 'linea') {
            document.getElementById('cartelLinea').classList.add('mostrar');
            playAudio('audioLine');
        } else {
            document.getElementById('cartelLinea').classList.remove('mostrar');
        }
        
        if(data.estado === 'bingo') {
            document.getElementById('cartelBingo').classList.add('mostrar');
            playAudio('audioBingo');
        } else {
            document.getElementById('cartelBingo').classList.remove('mostrar');
        }
    });
});
</script>

@endsection
