@extends('dashboard')

@section('content')

<style>
/* =========================================================
   ESTILOS GENERALES
========================================================= */
body { background:#f3f4f6; margin:0; }

/* ===== TOP BAR ===== */
.top-bar{
    position:fixed; top:0; left:0; right:0;
    background:#0f172a; color:#fff;
    padding:10px 14px;
    display:flex; justify-content:space-between; align-items:center;
    z-index:50;
}
.switch-group{display:flex;gap:14px;align-items:center}
.switch{display:flex;gap:6px;font-size:14px}

/* ===== CUERPO ===== */
.wrapper{padding-top:70px}

/* ===== BOLILLA ===== */
.bolilla-actual{
    width:150px;height:150px;border-radius:50%;
    background:radial-gradient(circle at top,#22c55e,#15803d);
    color:#fff;font-size:76px;font-weight:900;
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 0 30px rgba(34,197,94,.7);
}

/* ===== ÚLTIMAS ===== */
.ultimos{display:flex;gap:8px;margin-top:10px}
.ultimos span{
    width:42px;height:42px;border-radius:50%;
    background:#e5e7eb;font-weight:bold;
    display:flex;align-items:center;justify-content:center;
}

/* ===== CARTÓN ===== */
.bingo-grid{display:grid;grid-template-columns:repeat(9,1fr);gap:4px}
.bingo-cell{
    aspect-ratio:1/1;
    border-radius:6px;
    background:#e5e7eb;
    display:flex;align-items:center;justify-content:center;
    font-weight:900;font-size:20px;
}
.bingo-empty{opacity:.3}
.bingo-hit{background:#22c55e;color:#fff}
.bingo-pendiente{
    background:#fde047;
    animation:pulse 1s infinite;
}

@keyframes pulse{
    0%{transform:scale(1)}
    50%{transform:scale(1.15)}
    100%{transform:scale(1)}
}

/* ===== CARTELES ===== */
.cartel{
    position:fixed;top:35%;left:0;right:0;
    text-align:center;font-size:60px;
    font-weight:900;display:none;z-index:200;
}
.cartel.mostrar{display:block;animation:blink 1s infinite}
.cartel.linea{color:#2563eb}
.cartel.bingo{color:#dc2626}

@keyframes blink{
    0%{opacity:1}50%{opacity:.4}100%{opacity:1}
}
</style>

@php
    // Estado inicial desde BD
    $bolillasIniciales = $bolillasMarcadas ?? [];
@endphp

<!-- ================= TOP BAR ================= -->
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

<!-- ================= CARTELES ================= -->
<div class="cartel linea" id="cartelLinea">¡LÍNEA!</div>
<div class="cartel bingo" id="cartelBingo">¡BINGO!</div>

<!-- ================= CONTENIDO ================= -->
<div class="wrapper max-w-md mx-auto p-3">

    <div class="flex flex-col items-center mb-4">
        <div class="bolilla-actual" id="bolillaActual">
            {{ $bolillaActual ?? '–' }}
        </div>
        <div class="ultimos" id="ultimos"></div>
    </div>

    @foreach($cartones as $pcp)
        <div class="bg-white shadow rounded p-2 mb-3">
            <div class="text-xs text-center mb-1">
                Cartón Nº {{ $pcp->carton->numero_carton }}
            </div>

            <div class="bingo-grid">
                @foreach($pcp->carton->grilla as $fila)
                    @foreach($fila as $celda)
                        @if($celda==0)
                            <div class="bingo-cell bingo-empty">♣</div>
                        @else
                            <div class="bingo-cell numero {{ in_array($celda,$bolillasIniciales)?'bingo-hit':'' }}"
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

<!-- ================= AUDIOS ================= -->
<audio id="audioHit" src="/sounds/hit.mp3"></audio>
<audio id="audioLine" src="/sounds/linea.mp3"></audio>
<audio id="audioBingo" src="/sounds/bingo.mp3"></audio>

<!-- ================= PUSHER ================= -->
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>

<script>
/* =========================================================
   ESTADO LOCAL
========================================================= */
let modoAuto = false;
let sonido = true;

/* =========================================================
   CONTROLES
========================================================= */
document.getElementById('modoAuto').onchange = e => modoAuto = e.target.checked;
document.getElementById('sonidoOn').onchange = e => sonido = e.target.checked;
function play(id){ if(sonido) document.getElementById(id).play(); }

/* =========================================================
   PUSHER REALTIME
========================================================= */
const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}",{
    cluster:"{{ env('PUSHER_APP_CLUSTER') }}",
    forceTLS:true
});

const channel = pusher.subscribe('jugada.{{ $jugadaId }}');

channel.bind('SorteoActualizado', data => {

    /* ===== REINICIO TOTAL ===== */
    if(data.bolilla === null){
        document.getElementById('bolillaActual').innerText = '–';
        document.getElementById('ultimos').innerHTML = '';

        document.querySelectorAll('.numero').forEach(c=>{
            c.classList.remove('bingo-hit','bingo-pendiente');
        });

        document.getElementById('cartelLinea').classList.remove('mostrar');
        document.getElementById('cartelBingo').classList.remove('mostrar');
        return;
    }

    /* ===== BOLILLA ACTUAL ===== */
    document.getElementById('bolillaActual').innerText = data.bolilla;

    /* ===== ÚLTIMAS ===== */
    const ult = document.getElementById('ultimos');
    ult.innerHTML = '';
    data.ultimas.forEach(n=>{
        const s=document.createElement('span');
        s.innerText=n;
        ult.appendChild(s);
    });

    /* ===== MARCADO ===== */
    document.querySelectorAll('.numero').forEach(c=>{
        const n = parseInt(c.dataset.numero);

        if(data.bolillas.includes(n)){
            c.classList.remove('bingo-pendiente');
            c.classList.add('bingo-hit');
        }else if(!modoAuto && n === data.bolilla){
            c.classList.add('bingo-pendiente');
        }else if(modoAuto && n === data.bolilla){
            c.classList.add('bingo-hit');
        }
    });

    play('audioHit');

    /* ===== ESTADOS ===== */
    if(data.estado==='linea'){
        document.getElementById('cartelLinea').classList.add('mostrar');
        play('audioLine');
    }

    if(data.estado==='bingo'){
        document.getElementById('cartelBingo').classList.add('mostrar');
        play('audioBingo');
    }

    if(data.estado==='en_curso'){
        document.getElementById('cartelLinea').classList.remove('mostrar');
        document.getElementById('cartelBingo').classList.remove('mostrar');
    }
});
</script>

@endsection
