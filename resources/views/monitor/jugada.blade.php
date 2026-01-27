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
            grid-template-columns: 2.3fr 0.7fr; /* panel derecho más angosto */
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

        /* BOLILLA ACTUAL (se mantiene igual) */
        .numero-actual {
            width:180px;
            height:180px;
            margin:20px auto;
            background:#22c55e;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:96px;
            font-weight:bold;
            color:white;
            box-shadow:0 0 25px rgba(34,197,94,0.8);
        }

        .historial {
            display:flex;
            justify-content:center;
            gap:14px;
            font-size:36px;
            min-height:60px;
            margin-bottom:10px;
        }

        .historial span {
            background:#1e293b;
            border-radius:50%;
            width:56px;
            height:56px;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .tablero {
            display:grid;
            grid-template-columns: repeat(10, 1fr);
            gap:14px;
            padding:20px;
        }

        /* BOLILLAS +40% */
        .bola {
            width:64px;
            height:64px;
            background:#1f2937;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:28px;
            transition: all 0.3s ease;
        }

        .bola.salida {
            background: #22c55e;
            color: #022c22;
            font-weight: bold;
            transform: scale(1.12);
        }

        .columna-derecha {
            display:flex;
            flex-direction:column;
            height:100%;
        }

        /* PANEL ROJO ACHICADO */
        .panel-sorteo {
            background:#b00000;
            padding:10px;
            border-bottom:2px solid #ff4d4d;
        }

        .panel-sorteo h2 {
            margin:0 0 6px 0;
            text-align:center;
            font-size:16px;
            letter-spacing:1px;
        }

        .panel-sorteo .evento {
            font-size:16px;
            font-weight:bold;
            text-align:center;
            color:#ffe600;
            margin-bottom:4px;
        }

        .panel-sorteo .datos {
            font-size:12px;
            text-align:center;
            line-height:1.4;
        }

        .panel-info {
            background:#020617;
            padding:15px;
            font-size:16px;
            flex:1;
        }

        .panel-futuro {
            background:#020617;
            padding:10px;
            font-size:14px;
            text-align:center;
            opacity:0.4;
        }

        .footer {
            grid-column:1 / 3;
            background:#111827;
            padding:10px;
            text-align:center;
            font-size:16px;
        }

        .overlay {
            position: fixed;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:rgba(0,0,0,0.75);
            display:flex;
            align-items:center;
            justify-content:center;
            z-index:9998;
            opacity:0;
            pointer-events:none;
            transition: opacity 0.5s ease;
        }

        .overlay.activo {
            opacity:1;
            pointer-events:auto;
        }

        .overlay-texto {
            font-size:140px;
            font-weight:bold;
            color:#22c55e;
            text-shadow: 0 0 20px rgba(34,197,94,0.8);
        }

        .overlay-texto.bingo {
            color:#facc15;
        }
    </style>
</head>
<body>

<div id="overlay-linea" class="overlay">
    <div class="overlay-texto">¡LÍNEA!</div>
</div>

<div id="overlay-bingo" class="overlay">
    <div class="overlay-texto bingo">¡BINGO!</div>
</div>

<div class="pantalla">

    <div class="header">
        🎯 {{ $jugada->nombre_jugada }} — {{ $jugada->institucion->nombre }}
    </div>

    <div>
        <div class="numero-actual" id="numero-actual">{{ $numeroActual ?? '—' }}</div>
        <div class="historial" id="historial"></div>

        <div class="tablero" id="tablero">
            @for($i=1; $i<=90; $i++)
                <div class="bola">{{ $i }}</div>
            @endfor
        </div>
    </div>

    <div class="columna-derecha">

        <div class="panel-sorteo">
            <h2>INFORMACIÓN DEL SORTEO</h2>
            <div class="evento" id="estado-texto">EN JUEGO</div>
            <div class="datos">
                Bolillas sorteadas: <span id="total-bolillas">0</span>
            </div>
        </div>

        <div class="panel-info">
            <h3>📊 Información Técnica</h3>
            <p><strong>Organizador:</strong> {{ $jugada->organizador->nombre_fantasia }}</p>
            <p><strong>Cartones en juego:</strong> {{ $jugada->cantidad_cartones ?? '—' }}</p>
            <p><strong>Premio Línea:</strong> $ —</p>
            <p><strong>Premio Bingo:</strong> $ —</p>
        </div>

        <div class="panel-futuro">(Espacio reservado para información futura)</div>

    </div>

    <div class="footer">Monitor de Sorteo — Sistema Bingo Profesional</div>
</div>

<script>
function actualizarMonitor() {
    fetch('/api/monitor/jugada/{{ $jugada->id }}')
        .then(r => r.json())
        .then(data => {

            document.getElementById('numero-actual').innerText = data.ultima ?? '—';
            document.getElementById('total-bolillas').innerText = data.bolillas.length;

            const hist = document.getElementById('historial');
            hist.innerHTML = '';
            data.bolillas.slice(-10).forEach(n => {
                const s = document.createElement('span');
                s.innerText = n;
                hist.appendChild(s);
            });

            document.querySelectorAll('.bola').forEach(el => {
                const num = parseInt(el.innerText);
                if (data.bolillas.includes(num)) el.classList.add('salida');
            });

            const estado = document.getElementById('estado-texto');

            if (data.estado === 'pausa_linea') {
                estado.innerText = 'LÍNEA COMPLETADA';
                document.getElementById('overlay-linea').classList.add('activo');
            } else {
                document.getElementById('overlay-linea').classList.remove('activo');
            }

            if (data.estado === 'pausa_bingo') {
                estado.innerText = 'BINGO COMPLETADO';
                document.getElementById('overlay-bingo').classList.add('activo');
            } else {
                document.getElementById('overlay-bingo').classList.remove('activo');
            }

            if (data.estado === 'en_curso') {
                estado.innerText = 'EN JUEGO';
            }
        });
}

setInterval(actualizarMonitor, 2000);
</script>

</body>
</html>
