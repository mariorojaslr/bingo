@extends('dashboard')

@section('content')
<style>
body { background: #f3f4f6; margin:0; }

/* ===== BARRA FLOTANTE SUPERIOR ===== */
.top-bar {
    position: fixed;
    top: 0; left: 0; right: 0;
    background: #0f172a;
    color: white;
    padding: 10px 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 50;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}

.switch-group { display: flex; gap: 14px; align-items: center; }
.switch { display: flex; align-items: center; gap: 5px; font-size: 14px; }
.switch input { transform: scale(1.2); }

/* ===== CUERPO ===== */
.wrapper { padding-top: 70px; }

.bolilla-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 18px;
}

.bolilla-actual {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: radial-gradient(circle at top, #22c55e, #15803d);
    color: #ffffff;
    font-size: 76px;
    font-weight: 900;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 30px rgba(34,197,94,0.7);
}

.ultimos {
    display: flex;
    gap: 8px;
    margin-top: 10px;
}

.ultimos span {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #000;
    font-size: 22px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bingo-grid {
    display: grid;
    grid-template-columns: repeat(9, 1fr);
    gap: 4px;
}

.bingo-cell {
    aspect-ratio: 1 / 1;
    max-width: 44px;
    border-radius: 6px;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 20px;
    cursor: pointer;
}

.bingo-empty { opacity: 0.3; cursor: default; }
.bingo-hit { background:#22c55e; color:#fff; }

.bingo-pendiente {
    animation: pulse 1s infinite;
    background: #fde047;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.15); }
    100% { transform: scale(1); }
}

/* ===== CARTELES ===== */
.cartel {
    position: fixed;
    top: 35%;
    left: 0; right: 0;
    text-align: center;
    font-size: 60px;
    font-weight: bold;
    display: none;
    z-index: 100;
}

.cartel.linea { color: #2563eb; }
.cartel.bingo { color: #dc2626; }

.cartel.mostrar {
    display: block;
    animation: parpadeo 1s infinite;
}

@keyframes parpadeo {
    0% { opacity: 1; }
    50% { opacity: 0.4; }
    100% { opacity: 1; }
}
</style>

@php
    // 🔒 Estado inicial coherente (desde BD)
    $bolillasIniciales = $bolillasMarcadas ?? [];
    $ultimasIniciales  = $ultimasBolillas ?? [];
@endphp

<div class="top-bar">
    <div>{{ $participante->nombre }}</div>
    <div class="switch-group">
        <div class="switch">
            <label>Auto</label>
            <input type="checkbox" id="modoAuto">
        </div>
        <div class="switch">
            <label>🔊</label>
            <input type="checkbox" id="sonidoOn" checked>
        </div>
    </div>
</div>

<div class="cartel linea" id="cartelLinea">¡LÍNEA!</div>
<div class="cartel bingo" id="cartelBingo">¡BINGO!</div>

<div class="wrapper max-w-md mx-auto p-3">

    <div class="bolilla-wrap">
        <div class="bolilla-actual" id="bolillaActual">
            {{ $bolillaActual ?? '–' }}
        </div>

        <div class="ultimos" id="ultimos">
            @foreach($ultimasIniciales as $u)
                <span>{{ $u }}</span>
            @endforeach
        </div>
    </div>

    @foreach($cartones as $pcp)
        <div class="bg-white shadow rounded-lg p-2 mb-3 carton">
            <div class="text-xs text-gray-500 mb-1 text-center">
                Cartón Nº {{ $pcp->carton->numero_carton }}
            </div>

            <div class="bingo-grid">
                @foreach($pcp->carton->grilla as $fila)
                    @foreach($fila as $celda)
                        @if($celda == 0)
                            <div class="bingo-cell bingo-empty">♣</div>
                        @else
                            <div class="bingo-cell numero {{ in_array($celda, $bolillasIniciales) ? 'bingo-hit' : '' }}"
                                 data-numero="{{ $celda }}">
                                {{ $celda }}
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<audio id="audioHit" src="/sounds/hit.mp3"></audio>
<audio id="audioLine" src="/sounds/linea.mp3"></audio>
<audio id="audioBingo" src="/sounds/bingo.mp3"></audio>

<script src="https://js.pusher.com/8.2/pusher.min.js"></script>

<script>
let modoAuto = false;
let sonido = true;
let pendientes = [];

document.getElementById('modoAuto').addEventListener('change', e => {
    modoAuto = e.target.checked;
    limpiarPendientes();
});

document.getElementById('sonidoOn').addEventListener('change', e => {
    sonido = e.target.checked;
});

function play(id) {
    if (sonido) document.getElementById(id).play();
}

/**
 * 🔑 SINCRONIZACIÓN REAL
 * Reconstruye estado desde BD
 */
function sincronizarEstado() {
    fetch('/api/piloto/jugada/{{ $jugadaId }}')
        .then(r => r.json())
        .then(data => {

            document.getElementById('bolillaActual').innerText = data.ultima ?? '–';

            const ult = document.getElementById('ultimos');
            ult.innerHTML = '';
            data.bolillas.slice(-5).reverse().forEach(n => {
                const s = document.createElement('span');
                s.innerText = n;
                ult.appendChild(s);
            });

            document.querySelectorAll('.numero').forEach(cell => {
                const n = parseInt(cell.dataset.numero);
                if (data.bolillas.includes(n)) {
                    cell.classList.remove('bingo-pendiente');
                    cell.classList.add('bingo-hit');
                }
            });
        });
}

// ⏱️ Mantiene coherencia aunque se pierdan eventos
setInterval(sincronizarEstado, 2000);
sincronizarEstado();

/**
 * 📡 PUSHER (solo aviso inmediato)
 */
const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
    cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
    forceTLS: true
});

const channel = pusher.subscribe('jugada.{{ $jugadaId }}');

channel.bind('BolillaSorteada', function() {
    play('audioHit');
    sincronizarEstado();
});

channel.bind('LineaConfirmada', function() {
    document.getElementById('cartelLinea').classList.add('mostrar');
    play('audioLine');
});

channel.bind('BingoConfirmado', function() {
    document.getElementById('cartelBingo').classList.add('mostrar');
    play('audioBingo');
});

channel.bind('JuegoReanudado', function() {
    document.getElementById('cartelLinea').classList.remove('mostrar');
    document.getElementById('cartelBingo').classList.remove('mostrar');
    limpiarPendientes();
});

function limpiarPendientes() {
    pendientes = [];
    document.querySelectorAll('.bingo-pendiente').forEach(c => c.classList.remove('bingo-pendiente'));
}
</script>
@endsection
