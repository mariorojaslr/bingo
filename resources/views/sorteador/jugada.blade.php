<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sorteador – {{ $jugada->nombre_jugada }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            background: radial-gradient(circle at top, #0f172a, #020617);
            color: white;
            font-family: system-ui, sans-serif;
        }

        header {
            background: #020617;
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #1e293b;
        }

        .bolilla {
            margin: 20px auto;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: radial-gradient(circle at top, #22c55e, #15803d);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            font-weight: bold;
            box-shadow: 0 0 30px rgba(34,197,94,0.8);
        }

        .ultimas {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .ultimas span {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .controles {
            text-align: center;
            margin: 12px;
        }

        button {
            background: #22c55e;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            margin: 4px;
        }

        button[disabled] {
            background: #475569;
            cursor: not-allowed;
        }

        .cartel {
            display: none;
            text-align: center;
            font-size: 42px;
            font-weight: bold;
            margin-top: 15px;
        }

        .cartel.linea { color: #3b82f6; }
        .cartel.bingo { color: #ef4444; }

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
</head>
<body>

<header>
    <h3>{{ $jugada->nombre_jugada }}</h3>
    <div id="estadoTxt">Estado: {{ strtoupper($sorteo->estado) }}</div>
</header>

<div class="bolilla" id="bolillaActual">
    {{ $sorteo->bolilla_actual ?? '–' }}
</div>

<div class="ultimas" id="ultimas"></div>

<div class="cartel linea" id="cartelLinea">¡LÍNEA!</div>
<div class="cartel bingo" id="cartelBingo">¡BINGO!</div>

<div class="controles">
    <button id="btnSacar">🎯 SACAR BOLILLA</button>
    <button id="btnLinea">🟦 CONFIRMAR LÍNEA</button>
    <button id="btnBingo">🟥 CONFIRMAR BINGO</button>
    <button id="btnReiniciar" style="background:#f59e0b">🔄 REINICIAR</button>
</div>

<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
const csrf = '{{ csrf_token() }}';

function post(url) {
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf
        }
    });
}

// BOTONES
btnSacar.onclick     = () => post('{{ route("sorteador.extraer", $jugadaId) }}');
btnLinea.onclick     = () => post('{{ route("sorteador.confirmar.linea", $jugadaId) }}');
btnBingo.onclick     = () => post('{{ route("sorteador.confirmar.bingo", $jugadaId) }}');
btnReiniciar.onclick = () => post('{{ route("sorteador.reiniciar", $jugadaId) }}');

// PUSHER
const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
    cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
    forceTLS: true
});

const channel = pusher.subscribe('jugada.{{ $jugadaId }}');

channel.bind('SorteoActualizado', data => {

    bolillaActual.innerText = data.bolilla ?? '–';
    estadoTxt.innerText = 'Estado: ' + data.estado.toUpperCase();

    ultimas.innerHTML = '';
    data.ultimas.forEach(n => {
        const s = document.createElement('span');
        s.innerText = n;
        ultimas.appendChild(s);
    });

    cartelLinea.classList.toggle('mostrar', data.estado === 'linea');
    cartelBingo.classList.toggle('mostrar', data.estado === 'bingo');
});
</script>

</body>
</html>
