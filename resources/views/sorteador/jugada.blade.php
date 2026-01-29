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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: system-ui, sans-serif;
        }

        .bola {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle at top, #22c55e, #15803d);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 96px;
            font-weight: 900;
            box-shadow: 0 0 40px rgba(34,197,94,.8);
            margin-bottom: 20px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.08); }
            100% { transform: scale(1); }
        }

        .btn {
            background: #22c55e;
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            margin: 12px 0;
        }

        .ultimas {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 12px;
        }

        .ultimas span {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #1f2933;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="bola" id="bolillaActual">
        {{ $sorteo?->bolilla_actual ?? '—' }}
    </div>

    <form method="POST" action="{{ route('sorteador.extraer', $jugada->id) }}">
        @csrf
        <button class="btn">SACAR BOLILLA</button>
    </form>

    <div class="ultimas" id="ultimas">
        @if($sorteo && $sorteo->bolillas_sacadas)
            @foreach(array_reverse(array_slice($sorteo->bolillas_sacadas, -15)) as $b)
                <span>{{ $b }}</span>
            @endforeach
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <script>
        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true
        });

        Echo.channel('jugada.{{ $jugada->id }}')
            .listen('.bolilla.sorteada', (e) => {
                document.getElementById('bolillaActual').innerText = e.bolilla;

                const ultimas = document.getElementById('ultimas');
                ultimas.innerHTML = '';
                e.ultimas.forEach(n => {
                    const s = document.createElement('span');
                    s.innerText = n;
                    ultimas.appendChild(s);
                });
            });
    </script>

</body>
</html>
