<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Monitor Bingo - {{ $jugada->nombre_jugada }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin:0;
            background:#0b1220;
            color:white;
            font-family: Arial, sans-serif;
            overflow:hidden;
        }

        .pantalla {
            display:grid;
            grid-template-columns: 2fr 1fr;
            grid-template-rows: auto 1fr auto;
            height:100vh;
        }

        .header {
            grid-column:1 / 3;
            background:#111827;
            padding:15px;
            text-align:center;
            font-size:28px;
            font-weight:bold;
        }

        .numero-actual {
            font-size:120px;
            text-align:center;
            color:#22c55e;
            padding:20px;
        }

        .historial {
            display:flex;
            justify-content:center;
            gap:10px;
            font-size:24px;
            min-height:40px;
        }

        .tablero {
            display:grid;
            grid-template-columns: repeat(10, 1fr);
            gap:6px;
            padding:20px;
        }

        .bola {
            background:#1f2937;
            border-radius:50%;
            padding:10px;
            text-align:center;
            font-size:20px;
            transition: all 0.3s ease;
        }

        .bola.salida {
            background: #22c55e;
            color: #022c22;
            font-weight: bold;
            transform: scale(1.1);
        }

        .panel-info {
            background:#020617;
            padding:20px;
            font-size:18px;
        }

        .footer {
            grid-column:1 / 3;
            background:#111827;
            padding:10px;
            text-align:center;
            font-size:16px;
        }
    </style>
</head>
<body>

<div class="pantalla">

    <div class="header">
        🎯 {{ $jugada->nombre_jugada }} — {{ $jugada->institucion->nombre }}
    </div>

    <div>
        <div class="numero-actual" id="numero-actual">
            {{ $numeroActual ?? '—' }}
        </div>

        <div class="historial" id="historial">
            @forelse($historial ?? [] as $h)
                <span>{{ $h }}</span>
            @empty
                <span>Esperando bolillas...</span>
            @endforelse
        </div>

        <div class="tablero" id="tablero">
            @for($i=1; $i<=90; $i++)
                <div class="bola {{ in_array($i, $historial ?? []) ? 'salida' : '' }}">
                    {{ $i }}
                </div>
            @endfor
        </div>
    </div>

    <div class="panel-info">
        <h3>📊 Información</h3>
        <p><strong>Organizador:</strong> {{ $jugada->organizador->nombre_fantasia }}</p>
        <p><strong>Cartones en juego:</strong> {{ $jugada->cantidad_cartones ?? '—' }}</p>
        <p><strong>Bolillas salidas:</strong> {{ count($historial ?? []) }}</p>
        <p><strong>Tiempo transcurrido:</strong> {{ $tiempoTranscurrido ?? '—' }}</p>
        <p><strong>Premio Línea:</strong> $ —</p>
        <p><strong>Premio Bingo:</strong> $ —</p>

        <hr>

        <h3>🏆 Eventos</h3>
        <p>Línea: —</p>
        <p>Bingo: —</p>
    </div>

    <div class="footer">
        Monitor de Sorteo — Sistema Bingo Profesional
    </div>

</div>

<script>
function actualizarMonitor() {
    fetch('/api/monitor/jugada/{{ $jugada->id }}')
        .then(r => r.json())
        .then(data => {

            // Número grande
            document.getElementById('numero-actual').innerText = data.ultima ?? '—';

            // Historial
            const hist = document.getElementById('historial');
            hist.innerHTML = '';
            if (data.bolillas.length === 0) {
                hist.innerHTML = '<span>Esperando bolillas...</span>';
            } else {
                data.bolillas.slice(-10).forEach(n => {
                    const s = document.createElement('span');
                    s.innerText = n;
                    hist.appendChild(s);
                });
            }

            // Tablero
            document.querySelectorAll('.bola').forEach(el => {
                const num = parseInt(el.innerText);
                if (data.bolillas.includes(num)) {
                    el.classList.add('salida');
                }
            });
        });
}

// Actualización automática cada 2 segundos
setInterval(actualizarMonitor, 2000);
</script>

</body>
</html>
