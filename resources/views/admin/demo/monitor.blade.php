<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Común - Infinity Bingo PRO</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #000;
            color: #fff;
            overflow: hidden; /* Prevent scroll on projector */
        }
        .main-container {
            display: flex;
            height: 100vh;
            padding: 20px;
            gap: 20px;
        }
        .left-panel {
            flex: 0 0 35%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .right-panel {
            flex: 1;
            background: #111;
            border-radius: 20px;
            padding: 20px;
            border: 1px solid #333;
        }
        .last-number-box {
            background: radial-gradient(circle, #004d40 0%, #00251a 100%);
            border: 2px solid #00bfa5;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            box-shadow: 0 0 40px rgba(0, 191, 165, 0.2);
        }
        .last-number {
            font-size: 15rem;
            font-weight: 900;
            line-height: 1;
            color: #00bfa5;
            text-shadow: 0 0 20px rgba(0, 191, 165, 0.5);
        }
        .last-number-label {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-bottom: -20px;
            z-index: 2;
        }
        .history-box {
            background: #111;
            border-radius: 20px;
            padding: 20px;
            border: 1px solid #333;
            height: 150px;
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .history-ball {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #222;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            border: 2px solid #444;
            color: #888;
        }
        
        .grid-90 {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            grid-template-rows: repeat(9, 1fr);
            gap: 10px;
            height: 100%;
        }
        .grid-cell {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: #444;
            transition: all 0.3s ease;
        }
        .grid-cell.active {
            background: #00bfa5;
            color: #000;
            border-color: #00fff5;
            box-shadow: 0 0 15px rgba(0, 191, 165, 0.8);
            transform: scale(1.05);
        }

        /* Overlays */
        #takeover-ad, #winner-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background: rgba(0,0,0,0.95);
        }
        .winner-text {
            font-size: 8rem;
            font-weight: 900;
            color: #ffd700;
            text-transform: uppercase;
            text-shadow: 0 0 40px rgba(255, 215, 0, 0.6);
            animation: pulse 1s infinite alternate;
        }
        @keyframes pulse {
            from { transform: scale(1); }
            to { transform: scale(1.05); }
        }
    </style>
</head>
<body>

<div class="main-container">
    <!-- Panel Izquierdo: Bolilla actual e Historial -->
    <div class="left-panel">
        <div class="last-number-box" id="last-number-box">
            <div class="last-number-label">Última Bolilla</div>
            <div class="last-number" id="last-number">--</div>
        </div>
        
        <div class="history-box" id="history-box">
            <!-- Se llenará con JS -->
        </div>
    </div>

    <!-- Panel Derecho: Grilla de 90 -->
    <div class="right-panel">
        <div class="grid-90" id="grid-90">
            @for($i = 1; $i <= 90; $i++)
                <div class="grid-cell" id="cell-{{ $i }}">{{ $i }}</div>
            @endfor
        </div>
    </div>
</div>

<!-- Overlay Ganador -->
<div id="winner-overlay">
    <div class="winner-text" id="winner-type">¡GANADOR!</div>
</div>

<!-- Overlay Publicidad -->
<div id="takeover-ad">
    <img src="https://via.placeholder.com/1920x1080/000000/00bfa5?text=ESPACIO+PUBLICITARIO" style="max-width:100%; max-height:100%;">
</div>

<!-- Onboarding Modal -->
<div class="modal fade" id="onboardingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark border-secondary text-white">
      <div class="modal-body text-center p-5">
        <h2 class="mb-4 text-info" style="font-weight: 900;">MONITOR COMÚN - MODO PROYECTOR</h2>
        <p class="fs-5 mb-4">Este monitor está diseñado para conectarse a una <strong>pantalla gigante o proyector</strong> en el salón de eventos. Reaccionará en <em>tiempo real</em> a cada orden que des desde el Sorteador Remoto (tu celular).</p>
        <p class="text-muted mb-4"><small>Tip: Presiona F11 para poner el navegador en pantalla completa y ocultar las barras.</small></p>
        <button type="button" class="btn btn-info btn-lg fw-bold px-5" data-bs-dismiss="modal">Entendido, abrir Monitor</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Vite para compilar assets (Echo y Pusher) -->
@vite(['resources/css/app.css', 'resources/js/app.js'])

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Show onboarding
        const onboardingModal = new bootstrap.Modal(document.getElementById('onboardingModal'));
        onboardingModal.show();

        // Inicializar la grilla si hay datos precargados (se mandarán desde el controller si recarga)
        const bolillas = @json($sorteo->getBolillas() ?? []);
        const estado = "{{ $sorteo->estado ?? 'en_curso' }}";

        function renderizar(bolillas) {
            if(bolillas.length > 0) {
                const ultima = bolillas[bolillas.length - 1];
                document.getElementById('last-number').innerText = ultima;
                
                // Pintar grilla
                bolillas.forEach(num => {
                    const cell = document.getElementById('cell-' + num);
                    if(cell && !cell.classList.contains('active')) {
                        cell.classList.add('active');
                    }
                });

                // Historial (ultimas 9 sin contar la actual)
                const historial = bolillas.slice(0, -1).slice(-9).reverse();
                const historyBox = document.getElementById('history-box');
                historyBox.innerHTML = '';
                historial.forEach(num => {
                    historyBox.innerHTML += `<div class="history-ball">${num}</div>`;
                });
            }
        }

        renderizar(bolillas);

        // 📡 WebSockets: Escuchar el evento SorteoActualizado
        setTimeout(() => {
            if(window.Echo) {
                window.Echo.channel('jugada.{{ $jugada->id ?? 1 }}')
                    .listen('.SorteoActualizado', (e) => {
                        console.log("Evento recibido:", e);
                        
                        // Actualizar bolillas
                        renderizar(e.bolillas);

                        // Manejar estados (Línea, Bingo)
                        const winnerOverlay = document.getElementById('winner-overlay');
                        if(e.estado === 'linea') {
                            document.getElementById('winner-type').innerText = '¡HAY LÍNEA!';
                            winnerOverlay.style.display = 'flex';
                        } else if(e.estado === 'bingo') {
                            document.getElementById('winner-type').innerText = '¡BINGO!';
                            winnerOverlay.style.display = 'flex';
                        } else if(e.estado === 'publicidad') {
                            document.getElementById('takeover-ad').style.display = 'flex';
                        } else {
                            // en_curso (Ocultar overlays)
                            winnerOverlay.style.display = 'none';
                            document.getElementById('takeover-ad').style.display = 'none';
                        }
                    });
            }
        }, 1000);
    });
</script>
</body>
</html>
