<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sorteador – {{ $jugada->nombre_jugada }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: radial-gradient(circle at top, #0f172a, #020617);
            color: white;
            min-height: 100vh;
        }

        .bola {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: radial-gradient(circle at top left, #22c55e, #166534);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: bold;
            box-shadow: 0 0 25px rgba(34,197,94,.6);
        }

        .historial span {
            display: inline-block;
            background: #1e293b;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            line-height: 45px;
            text-align: center;
            margin: 4px;
        }

        .estado {
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
        }

        .aviso-linea { background:#dc2626; padding:8px; }
        .aviso-bingo { background:#f59e0b; padding:8px; color:#000; }
    </style>
</head>
<body>

<div class="container py-5 text-center">

    <h2>🎱 Sorteador</h2>
    <h4>{{ $jugada->nombre_jugada }}</h4>

    <div class="estado">
        ESTADO: {{ strtoupper(str_replace('_',' ', $sorteo->estado)) }}
    </div>

    <div class="d-flex justify-content-center my-4">
        <div class="bola">
            {{ $ultima ?? '—' }}
        </div>
    </div>

    @if($sorteo->estado == 'en_curso')
        <form method="POST" action="{{ route('sorteador.extraer', $jugada->id) }}">
            @csrf
            <button class="btn btn-success btn-lg">🎯 Sacar bolilla</button>
        </form>
    @else
        <form method="POST" action="{{ route('sorteador.continuar', $jugada->id) }}">
            @csrf
            <button class="btn btn-primary btn-lg mt-2">▶ CONTINUAR JUEGO</button>
        </form>
    @endif

    @if($sorteo->estado == 'pausa_linea')
        <div class="aviso-linea mt-3">🟥 LÍNEA COMPLETADA – Validar y pagar</div>
    @endif

    @if($sorteo->estado == 'pausa_bingo')
        <div class="aviso-bingo mt-3">🟨 BINGO COMPLETADO – Validar ganador</div>
    @endif

    <h5 class="mt-4">Bolillas salidas: {{ count($bolillas) }}</h5>

    <div class="historial mt-3">
        @foreach($bolillas as $b)
            <span>{{ $b }}</span>
        @endforeach
    </div>

</div>
</body>
</html>
