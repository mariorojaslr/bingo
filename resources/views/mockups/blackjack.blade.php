<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Blackjack VIP - Infinity</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --table-color: #0b3d1f; /* Verde clásico oscuro de casino */
            --table-border: #8b5a2b; /* Borde madera/cuero */
            --gold-accent: #d4af37;
        }

        body {
            background-color: #000;
            font-family: 'Outfit', sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100vh;
            user-select: none;
        }

        /* --- UI Overlay (Header / Footer) --- */
        .game-header {
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
        }

        .balance-box {
            background: rgba(255,255,255,0.1);
            border: 1px solid var(--gold-accent);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            color: var(--gold-accent);
            font-family: monospace;
            font-size: 1.1rem;
        }

        /* --- La Mesa (Table Felt) --- */
        .blackjack-table {
            flex-grow: 1;
            background: radial-gradient(ellipse at bottom, #11572c 0%, var(--table-color) 100%);
            position: relative;
            box-shadow: inset 0 20px 50px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 40px 20px;
            overflow: hidden;
        }

        /* Borde superior curvo estilo mesa */
        .table-edge {
            position: absolute;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 150vw;
            height: 300px;
            background: transparent;
            border: 30px solid rgba(0,0,0,0.3);
            border-radius: 50%;
            pointer-events: none;
        }

        .table-logo {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.15;
            font-size: 5rem;
            font-weight: 900;
            letter-spacing: 5px;
            white-space: nowrap;
        }

        .insurance-line {
            position: absolute;
            top: 35%;
            left: 10%;
            right: 10%;
            height: 150px;
            border-top: 2px dashed rgba(255,255,255,0.2);
            border-radius: 50%;
            text-align: center;
            pointer-events: none;
        }

        .insurance-line span {
            background: var(--table-color);
            padding: 0 20px;
            color: rgba(255,255,255,0.4);
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            top: -12px;
        }

        /* --- Zonas de Cartas --- */
        .hand-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .dealer-area {
            margin-top: 10px;
        }

        .player-area {
            margin-bottom: 20px;
        }

        .score-badge {
            background: rgba(0,0,0,0.6);
            color: #fff;
            padding: 3px 12px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 15px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .score-badge.player-score {
            margin-bottom: 0;
            margin-top: 15px;
        }

        /* --- Cartas --- */
        .cards-container {
            display: flex;
            gap: -40px; /* Cartas solapadas */
        }

        .playing-card {
            width: 80px;
            height: 120px;
            background: #fff;
            border-radius: 8px;
            box-shadow: -3px 3px 10px rgba(0,0,0,0.4);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 8px;
            position: relative;
            color: #000;
            margin-right: -40px; /* Solapamiento negativo */
            transition: transform 0.3s ease;
        }
        
        .playing-card:last-child {
            margin-right: 0;
        }

        .playing-card:hover {
            transform: translateY(-10px);
        }

        .card-red { color: #d00; }
        .card-black { color: #000; }

        .card-value {
            font-size: 1.2rem;
            font-weight: 900;
            line-height: 1;
        }

        .card-suit {
            font-size: 1.5rem;
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .card-back {
            background: repeating-linear-gradient(45deg, #0f4c75, #0f4c75 10px, #1b262c 10px, #1b262c 20px);
            border: 4px solid #fff;
            color: transparent;
        }

        /* --- Fichas de Apuesta --- */
        .bet-circle {
            width: 90px;
            height: 90px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            position: relative;
        }
        
        .bet-circle::after {
            content: 'PLACE BET';
            position: absolute;
            color: rgba(255,255,255,0.2);
            font-size: 0.7rem;
            text-align: center;
        }

        .chip {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 14px;
            color: #fff;
            box-shadow: inset 0 0 0 5px rgba(255,255,255,0.2), 0 5px 10px rgba(0,0,0,0.5);
            position: relative;
            z-index: 5;
            border: 2px dashed rgba(255,255,255,0.5);
        }

        .chip-10 { background: #3498db; }
        .chip-50 { background: #e74c3c; }
        .chip-100 { background: #2c3e50; }
        .chip-500 { background: #9b59b6; }

        .chip-stack {
            position: absolute;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .chip-stack .chip {
            position: absolute;
        }
        .chip-stack .chip:nth-child(1) { bottom: 0px; }
        .chip-stack .chip:nth-child(2) { bottom: 4px; }
        .chip-stack .chip:nth-child(3) { bottom: 8px; }

        /* --- Controles --- */
        .game-controls {
            background: #111;
            padding: 20px;
            display: flex;
            justify-content: center;
            gap: 15px;
            border-top: 2px solid #333;
            z-index: 100;
        }

        .btn-action {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #000;
            box-shadow: 0 4px 0 rgba(0,0,0,0.4);
            transition: all 0.1s;
        }

        .btn-action:active {
            transform: translateY(4px);
            box-shadow: 0 0 0 rgba(0,0,0,0);
        }

        .btn-hit { background: #2ecc71; }
        .btn-stand { background: #e74c3c; color: #fff; }
        .btn-double { background: #f1c40f; }

        .btn-action i { font-size: 1.5rem; margin-bottom: 2px; }

        @media (min-width: 768px) {
            .playing-card { width: 100px; height: 145px; margin-right: -60px; }
            .bet-circle { width: 120px; height: 120px; }
            .chip { width: 60px; height: 60px; font-size: 16px; }
            .btn-action { width: 150px; height: 60px; border-radius: 10px; flex-direction: row; gap: 10px;}
            .btn-action i { margin-bottom: 0; }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="game-header">
        <a href="javascript:history.back()" class="text-white text-decoration-none">
            <i class="bi bi-chevron-left"></i> Lobby
        </a>
        <div class="fw-bold" style="letter-spacing: 2px; color: var(--gold-accent);">INFINITY CASINO</div>
        <div class="balance-box">
            $4,500.00
        </div>
    </header>

    <!-- La Mesa de Juego -->
    <main class="blackjack-table">
        <div class="table-edge"></div>
        <div class="table-logo">INFINITY BLACKJACK</div>
        
        <div class="insurance-line">
            <span>Insurance Pays 2 to 1</span>
        </div>

        <!-- Zona del Crupier -->
        <div class="hand-area dealer-area">
            <div class="score-badge">10</div>
            <div class="cards-container">
                <!-- Carta boca arriba -->
                <div class="playing-card card-black">
                    <div class="card-value">10</div>
                    <div class="card-suit">♠</div>
                    <div class="card-value" style="position:absolute; bottom:8px; right:8px; transform: rotate(180deg);">10</div>
                </div>
                <!-- Carta boca abajo -->
                <div class="playing-card card-back"></div>
            </div>
        </div>

        <!-- Zona del Jugador -->
        <div class="hand-area player-area">
            <!-- Zona de Apuesta -->
            <div class="bet-circle">
                <div class="chip-stack">
                    <div class="chip chip-100">100</div>
                    <div class="chip chip-50">50</div>
                    <div class="chip chip-10">10</div>
                </div>
            </div>

            <!-- Cartas del Jugador -->
            <div class="cards-container mt-3">
                <div class="playing-card card-red">
                    <div class="card-value">A</div>
                    <div class="card-suit">♥</div>
                    <div class="card-value" style="position:absolute; bottom:8px; right:8px; transform: rotate(180deg);">A</div>
                </div>
                <div class="playing-card card-black">
                    <div class="card-value">8</div>
                    <div class="card-suit">♣</div>
                    <div class="card-value" style="position:absolute; bottom:8px; right:8px; transform: rotate(180deg);">8</div>
                </div>
            </div>
            <div class="score-badge player-score">19</div>
        </div>

    </main>

    <!-- Controles de Acción -->
    <div class="game-controls">
        <button class="btn-action btn-hit">
            <i class="bi bi-plus-square"></i>
            <span>HIT</span>
        </button>
        <button class="btn-action btn-stand">
            <i class="bi bi-dash-square"></i>
            <span>STAND</span>
        </button>
        <button class="btn-action btn-double">
            <i class="bi bi-x-square"></i>
            <span>DOUBLE</span>
        </button>
    </div>

</body>
</html>
