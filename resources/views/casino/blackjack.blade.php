<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Blackjack | Infinity Casino</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { 
            background: #0d1a13; color: #fff; font-family: 'Inter', sans-serif;
            margin: 0; padding: 0; overflow: hidden;
            height: 100dvh;
        }
        
        /* Casino Table Felt Background */
        .casino-table {
            background-color: #0b4f2c;
            background-image: 
                radial-gradient(circle at 50% -20%, #157345 0%, transparent 60%),
                radial-gradient(circle at 50% 120%, #06311a 0%, transparent 70%);
            height: 100%;
            display: flex;
            flex-direction: column;
            border-top: 10px solid #231611; /* Madera del borde */
            box-shadow: inset 0 20px 50px rgba(0,0,0,0.5);
            position: relative;
        }

        /* Branding en el tapete */
        .table-logo {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.15;
            font-family: 'Outfit';
            font-size: 5rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 5px;
            pointer-events: none;
            white-space: nowrap;
        }
        
        .table-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.3;
            font-family: 'Outfit';
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            pointer-events: none;
            border-top: 2px solid rgba(255,255,255,0.3);
            border-bottom: 2px solid rgba(255,255,255,0.3);
            padding: 10px 40px;
        }

        .header-bar {
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            z-index: 10;
        }

        /* Áreas de Juego */
        .game-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            z-index: 5;
        }

        .dealer-area, .player-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 180px;
        }

        .cards-container {
            display: flex;
            justify-content: center;
            gap: -40px; /* Cartas solapadas */
            margin-top: 15px;
            position: relative;
        }

        /* Diseño de Cartas (CSS Puro) */
        .playing-card {
            width: 100px;
            height: 140px;
            background: #fff;
            border-radius: 8px;
            box-shadow: -2px 5px 15px rgba(0,0,0,0.4);
            position: relative;
            margin-left: -40px; /* Solapamiento */
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 5px 10px;
            border: 1px solid #ddd;
        }
        
        .playing-card:first-child {
            margin-left: 0;
        }

        .playing-card:hover {
            transform: translateY(-15px);
            z-index: 10;
        }

        .card-value {
            font-size: 1.5rem;
            font-weight: bold;
            line-height: 1;
        }
        .card-suit {
            font-size: 1.5rem;
        }
        
        .card-red { color: #d32f2f; }
        .card-black { color: #212121; }

        .card-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 3rem;
        }

        .card-bottom {
            transform: rotate(180deg);
            text-align: left;
        }

        /* Carta oculta de la banca */
        .card-back {
            background: linear-gradient(135deg, #b71c1c 25%, transparent 25%) -50px 0,
                        linear-gradient(225deg, #b71c1c 25%, transparent 25%) -50px 0,
                        linear-gradient(315deg, #b71c1c 25%, transparent 25%),
                        linear-gradient(45deg, #b71c1c 25%, transparent 25%);
            background-size: 20px 20px;
            background-color: #880e4f;
            border: 5px solid #fff;
        }

        /* Puntajes */
        .score-badge {
            background: rgba(0,0,0,0.6);
            color: #fff;
            padding: 5px 15px;
            border-radius: 20px;
            font-family: 'Outfit';
            font-weight: 600;
            margin-bottom: 10px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* Controles / Fichas */
        .controls-area {
            background: rgba(0,0,0,0.85);
            padding: 20px;
            border-top: 2px solid #333;
            display: flex;
            justify-content: center;
            gap: 15px;
            backdrop-filter: blur(10px);
        }

        .btn-casino {
            background: #2a2a35;
            color: #fff;
            border: 1px solid #444;
            font-family: 'Outfit';
            font-weight: 600;
            text-transform: uppercase;
            padding: 12px 25px;
            border-radius: 30px;
            letter-spacing: 1px;
            transition: all 0.2s;
        }

        .btn-casino:hover { background: #3a3a45; border-color: #555; }
        
        .btn-action-hit { background: #198754; border-color: #198754; }
        .btn-action-hit:hover { background: #157347; border-color: #146c43; }
        
        .btn-action-stand { background: #dc3545; border-color: #dc3545; }
        .btn-action-stand:hover { background: #bb2d3b; border-color: #b02a37; }

        /* Chips area */
        .betting-area {
            text-align: center;
            margin-top: 10px;
        }
        
        .chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-weight: bold;
            font-family: 'Outfit';
            border: 4px dashed rgba(255,255,255,0.5);
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
            cursor: pointer;
            margin: 0 5px;
            transition: transform 0.2s;
        }
        .chip:hover { transform: scale(1.1); }
        .chip-100 { background: #198754; color: white; }
        .chip-500 { background: #0dcaf0; color: black; border-color: rgba(0,0,0,0.3); }
        .chip-1k { background: #6f42c1; color: white; }
        .chip-5k { background: #ffc107; color: black; border-color: rgba(0,0,0,0.3); }

        .current-bet {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #ffc107;
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit';
            font-weight: 900;
            font-size: 1.2rem;
            border: 6px dashed rgba(0,0,0,0.3);
            margin: 20px auto;
            box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        }

    </style>
</head>
<body>

<div class="casino-table">
    
    <div class="table-logo">INFINITY</div>
    <div class="table-text">BLACKJACK PAYS 3 TO 2<br><small style="font-size: 0.8rem;">Dealer must draw to 16 and stand on all 17s</small></div>

    <!-- Header / Saldo -->
    <div class="header-bar d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('tienda.show', 1) }}" class="btn btn-sm btn-outline-light"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
        <div class="text-end">
            @if($participanteLogueado)
                <div class="text-white-50 small">{{ $participanteLogueado->nombre }}</div>
                <div class="fw-bold text-warning" style="font-family: 'Outfit'; font-size: 1.2rem;">
                    <i class="bi bi-gem"></i> {{ number_format($participanteLogueado->saldo_fichas, 0) }}
                </div>
            @else
                <div class="fw-bold text-warning"><i class="bi bi-gem"></i> MODO DEMO</div>
            @endif
        </div>
    </div>

    <!-- Área de Juego -->
    <div class="game-area">
        
        <!-- Banca (Dealer) -->
        <div class="dealer-area">
            <div class="score-badge" id="dealer-score">BANCA: ?</div>
            <div class="cards-container" id="dealer-cards">
                <!-- Se llenará con JS -->
            </div>
        </div>

        <!-- Mensajes del Sistema -->
        <div class="text-center mt-3" style="min-height: 40px;">
            <h3 id="game-message" class="text-warning fw-bold" style="font-family: 'Outfit'; text-shadow: 0 2px 10px rgba(0,0,0,0.8); display: none;"></h3>
        </div>

        <!-- Apuesta en la mesa -->
        <div class="betting-area">
            <div class="current-bet" id="current-bet" style="display: none;">
                0
            </div>
        </div>

        <!-- Jugador (Player) -->
        <div class="player-area">
            <div class="score-badge" id="player-score">TU MANO: 0</div>
            <div class="cards-container" id="player-cards">
                <!-- Se llenará con JS -->
            </div>
        </div>

    </div>

    <!-- Controles Activos -->
    <div class="controls-area" id="controls-playing" style="display: none;">
        <button class="btn btn-casino btn-action-hit" onclick="hit()"><i class="bi bi-plus-circle me-1"></i> Pedir</button>
        <button class="btn btn-casino btn-action-stand" onclick="stand()"><i class="bi bi-hand-index-thumb me-1"></i> Plantarse</button>
        <button class="btn btn-casino" onclick="doubleDown()"><i class="bi bi-layers me-1"></i> Doblar</button>
    </div>
    
    <!-- Selección de Apuesta (Fichas) -->
    <div class="bg-dark text-center py-3" id="controls-betting" style="border-top: 1px solid #222;">
        <div class="text-white-50 small mb-2 text-uppercase fw-bold">Colocar Apuesta</div>
        <div class="d-flex justify-content-center">
            <div class="chip chip-100" onclick="placeBet(100)">100</div>
            <div class="chip chip-500" onclick="placeBet(500)">500</div>
            <div class="chip chip-1k" onclick="placeBet(1000)">1K</div>
            <div class="chip chip-5k" onclick="placeBet(5000)">5K</div>
        </div>
    </div>

</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const suitSymbols = { 'H': '♥', 'D': '♦', 'C': '♣', 'S': '♠' };
    const suitColors = { 'H': 'card-red', 'D': 'card-red', 'C': 'card-black', 'S': 'card-black' };

    function renderCard(cardData) {
        if (cardData.suit === 'hidden') {
            return `<div class="playing-card card-back"></div>`;
        }

        const symbol = suitSymbols[cardData.suit];
        const colorClass = suitColors[cardData.suit];
        const val = cardData.value;

        return `
            <div class="playing-card ${colorClass}">
                <div class="d-flex justify-content-between">
                    <div class="card-value">${val}</div>
                    <div class="card-suit">${symbol}</div>
                </div>
                <div class="card-center">${symbol}</div>
                <div class="card-bottom d-flex justify-content-between">
                    <div class="card-value">${val}</div>
                    <div class="card-suit">${symbol}</div>
                </div>
            </div>
        `;
    }

    function updateUI(data) {
        // Actualizar saldo visualmente
        if (data.saldo !== undefined) {
            document.querySelector('.text-warning').innerHTML = `<i class="bi bi-gem"></i> ${new Intl.NumberFormat().format(data.saldo)}`;
        }

        // Render Dealer Cards
        if (data.dealer_hand) {
            document.getElementById('dealer-cards').innerHTML = data.dealer_hand.map(renderCard).join('');
        }
        
        // Render Player Cards
        if (data.player_hand) {
            document.getElementById('player-cards').innerHTML = data.player_hand.map(renderCard).join('');
        }

        // Update Scores
        if (data.player_value !== undefined) {
            document.getElementById('player-score').innerText = `TU MANO: ${data.player_value}`;
        }
        if (data.dealer_value !== undefined) {
            document.getElementById('dealer-score').innerText = `BANCA: ${data.dealer_value}`;
        } else {
            document.getElementById('dealer-score').innerText = `BANCA: ?`;
        }

        // Mostrar Apuesta Actual
        if (data.bet_amount || document.getElementById('current-bet').innerText != "0") {
            const betElem = document.getElementById('current-bet');
            betElem.style.display = 'flex';
            if (data.bet_amount) betElem.innerText = data.bet_amount;
        }

        // Manejar estado de botones
        const msgElem = document.getElementById('game-message');
        if (data.estado === 'playing') {
            document.getElementById('controls-betting').style.display = 'none';
            document.getElementById('controls-playing').style.display = 'flex';
            msgElem.style.display = 'none';
        } else if (data.estado === 'finished') {
            document.getElementById('controls-playing').style.display = 'none';
            setTimeout(() => {
                document.getElementById('controls-betting').style.display = 'block';
                document.getElementById('current-bet').style.display = 'none';
                document.getElementById('dealer-cards').innerHTML = '';
                document.getElementById('player-cards').innerHTML = '';
                document.getElementById('player-score').innerText = 'TU MANO: 0';
                document.getElementById('dealer-score').innerText = 'BANCA: ?';
            }, 4000); // Dar 4 segundos para ver el resultado

            // Mostrar Mensaje de Resultado
            msgElem.style.display = 'block';
            if (data.result === 'win') msgElem.innerHTML = '¡GANASTE! 🎉';
            else if (data.result === 'loss') msgElem.innerHTML = 'LA CASA GANA 💸';
            else if (data.result === 'push') msgElem.innerHTML = 'EMPATE (PUSH) 🤝';
            else if (data.result === 'blackjack') msgElem.innerHTML = '¡BLACKJACK! 💎';
        }
    }

    async function placeBet(amount) {
        try {
            const res = await fetch('/blackjack/bet', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ amount })
            });
            const data = await res.json();
            if (res.ok) {
                data.bet_amount = amount;
                updateUI(data);
            } else {
                alert(data.error || 'Error al apostar');
            }
        } catch (e) {
            console.error(e);
        }
    }

    async function hit() {
        try {
            const res = await fetch('/blackjack/hit', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await res.json();
            if (res.ok) updateUI(data);
            else alert(data.error);
        } catch (e) {
            console.error(e);
        }
    }

    async function stand() {
        try {
            const res = await fetch('/blackjack/stand', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await res.json();
            if (res.ok) updateUI(data);
            else alert(data.error);
        } catch (e) {
            console.error(e);
        }
    }

    async function doubleDown() {
        try {
            const res = await fetch('/blackjack/double', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await res.json();
            if (res.ok) updateUI(data);
            else alert(data.error);
        } catch (e) {
            console.error(e);
        }
    }

    // Si ya existe un juego activo (pasado por el controlador), inicializarlo
    @if($activeGame)
        // Por ahora lo simplificamos: si hay un juego colgado, el frontend lo reinicia visualmente.
        // En una versio multijugador, aqui cargariamos el estado completo.
    @endif
</script>

</body>
</html>
