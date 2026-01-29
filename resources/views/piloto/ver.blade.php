@extends('dashboard')

@section('content')
<style>
body { background: #f3f4f6; }

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
    color: #fff;
    font-size: 76px;
    font-weight: 900;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 30px rgba(34,197,94,0.7);
    animation: pulse 1.4s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.08); }
    100% { transform: scale(1); }
}

.ultimos {
    display: flex;
    gap: 8px;
    margin-top: 10px;
}

.ultimos span {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #000;
    font-size: 25px;
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
}

.bingo-empty { opacity: 0.4; }

.bingo-hit { background:#22c55e;color:#fff; }
.bingo-line { background:#2563eb;color:#fff; animation: lineflash 1s infinite; }
.bingo-full { background:#dc2626;color:#fff; animation: bingoflash 0.8s infinite; }

@keyframes lineflash {
    0% { box-shadow: 0 0 0px #2563eb; }
    50% { box-shadow: 0 0 20px #2563eb; }
    100% { box-shadow: 0 0 0px #2563eb; }
}

@keyframes bingoflash {
    0% { box-shadow: 0 0 0px #dc2626; }
    50% { box-shadow: 0 0 25px #dc2626; }
    100% { box-shadow: 0 0 0px #dc2626; }
}
</style>

<div class="max-w-md mx-auto p-3">

    <div class="text-center mb-3">
        <h1 class="text-xl font-bold">🎯 Bingo en Vivo</h1>
        <div class="text-sm text-gray-600">{{ $participante->nombre }}</div>
    </div>

    <div class="bolilla-wrap">
        <div class="bolilla-actual" id="bolillaActual">{{ $bolillaActual }}</div>

        <div class="ultimos" id="ultimos">
            @foreach($ultimasBolillas as $u)
                <span>{{ $u }}</span>
            @endforeach
        </div>
    </div>

    @foreach($cartones as $pcp)
        <div class="bg-white shadow rounded-lg p-2 mb-3 carton" data-carton="{{ $pcp->carton->id }}">
            <div class="text-xs text-gray-500 mb-1 text-center">
                Cartón Nº {{ $pcp->carton->numero_carton }}
            </div>

            <div class="bingo-grid">
                @foreach($pcp->carton->grilla as $fila)
                    @foreach($fila as $celda)
                        @if($celda == 0)
                            <div class="bingo-cell bingo-empty">♣</div>
                        @else
                            <div class="bingo-cell numero" data-numero="{{ $celda }}">{{ $celda }}</div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="text-center mt-3 text-xs text-gray-500">
        Código: <strong>{{ $participante->codigo_acceso }}</strong>
    </div>

</div>

<audio id="audioHit" src="/sounds/hit.mp3" preload="auto"></audio>
<audio id="audioLine" src="/sounds/linea.mp3" preload="auto"></audio>
<audio id="audioBingo" src="/sounds/bingo.mp3" preload="auto"></audio>

<script src="https://js.pusher.com/8.2/pusher.min.js"></script>

<script>
// === MARCAR BOLILLAS YA SALIDAS AL CARGAR ===
const bolillasYaSalidas = @json($bolillasMarcadas);

document.querySelectorAll('.numero').forEach(cell => {
    const n = parseInt(cell.dataset.numero);
    if (bolillasYaSalidas.includes(n)) {
        cell.classList.add('bingo-hit');
    }
});

// === CONEXIÓN PUSHER REAL ===
Pusher.logToConsole = true;

const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
    cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
    forceTLS: true
});

const channel = pusher.subscribe('jugada.{{ $jugadaId }}');

// === FUNCIÓN CENTRAL DE ACTUALIZACIÓN ===
function manejarBolilla(data) {

    console.log('EVENTO RECIBIDO:', data);

    const bolilla = data.bolilla;
    const ultimas = data.ultimas;

    document.getElementById('bolillaActual').innerText = bolilla;

    const ultimosDiv = document.getElementById('ultimos');
    ultimosDiv.innerHTML = '';
    ultimas.forEach(n => {
        const s = document.createElement('span');
        s.innerText = n;
        ultimosDiv.appendChild(s);
    });

    document.querySelectorAll('.numero').forEach(cell => {
        if (parseInt(cell.dataset.numero) === bolilla) {
            cell.classList.add('bingo-hit');
            document.getElementById('audioHit').play();
        }
    });
}

// === ESCUCHAR EVENTOS (NOMBRE CORRECTO) ===
channel.bind('BolillaSorteada', manejarBolilla);
channel.bind('bolilla.sorteada', manejarBolilla); // compatibilidad

// === EVENTOS DE LÍNEA ===
channel.bind('LineaConfirmada', function(data) {
    document.querySelectorAll(`[data-carton="${data.carton_id}"] .numero`).forEach(c => {
        c.classList.add('bingo-line');
    });
    document.getElementById('audioLine').play();
});

// === EVENTOS DE BINGO ===
channel.bind('BingoConfirmado', function(data) {
    document.querySelectorAll(`[data-carton="${data.carton_id}"] .numero`).forEach(c => {
        c.classList.add('bingo-full');
    });
    document.getElementById('audioBingo').play();
});
</script>
@endsection
