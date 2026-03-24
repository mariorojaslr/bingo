<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Monitor TV Bingo</title>

<style>
*{box-sizing:border-box}
body{
    margin:0;
    background:#0b0b0b;
    font-family:Segoe UI,Tahoma,sans-serif;
    color:#e8e0b8;
}
.monitor{
    display:grid;
    grid-template-columns:45% 55%;
    gap:20px;
    padding:20px;
    height:100vh;
}
.col-left,.col-right{display:grid;gap:20px}
.col-left{grid-template-rows:42% 58%}
.col-right{grid-template-rows:52% 48%}
.panel{
    border:4px solid #e8e0b8;
    border-radius:16px;
    padding:16px;
    position:relative;
}

/* ================= BOLILLA ================= */
.sorteador{display:flex;flex-direction:column;height:100%}
.bolilla-grande{
    margin:auto;
    width:180px;height:180px;border-radius:50%;
    background:radial-gradient(circle at top left,#00ff9c,#009e6a);
    display:flex;align-items:center;justify-content:center;
    font-size:96px;font-weight:900;color:#003b26;
    border:6px solid #fff;
    box-shadow:inset -10px -10px 20px rgba(0,0,0,.35),
               inset 8px 8px 14px rgba(255,255,255,.25),
               0 14px 28px rgba(0,255,156,.6);
}
.ultimas{
    margin-top:auto;
    display:grid;
    grid-template-columns:repeat(9,1fr);
    gap:8px;
}
.mini{
    text-align:center;padding:8px 0;
    border-radius:8px;border:2px solid #444;
    background:#111;color:#777;font-weight:bold;
}
.mini.activa{background:#00ff9c;color:#000;border-color:#fff}

/* ================= TABLERO ================= */
.tablero h3{text-align:center;margin:0 0 10px;letter-spacing:2px}
.grid90{
    display:grid;
    grid-template-columns:repeat(10,1fr);
    gap:6px;
}
.num{
    padding:6px 0;text-align:center;border-radius:6px;
    border:1px solid #333;background:#111;color:#666;
    font-size:13px;font-weight:bold;
}
.num.activo{
    background:#00ff9c;color:#000;border-color:#fff;
    box-shadow:0 0 6px rgba(0,255,156,.6);
}

/* ================= VIDEO ================= */
.video iframe{width:100%;height:100%;border:none}
.controles-video{
    position:absolute;bottom:14px;left:50%;
    transform:translateX(-50%);
    display:flex;gap:24px;
    background:rgba(0,0,0,.55);
    padding:8px 16px;border-radius:24px;
}
.control{display:flex;align-items:center;gap:8px;color:#fff;font-size:12px}
.switch input{display:none}
.slider{
    width:40px;height:18px;background:#555;border-radius:18px;
    position:relative;cursor:pointer;
}
.slider:before{
    content:'';width:14px;height:14px;background:#fff;
    border-radius:50%;position:absolute;top:2px;left:2px;
    transition:.3s;
}
input:checked + .slider{background:#00c853}
input:checked + .slider:before{transform:translateX(20px)}

/* ================= DATOS ================= */
.datos{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    grid-template-rows:repeat(2,1fr);
    gap:14px;
}
.dato{
    border:2px solid #e8e0b8;border-radius:12px;
    text-align:center;padding:12px;
}
.dato label{font-size:12px;opacity:.7}
.dato strong{display:block;font-size:22px;margin-top:6px}
.vivo{color:#00ff9c}
</style>
</head>

@php
    $bolillas = $sorteo->getBolillas();
    $ultimas = array_slice(array_reverse($bolillas),0,9);
    $stream = $sorteo->jugada->streaming_url ?? 'https://www.youtube.com/embed/F7jzWJEIXmk';
@endphp

<body>

<div class="monitor">

<!-- IZQUIERDA -->
<div class="col-left">

<div class="panel sorteador">
    <div class="bolilla-grande">{{ $sorteo->bolilla_actual ?? '—' }}</div>

    <div class="ultimas">
        @for($i=0;$i<9;$i++)
            <div class="mini {{ isset($ultimas[$i]) ? 'activa' : '' }}">
                {{ $ultimas[$i] ?? '—' }}
            </div>
        @endfor
    </div>
</div>

<div class="panel tablero">
<h3>NÚMEROS 1 AL 90</h3>
<div class="grid90">
@for($i=1;$i<=90;$i++)
<div class="num {{ in_array($i,$bolillas) ? 'activo' : '' }}">{{ $i }}</div>
@endfor
</div>
</div>

</div>

<!-- DERECHA -->
<div class="col-right">

<div class="panel video">

<div class="controles-video">
    <div class="control">
        <span>VIDEO</span>
        <label class="switch">
            <input type="checkbox" id="videoToggle" checked>
            <span class="slider"></span>
        </label>
    </div>
    <div class="control">
        <label class="switch">
            <input type="checkbox" id="audioToggle" checked>
            <span class="slider"></span>
        </label>
        <span>AUDIO</span>
    </div>
</div>

<iframe id="frame"
src="{{ $stream }}?autoplay=1&mute=0"
allow="autoplay" allowfullscreen></iframe>

</div>

<div class="panel datos">
<div class="dato"><label>Estado</label><strong class="vivo">{{ strtoupper($sorteo->estado) }}</strong></div>

<div class="dato"><label>Jugadores</label><strong>—</strong></div>

<div class="dato"><label>Bolillas</label><strong>{{ count($bolillas) }}</strong></div>
<div class="dato"><label>Jugada</label><strong>#{{ $jugadaId }}</strong></div>
<div class="dato"><label>Organizador</label><strong>{{ $sorteo->jugada->organizador->nombre ?? '—' }}</strong></div>
<div class="dato"><label>Institución</label><strong>{{ $sorteo->jugada->institucion->nombre ?? '—' }}</strong></div>
<div class="dato"><label>Provincia</label><strong>{{ $sorteo->jugada->provincia ?? '—' }}</strong></div>
<div class="dato"><label>Ciudad</label><strong>{{ $sorteo->jugada->ciudad ?? '—' }}</strong></div>
</div>

</div>
</div>

<!-- VIDEO CONTROLS -->
<script>
const v=document.getElementById('videoToggle');
const a=document.getElementById('audioToggle');
const f=document.getElementById('frame');
const BASE="{{ $stream }}?autoplay=1";
function updateVideo(){
    if(!v.checked){f.src="";return;}
    f.src=BASE+"&mute="+(a.checked?0:1);
}
v.onchange=updateVideo;
a.onchange=updateVideo;
</script>

<!-- PUSHER -->

<!-- Pusher -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<script>
    // 🔍 Activar logs SOLO para pruebas
    Pusher.logToConsole = true;

    // 📌 ID de la jugada (viene desde Laravel)
    const JUGADA_ID = {{ $jugadaId }};

    // ⚙️ Configuración correcta para LOCAL y PRODUCCIÓN
    const pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
        forceTLS: window.location.protocol === 'https:',
        enabledTransports: ['ws', 'wss']
    });

    // 📡 Canal por jugada
    const channel = pusher.subscribe('jugada.' + JUGADA_ID);

    // 🔔 Evento principal
    channel.bind('SorteoActualizado', function (data) {
        console.log('Evento recibido:', data);

        // 🎱 Bolilla grande
        if (data.bolilla !== null) {
            const big = document.querySelector('.bolilla-grande');
            if (big) big.innerText = data.bolilla;
        }

        // 🔢 Últimas 9 bolillas
        const minis = document.querySelectorAll('.mini');
        minis.forEach((el, i) => {
            const v = data.ultimas[i] ?? '—';
            el.innerText = v;
            el.classList.toggle('activa', v !== '—');
        });

        // 🔲 Tablero 1–90
        document.querySelectorAll('.num').forEach(el => {
            const n = Number(el.innerText);
            el.classList.toggle('activo', data.bolillas.includes(n));
        });
    });
</script>



</body>
</html>
