<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Ruleta Premium - Infinity</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --felt-color: #0d4a25; /* Verde de Ruleta */
            --red-num: #e74c3c;
            --black-num: #111;
            --gold-accent: #d4af37;
            --table-border: #8b5a2b;
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

        /* --- Header --- */
        .game-header {
            background: rgba(0,0,0,0.8);
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

        /* --- Mesa de Juego --- */
        .roulette-container {
            flex-grow: 1;
            background: #0d4a25; /* Fondo solido de paño de casino verde */
            display: flex;
            flex-direction: column;
            padding: 20px;
            position: relative;
            overflow-y: auto;
            overflow-x: hidden;
            background-image: url('data:image/svg+xml;utf8,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100" fill="%230d4a25"/><circle cx="50" cy="50" r="2" fill="%23ffffff" fill-opacity="0.05"/></svg>'); /* Textura de paño leve */
        }

        .table-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            font-size: 8rem;
            font-weight: 900;
            letter-spacing: 5px;
            white-space: nowrap;
            pointer-events: none;
        }

        /* --- El Cilindro (Wheel) --- */
        .wheel-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            perspective: 1000px;
        }

        .wheel {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: repeating-conic-gradient(
                var(--red-num) 0 9.7deg, 
                var(--black-num) 9.7deg 19.4deg
            );
            border: 15px solid #3e1b04; /* Madera */
            box-shadow: 0 10px 30px rgba(0,0,0,0.6), inset 0 0 15px rgba(0,0,0,0.9);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wheel::before {
            content: '';
            position: absolute;
            width: 90%;
            height: 90%;
            border-radius: 50%;
            border: 2px solid #888;
            pointer-events: none;
        }

        .wheel::after {
            content: '';
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, #eaddc0 0%, #bca56a 50%, #856a29 100%);
            border-radius: 50%;
            border: 2px solid #5a4b27;
            box-shadow: 0 5px 15px rgba(0,0,0,0.6);
        }

        /* --- Tablero de Apuestas (Grid) --- */
        .board-wrapper {
            display: flex;
            justify-content: center;
            flex-grow: 1;
            align-items: center;
        }

        .betting-board {
            display: grid;
            grid-template-columns: 60px repeat(12, 1fr) 60px;
            grid-template-rows: repeat(3, 80px) 50px 50px;
            gap: 2px;
            background: rgba(255,255,255,0.2);
            padding: 5px;
            border: 5px solid var(--table-border);
            border-radius: 10px;
            max-width: 900px;
            width: 100%;
        }

        .board-cell {
            background: transparent;
            border: 1px solid #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            position: relative;
            transition: background 0.2s;
        }
        .board-cell:hover {
            background: rgba(255,255,255,0.2);
        }

        .zero-cell {
            grid-row: 1 / 4;
            grid-column: 1;
            background: var(--felt-color); /* Verde mesa */
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        /* Numbers 1-36 are placed via nth-child mostly, but we can assign colors via classes */
        .red-cell { background: var(--red-num); }
        .black-cell { background: var(--black-num); }

        /* Rows to Columns mapping in standard European layout */
        /* Row 1 (Top): 3, 6, 9... 36 */
        /* Row 2 (Middle): 2, 5, 8... 35 */
        /* Row 3 (Bottom): 1, 4, 7... 34 */

        /* Columns (Dozens) */
        .dozen-cell {
            grid-row: 4;
            grid-column: span 4;
            font-size: 1rem;
            text-transform: uppercase;
        }

        /* Bottom Outside Bets */
        .outside-cell {
            grid-row: 5;
            grid-column: span 2;
            font-size: 1rem;
            text-transform: uppercase;
        }

        /* 2-to-1 cells */
        .col-bet-cell {
            grid-column: 14;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .col-bet-1 { grid-row: 1; border-radius: 0 5px 0 0;}
        .col-bet-2 { grid-row: 2; }
        .col-bet-3 { grid-row: 3; border-radius: 0 0 5px 0;}

        /* Fix grid placing for 1st Dozen */
        .doz-1 { grid-column: 2 / 6; }
        .doz-2 { grid-column: 6 / 10; }
        .doz-3 { grid-column: 10 / 14; }

        /* Fix grid placing for outside */
        .out-1-18 { grid-column: 2 / 4; }
        .out-even { grid-column: 4 / 6; }
        .out-red { grid-column: 6 / 8; background: var(--red-num) !important;}
        .out-black { grid-column: 8 / 10; background: var(--black-num) !important;}
        .out-odd { grid-column: 10 / 12; }
        .out-19-36 { grid-column: 12 / 14; }

        /* --- Controles Inferiores (Estilo Dark Bar) --- */
        .game-controls {
            background: #111;
            border-top: 2px solid #555;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 -5px 15px rgba(0,0,0,0.5);
            z-index: 100;
        }
        
        .chip-selector {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 5px;
            align-items: flex-end;
        }
        
        .chip {
            width: 50px;
            height: 50px;
            font-size: 14px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #111;
            box-shadow: 0 4px 8px rgba(0,0,0,0.4), inset 0 0 5px rgba(255,255,255,0.5);
            cursor: pointer;
            transition: transform 0.2s;
            /* Patrón de líneas simulando la ficha */
            border: 4px dashed rgba(255,255,255,0.7);
        }
        .chip.selected {
            transform: translateY(-10px);
            border-color: #00FF88;
            box-shadow: 0 10px 15px rgba(0,255,136,0.3);
        }

        .chip-10 { background-color: #b0c4de; }
        .chip-50 { background-color: #98fb98; }
        .chip-100 { background-color: #ffb6c1; }
        .chip-500 { background-color: #fff; }

        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-action {
            background: transparent;
            color: #aaa;
            border: none;
            padding: 8px 15px;
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .btn-action:hover {
            color: #fff;
        }
        .btn-spin {
            border: 1px solid #fff;
            border-radius: 5px;
            color: #fff;
        }
        .btn-spin:hover {
            background: rgba(255,255,255,0.1);
        }

        /* CHIP ON BOARD MOCK */
        .placed-chip {
            position: absolute;
            width: 30px;
            height: 30px;
            font-size: 10px;
            z-index: 10;
        }

        /* --- Responsive Adjustments --- */
        @media (min-width: 901px) {
            .roulette-container {
                flex-direction: row;
                align-items: center;
                justify-content: space-around;
                padding: 40px 20px;
            }
            .wheel-wrapper {
                flex: 1;
                margin-bottom: 0;
            }
            .wheel {
                width: 450px;
                height: 450px;
                border-width: 25px;
            }
            .wheel::after {
                width: 160px;
                height: 160px;
            }
            .board-wrapper {
                flex: 1.5;
                margin-top: 0;
                justify-content: flex-end;
            }
            .betting-board {
                max-width: 100%;
                grid-template-rows: repeat(3, 110px) 60px 60px;
                border-radius: 10px;
            }
            .board-cell {
                font-size: 1.8rem;
                border: 1px solid #fff;
            }
            .dozen-cell, .outside-cell {
                font-size: 1.2rem;
            }
            .col-bet-1 { border-top-right-radius: 8px; }
            .col-bet-3 { border-bottom-right-radius: 8px; }
            .out-19-36 { border-bottom-right-radius: 8px; }
            
            .btn-action {
                font-size: 1rem;
                padding: 12px 25px;
                margin-left: 10px;
            }
        }

        @media (max-width: 900px) {
            .betting-board {
                transform: rotate(-90deg) scale(0.85);
                transform-origin: center center;
                margin-top: 220px;
                margin-bottom: 220px;
            }
            .roulette-container {
                align-items: center;
                justify-content: flex-start;
            }
            .board-cell { transform: rotate(90deg); } /* Rotar texto de vuelta */
            .wheel { width: 220px; height: 220px; }
            .wheel::after { width: 130px; height: 130px; }
            .board-wrapper { width: 100%; display: flex; justify-content: center; }
        }
    </style>
</head>
<body>

    <header class="game-header">
        <a href="{{ route('tienda.show', 1) }}" class="text-white text-decoration-none">
            <i class="bi bi-chevron-left"></i> Lobby
        </a>
        <div class="fw-bold" style="letter-spacing: 2px; color: var(--gold-accent);">
            @if(isset($participanteLogueado))
                {{ $participanteLogueado->nombre }}
            @else
                INFINITY CASINO
            @endif
        </div>
        <div class="balance-box">
            <i class="bi bi-gem"></i> <span id="balanceDisplay">{{ isset($participanteLogueado) ? number_format($participanteLogueado->saldo_fichas, 0) : 'DEMO' }}</span>
        </div>
    </header>

    <main class="roulette-container">
        <div class="table-logo">INFINITY RULETA</div>
        
        <div class="wheel-wrapper">
            <div class="wheel"></div>
        </div>

        <div class="board-wrapper">
            <div class="betting-board">
                <!-- ZERO -->
                <div class="board-cell zero-cell">0</div>

                <!-- ROW 1 (TOP) 3, 6, 9... -->
                <div class="board-cell red-cell" style="grid-row:1; grid-column:2;">3</div>
                <div class="board-cell black-cell" style="grid-row:1; grid-column:3;">6</div>
                <div class="board-cell red-cell" style="grid-row:1; grid-column:4;">9</div>
                <div class="board-cell red-cell" style="grid-row:1; grid-column:5;">12</div>
                <div class="board-cell black-cell" style="grid-row:1; grid-column:6;">15</div>
                <div class="board-cell red-cell" style="grid-row:1; grid-column:7;">18</div>
                <div class="board-cell red-cell" style="grid-row:1; grid-column:8;">21</div>
                <div class="board-cell black-cell" style="grid-row:1; grid-column:9;">24</div>
                <div class="board-cell red-cell" style="grid-row:1; grid-column:10;">27</div>
                <div class="board-cell red-cell" style="grid-row:1; grid-column:11;">30</div>
                <div class="board-cell black-cell" style="grid-row:1; grid-column:12;">33</div>
                <div class="board-cell red-cell" style="grid-row:1; grid-column:13;">36</div>

                <!-- ROW 2 (MIDDLE) 2, 5, 8... -->
                <div class="board-cell black-cell" style="grid-row:2; grid-column:2;">2</div>
                <div class="board-cell red-cell" style="grid-row:2; grid-column:3;">5</div>
                <div class="board-cell black-cell" style="grid-row:2; grid-column:4;">8</div>
                <div class="board-cell black-cell" style="grid-row:2; grid-column:5;">11</div>
                <div class="board-cell red-cell" style="grid-row:2; grid-column:6;">14</div>
                <div class="board-cell black-cell" style="grid-row:2; grid-column:7;">17</div>
                <div class="board-cell black-cell" style="grid-row:2; grid-column:8;">20</div>
                <div class="board-cell red-cell" style="grid-row:2; grid-column:9;">23</div>
                <div class="board-cell black-cell" style="grid-row:2; grid-column:10;">26</div>
                <div class="board-cell black-cell" style="grid-row:2; grid-column:11;">29</div>
                <div class="board-cell red-cell" style="grid-row:2; grid-column:12;">32</div>
                <div class="board-cell black-cell" style="grid-row:2; grid-column:13;">35</div>

                <!-- ROW 3 (BOTTOM) 1, 4, 7... -->
                <div class="board-cell red-cell" style="grid-row:3; grid-column:2;">1</div>
                <div class="board-cell black-cell" style="grid-row:3; grid-column:3;">4</div>
                <div class="board-cell red-cell" style="grid-row:3; grid-column:4;">7</div>
                <div class="board-cell black-cell" style="grid-row:3; grid-column:5;">10</div>
                <div class="board-cell black-cell" style="grid-row:3; grid-column:6;">13</div>
                <div class="board-cell red-cell" style="grid-row:3; grid-column:7;">16</div>
                <div class="board-cell red-cell" style="grid-row:3; grid-column:8;">19</div>
                <div class="board-cell black-cell" style="grid-row:3; grid-column:9;">22</div>
                <div class="board-cell red-cell" style="grid-row:3; grid-column:10;">25</div>
                <div class="board-cell black-cell" style="grid-row:3; grid-column:11;">28</div>
                <div class="board-cell black-cell" style="grid-row:3; grid-column:12;">31</div>
                <div class="board-cell red-cell" style="grid-row:3; grid-column:13;">34</div>

                <!-- DOZENS -->
                <div class="board-cell dozen-cell doz-1">1st 12</div>
                <div class="board-cell dozen-cell doz-2">2nd 12</div>
                <div class="board-cell dozen-cell doz-3">3rd 12</div>

                <!-- OUTSIDE BETS -->
                <div class="board-cell outside-cell out-1-18">1 to 18</div>
                <div class="board-cell outside-cell out-even">EVEN</div>
                <div class="board-cell outside-cell out-red"></div>
                <div class="board-cell outside-cell out-black"></div>
                <div class="board-cell outside-cell out-odd">ODD</div>
                <div class="board-cell outside-cell out-19-36">19 to 36</div>

                <!-- COLUMNS -->
                <div class="board-cell col-bet-cell col-bet-1">2:1</div>
                <div class="board-cell col-bet-cell col-bet-2">2:1</div>
                <div class="board-cell col-bet-cell col-bet-3">2:1</div>

                <!-- Ejemplo de Ficha Colocada (Mock) -->
                <div class="chip chip-10 placed-chip" style="grid-row:2; grid-column:9;">10</div>
                <div class="chip chip-100 placed-chip" style="grid-row:4; grid-column:6/10;">100</div>

            </div>
        </div>

    </main>

    <div class="game-controls">
        <div class="chip-selector">
            <div class="chip chip-10 selected">0.10</div>
            <div class="chip chip-50">0.50</div>
            <div class="chip chip-100">1</div>
            <div class="chip chip-500">5</div>
            <div class="chip chip-100" style="background-color:#555">25</div>
            <div class="chip chip-500" style="background-color:#fff">100</div>
        </div>
        
        <div class="action-buttons">
            <button class="btn-action" onclick="alert('Eliminar')">ELIMINAR</button>
            <button class="btn-action" onclick="alert('Deshacer')">DESHACER</button>
            <button class="btn-action btn-spin" onclick="alert('Girar!')">GIRAR</button>
            <button class="btn-action" onclick="alert('Doblar')">DOBLAR</button>
            <button class="btn-action" onclick="alert('Reapostar')">REAPOSTAR</button>
        </div>
    </div>

</body>
</html>
