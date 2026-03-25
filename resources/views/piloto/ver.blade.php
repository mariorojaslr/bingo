@extends('dashboard')

@section('content')

<style>
/* =========================================================
   ESTILOS GENERALES Y LAYOUT MÓVIL
========================================================= */
body { 
    background: #050505; 
    margin: 0; 
    font-family: 'Inter', sans-serif;
    color: #fff;
    padding-bottom: 50px;
}

/* ===== ENCABEZADO Y BOTONES DE HUNDIR ===== */
.top-portal {
    position: sticky; top: 0; left: 0; right: 0;
    background: rgba(10, 10, 10, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(255,255,255,0.1);
    z-index: 100;
    padding: 10px 15px;
}

.portal-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.player-info {
    font-family: 'Outfit';
    font-weight: 800;
    color: var(--neon-gold, #D4AF37);
    letter-spacing: 1px;
}

/* BOTONES HUNDIBLES */
.btn-hundir-group {
    display: flex;
    gap: 8px;
    justify-content: center;
    overflow-x: auto;
    padding-bottom: 5px;
}

.btn-hundir {
    background: #222;
    border: 1px solid #444;
    color: #888;
    width: 45px; height: 45px;
    border-radius: 12px;
    font-family: 'Outfit';
    font-weight: 900;
    font-size: 1.2rem;
    box-shadow: inset 0 4px 6px rgba(255,255,255,0.1), 0 4px 6px rgba(0,0,0,0.5);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
}

/* Estado activo/hundido */
.btn-hundir.active {
    background: rgba(0, 255, 136, 0.1);
    border: 1px solid var(--neon-green, #00FF88);
    color: var(--neon-green, #00FF88);
    box-shadow: inset 0 4px 8px rgba(0,0,0,0.6), 0 0 15px rgba(0,255,136,0.3);
    transform: translateY(3px); /* Hace el efecto visual de "hundirse" */
}

.btn-hundir.active.btn-bingo { border-color: #00A8FF; color: #00A8FF; box-shadow: inset 0 4px 8px rgba(0,0,0,0.6), 0 0 15px rgba(0,168,255,0.3); background: rgba(0, 168, 255, 0.1); }
.btn-hundir.active.btn-trans { border-color: #FF0055; color: #FF0055; box-shadow: inset 0 4px 8px rgba(0,0,0,0.6), 0 0 15px rgba(255,0,85,0.3); background: rgba(255, 0, 85, 0.1); }

/* CONTROLES EXTRA (Auto y Audio) */
.switch-control {
    display: flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,0.05); padding: 5px 10px;
    border-radius: 20px; font-size: 0.8rem; border: 1px solid rgba(255,255,255,0.1);
}

/* ===== CONTENEDOR DINÁMICO ===== */
.dynamic-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 15px;
    max-width: 600px;
    margin: 0 auto;
}

.module {
    display: none; /* Ocultos por defecto, activados por js */
    background: rgba(20,20,25,0.9);
    border: 1px solid rgba(255,255,255,0.05);
    border-radius: 16px;
    padding: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    animation: fadeIn 0.3s ease forwards;
}
.module.active { display: block; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

/* Títulos de módulos */
.mod-title {
    font-family: 'Outfit';
    font-size: 0.85rem;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 15px;
    text-align: center;
    border-bottom: 1px dashed rgba(255,255,255,0.1);
    padding-bottom: 8px;
}

/* ===== MÓDULOS ESPECÍFICOS ===== */
/* B: Bolillero */
.bolilla-actual {
    width: 130px; height: 130px; margin: 0 auto;
    border-radius: 50%;
    background: radial-gradient(circle at 30% 30%, #00FF88, #005522);
    color: #000; font-size: 70px; font-weight: 900; font-family: 'Outfit';
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 0 40px rgba(0, 255, 136, 0.4), inset -5px -5px 15px rgba(0,0,0,0.5);
}
.ultimos { display: flex; gap: 8px; justify-content: center; margin-top: 15px; direction: rtl; }
.ultimos span { width: 35px; height: 35px; border-radius: 50%; background: #222; border: 1px solid #444; color: #fff; font-weight: bold; display: flex; align-items: center; justify-content: center; direction: ltr; }
.ultimos span.hi { background: #00A8FF; color: #fff; border-color: transparent; }

/* M: Monitor 1-90 */
.matrix-grid { display: grid; grid-template-columns: repeat(10, 1fr); gap: 4px; }
.matrix-num { aspect-ratio: 1; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 11px; font-weight: bold; color: rgba(255,255,255,0.4); }
.matrix-num.drawn { background: #FF0055; color: #fff; }

/* T: Transmisión */
.tv-frame { width: 100%; aspect-ratio: 16/9; background: #000; border-radius: 12px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; }
.tv-frame::before { content: '🔴 LIVE STREAMING OFF'; color: #444; font-weight: bold; letter-spacing: 2px; font-size: 0.9rem; }

/* C: Cartones */
.bingo-grid { display: grid; grid-template-columns: repeat(9, 1fr); gap: 3px; background: #fff; padding: 5px; border-radius: 8px; margin-bottom: 15px;}
.bingo-cell { aspect-ratio: 3/4; background: #e0e0e0; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 16px; color: #000; }
.bingo-empty { background: #D5D5D5; color: transparent; }
.bingo-hit { background: #00FF88 !important; color: #000 !important; }
.bingo-pendiente { background: #fde047; animation: pulseItem 1s infinite; }
@keyframes pulseItem { 0% { transform: scale(1); } 50% { transform: scale(1.1); box-shadow: 0 0 10px #fde047; } 100% { transform: scale(1); } }

/* D: Datos */
.kpi-row { display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.05); padding: 10px 0; }

/* CARTELES */
.overlay-cartel {
    position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 999;
    display: flex; align-items: center; justify-content: center; flex-direction: column;
    display: none;
}
.overlay-cartel.mostrar { display: flex; }
.cartel-text { font-size: 5rem; font-weight: 900; font-family: 'Outfit'; animation: blink 0.8s infinite alternate;}
.linea { color: #00A8FF; text-shadow: 0 0 30px #00A8FF; }
.bingo { color: #FF0055; text-shadow: 0 0 30px #FF0055; }
@keyframes blink { from { transform: scale(0.9); opacity: 0.8; } to { transform: scale(1.1); opacity: 1; } }

</style>

@php
    $bolillasIniciales = $bolillasMarcadas ?? [];
@endphp

<!-- ================= ENCABEZADO Y CONTROLES ================= -->
<div class="top-portal">
    <div class="portal-nav">
        <div class="player-info"><i class="bi bi-person-circle text-white-50 me-1"></i> {{ mb_strtoupper($participante->nombre) }}</div>
        
        <div class="d-flex gap-2">
            <div class="switch-control">
                <label for="modoAuto" class="text-white-50 fw-bold">AUTO</label>
                <div class="form-check form-switch m-0 pt-1">
                    <input class="form-check-input" type="checkbox" id="modoAuto" checked style="background-color: var(--neon-green); border-color: var(--neon-green);">
                </div>
            </div>
            <div class="switch-control">
                <label for="sonidoOn" class="text-white-50"><i class="bi bi-volume-up-fill"></i></label>
                <div class="form-check form-switch m-0 pt-1">
                    <input class="form-check-input" type="checkbox" id="sonidoOn" checked>
                </div>
            </div>
        </div>
    </div>

    <!-- BOTONES DE HUNDIR (LA MAGIA VISUAL) -->
    <div class="btn-hundir-group">
        <div class="btn-hundir active" data-mod="cartones" title="Cartones">C</div>
        <div class="btn-hundir btn-bingo active" data-mod="bolillero" title="Bolillero">B</div>
        <div class="btn-hundir btn-trans active" data-mod="monitor" title="Monitor 1-90">M</div>
        <div class="btn-hundir" data-mod="transmision" title="Transmisión TV">T</div>
        <div class="btn-hundir" data-mod="datos" title="Datos de Sala">D</div>
    </div>
</div>

<!-- ================= OVERLAYS DE PREMIOS ================= -->
<div class="overlay-cartel" id="cartelLinea">
    <div class="cartel-text linea">¡LÍNEA!</div>
    <div class="text-white-50 mt-3">Validando premio en mesa...</div>
</div>
<div class="overlay-cartel" id="cartelBingo">
    <div class="cartel-text bingo">¡BINGO!</div>
    <div class="text-white-50 mt-3">Juego finalizado.</div>
</div>

<!-- ================= CONTENEDOR DINÁMICO DE MÓDULOS ================= -->
<div class="dynamic-container" id="moduleContainer">

    <!-- C: CARTONES -->
    <div class="module active" id="mod-cartones" style="order: 1;">
        <div class="mod-title"><i class="bi bi-grid-3x2"></i> TUS CARTONES OFICIALES</div>
        
        @foreach($cartones as $pcp)
            <div>
                <div class="text-white-50 text-center small mb-1">
                    CARTÓN Nº {{ str_pad($pcp->carton->numero, 6, '0', STR_PAD_LEFT) }} | S.C: {{ 1000 + $pcp->carton->numero }}
                </div>
                <!-- GRiLLA ORDENADA VERTICALMENTE (Corregida) -->
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

    <!-- B: BOLILLERO -->
    <div class="module active" id="mod-bolillero" style="order: 2;">
        <div class="mod-title"><i class="bi bi-circle"></i> EXTRACCIÓN EN VIVO</div>
        <div class="bolilla-actual" id="bolillaActual">{{ $bolillaActual ?? '—' }}</div>
        <div class="ultimos" id="ultimos">
            @for($i=0; $i<9; $i++)
                <span class="{{ isset($ultimas[$i]) ? 'hi' : '' }}">{{ $ultimas[$i] ?? '' }}</span>
            @endfor
        </div>
    </div>

    <!-- M: MONITOR -->
    <div class="module active" id="mod-monitor" style="order: 3;">
        <div class="mod-title"><i class="bi bi-grid-apps"></i> MATRIZ CENTRAL 1-90</div>
        <div class="matrix-grid">
            @for($i=1; $i<=90; $i++)
                <div class="matrix-num {{ in_array($i, $bolillasIniciales) ? 'drawn' : '' }}" id="mat-{{$i}}">{{ $i }}</div>
            @endfor
        </div>
    </div>

    <!-- T: TRANSMISION -->
    <div class="module" id="mod-transmision" style="order: 4;">
        <div class="mod-title"><i class="bi bi-camera-video text-danger"></i> TV STREAMING</div>
        <div class="tv-frame"></div>
    </div>

    <!-- D: DATOS -->
    <div class="module" id="mod-datos" style="order: 5;">
        <div class="mod-title"><i class="bi bi-bar-chart"></i> DATOS DE SALA</div>
        <div class="kpi-row">
            <span class="text-white-50">Sala / Franquicia:</span>
            <span class="text-white fw-bold">{{ mb_strtoupper($jugada->organizador->nombre_fantasia) }}</span>
        </div>
        <div class="kpi-row">
            <span class="text-white-50">Tus Cartones:</span>
            <span class="text-warning fw-bold">{{ count($cartones) }} unidades</span>
        </div>
        <div class="kpi-row border-0">
            <span class="text-white-50">Pozo Total (Estimado):</span>
            <span class="text-success fw-bold">$ {{ number_format(count($cartones) * 10 * 3500, 2) }}</span>
        </div>
    </div>

</div>

<!-- ================= AUDIOS PÚBLICOS ================= -->
<audio id="audioHit" src="/sounds/hit.mp3" preload="auto"></audio>
<audio id="audioLine" src="/sounds/linea.mp3" preload="auto"></audio>
<audio id="audioBingo" src="/sounds/bingo.mp3" preload="auto"></audio>

<!-- ================= SCRIPTS ================= -->
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* =========================================================
       MAGIA DE BOTONES HUNDIBLES Y ORDENACIÓN
    ========================================================= */
    let orderCounter = 10; // Empieza alto para agregarlos al final al encenderlos
    
    document.querySelectorAll('.btn-hundir').forEach(btn => {
        btn.addEventListener('click', function() {
            // Toglear clase visual de "hundido"
            this.classList.toggle('active');
            
            // Buscar módulo objetivo
            const targetId = 'mod-' + this.dataset.mod;
            const targetEl = document.getElementById(targetId);
            
            if (this.classList.contains('active')) {
                targetEl.classList.add('active'); // Mostrar
                targetEl.style.order = orderCounter++; // Mandar al final del flujo visible
            } else {
                targetEl.classList.remove('active'); // Ocultar
            }
        });
    });

    /* =========================================================
       ESTADO LÓGICO Y CONTROLES
    ========================================================= */
    let modoAuto = document.getElementById('modoAuto').checked;
    let sonido = document.getElementById('sonidoOn').checked;

    document.getElementById('modoAuto').addEventListener('change', e => {
        modoAuto = e.target.checked;
        if(modoAuto) {
            // Si activa auto, pintar todos los pendientes que el usuario se olvidó de tocar
            document.querySelectorAll('.bingo-pendiente').forEach(c => {
                c.classList.remove('bingo-pendiente');
                c.classList.add('bingo-hit');
            });
        }
    });
    
    document.getElementById('sonidoOn').addEventListener('change', e => sonido = e.target.checked);
    
    // Si el usuario toca un pendiente en modo MANUAL, lo marca a HIT
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
       PUSHER Y SINCRONIZACIÓN REALTIME
    ========================================================= */
    const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: window.location.protocol === 'https:',
        enabledTransports: ['ws', 'wss']
    });

    const channel = pusher.subscribe('jugada.{{ $jugadaId }}');

    channel.bind('SorteoActualizado', data => {

        // REINICIO DE TABLERO
        if(data.bolilla === null) {
            document.getElementById('bolillaActual').innerText = '–';
            document.getElementById('ultimos').innerHTML = '';
            document.querySelectorAll('.numero, .matrix-num').forEach(c => {
                c.classList.remove('bingo-hit', 'bingo-pendiente', 'drawn');
            });
            document.getElementById('cartelLinea').classList.remove('mostrar');
            document.getElementById('cartelBingo').classList.remove('mostrar');
            return;
        }

        // B: BOLILLA ACTUAL
        document.getElementById('bolillaActual').innerText = data.bolilla;

        // B: ULTIMAS
        const ult = document.getElementById('ultimos');
        ult.innerHTML = '';
        data.ultimas.forEach((n, i) => {
            const s = document.createElement('span');
            s.innerText = n;
            if(i === 0) s.classList.add('hi');
            ult.appendChild(s);
        });

        // M: MATRIZ
        const mat = document.getElementById('mat-' + data.bolilla);
        if(mat) mat.classList.add('drawn');

        // C: MARCADOR DE CARTONES PROPIOS
        document.querySelectorAll('.numero').forEach(c => {
            const n = parseInt(c.dataset.numero);
            if(modoAuto && n === data.bolilla) {
                c.classList.add('bingo-hit');
            } else if(!modoAuto && n === data.bolilla) {
                c.classList.add('bingo-pendiente');
            }
        });

        playAudio('audioHit');

        // CARTELES 
        if(data.estado === 'linea') {
            document.getElementById('cartelLinea').classList.add('mostrar');
            playAudio('audioLine');
        }
        if(data.estado === 'bingo') {
            document.getElementById('cartelBingo').classList.add('mostrar');
            playAudio('audioBingo');
        }
        if(data.estado === 'en_curso') {
            document.getElementById('cartelLinea').classList.remove('mostrar');
            document.getElementById('cartelBingo').classList.remove('mostrar');
        }
    });
});
</script>

@endsection
