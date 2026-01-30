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
            position: sticky;
            top: 0;
            background: #020617;
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #1e293b;
            z-index: 10;
        }

        .estado {
            font-size: 12px;
            opacity: 0.8;
        }

        .bolilla {
            margin: 20px auto 10px;
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
            font-size: 18px;
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
    <div class="estado">
        Estado: {{ strtoupper($sorteo->estado) }} | {{ now()->format('d/m/Y H:i') }}
    </div>
</header>

<div class="bolilla">
    {{ $sorteo->bolilla_actual ?? '–' }}
</div>

<div class="ultimas">
    @foreach(array_slice(array_reverse($sorteo->getBolillas()), 0, 5) as $b)
        <span>{{ $b }}</span>
    @endforeach
</div>

<div class="cartel linea" id="cartelLinea">¡LÍNEA!</div>
<div class="cartel bingo" id="cartelBingo">¡BINGO!</div>

<div class="controles">

    @if($sorteo->estado === 'en_curso')
        <form method="POST" action="{{ route('sorteador.extraer', $jugadaId) }}">
            @csrf
            <button type="submit">🎯 SACAR BOLILLA</button>
        </form>

        <form method="POST" action="{{ route('sorteador.confirmar.linea', $jugadaId) }}">
            @csrf
            <input type="hidden" name="carton_id" value="0">
            <button type="submit">🟦 CONFIRMAR LÍNEA</button>
        </form>
    @endif

    @if($sorteo->estado === 'linea')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('cartelLinea').classList.add('mostrar');
            });
        </script>

        <form method="POST" action="{{ route('sorteador.reanudar', $jugadaId) }}">
            @csrf
            <button type="submit">▶ REANUDAR JUEGO</button>
        </form>
    @endif

    @if($sorteo->estado === 'en_curso')
        <form method="POST" action="{{ route('sorteador.confirmar.bingo', $jugadaId) }}">
            @csrf
            <input type="hidden" name="carton_id" value="0">
            <button type="submit">🟥 CONFIRMAR BINGO</button>
        </form>
    @endif

    @if($sorteo->estado === 'bingo')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('cartelBingo').classList.add('mostrar');
            });
        </script>

        <form method="POST" action="{{ route('sorteador.finalizar', $jugadaId) }}">
            @csrf
            <button type="submit">🏁 FINALIZAR JUGADA</button>
        </form>
    @endif

    @if($sorteo->estado === 'finalizado')
        <button disabled>✔ JUGADA FINALIZADA</button>

        <form method="POST" action="{{ route('sorteador.reiniciar', $jugadaId) }}" style="margin-top:10px;">
            @csrf
            <button type="submit" style="background:#f59e0b;">🔄 REINICIAR JUGADA</button>
        </form>
    @endif

</div>

<audio id="audioLinea" src="/sounds/linea.mp3" preload="auto"></audio>
<audio id="audioBingo" src="/sounds/bingo.mp3" preload="auto"></audio>

<script>
    @if($sorteo->estado === 'linea')
        document.getElementById('audioLinea').play();
    @endif

    @if($sorteo->estado === 'bingo')
        document.getElementById('audioBingo').play();
    @endif
</script>

</body>
</html>
