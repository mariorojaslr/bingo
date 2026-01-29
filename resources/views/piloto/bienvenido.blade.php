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
    width: 100%;
}

.bingo-cell {
    aspect-ratio: 1 / 1;
    width: 100%;
    max-width: 44px;
    border-radius: 6px;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 20px;
    color: #000;
}

.bingo-empty {
    font-size: 18px;
    opacity: 0.6;
}

.bingo-hit {
    background: #22c55e !important;
    color: white;
    animation: hit 0.5s ease-in-out;
}

.bingo-line {
    background: #2563eb !important;
    color: white;
    animation: lineflash 1s infinite;
}

.bingo-full {
    background: #dc2626 !important;
    color: white;
    animation: bingoflash 0.8s infinite;
}

@keyframes hit {
    0% { transform: scale(1); }
    50% { transform: scale(1.3); }
    100% { transform: scale(1); }
}

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

@media (min-width: 768px) {
    .bingo-cell { max-width: 52px; font-size: 24px; }
}
</style>

<div class="max-w-md mx-auto p-3">

    <div class="text-center mb-3">
        <h1 class="text-xl font-bold">🎯 Bingo en Vivo</h1>
        <div class="text-sm text-gray-600">{{ $participante->nombre }}</div>
    </div>

    <div class="bolilla-wrap">
        <div class="bolilla-actual" id="bolillaActual">{{ $bolillaActual }}</div>
        <div class="ultimos" id="ultimos"></div>
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
const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
    cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
});

const channel = pusher.subscribe('jugada.{{ $jugadaId }}');

channel.bind('BolillaSorteada', function(data) {

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

    const audioHit = document.getElementById('audioHit');
    audioHit.play();

    document.querySelectorAll('.numero').forEach(cell => {
        if (parseInt(cell.dataset.numero) === bolilla) {
            cell.classList.add('bingo-hit');
        }
    });
});
</script>
@endsection
