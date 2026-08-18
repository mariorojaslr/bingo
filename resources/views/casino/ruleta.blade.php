<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            -webkit-user-select: none;
            -webkit-touch-callout: none;
            touch-action: manipulation;
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
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: 0.3s;
            font-family: monospace;
            font-size: 1.1rem;
        }
        .balance-box:hover {
            background: rgba(212, 175, 55, 0.2);
            color: #fff;
        }
        .balance-box .add-icon {
            background: var(--gold-accent);
            color: #000;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            margin-left: 5px;
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
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 20px solid #3e1b04; /* Madera */
            box-shadow: 0 10px 30px rgba(0,0,0,0.6), inset 0 0 15px rgba(0,0,0,0.9);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #222; /* Fondo debajo del SVG */
            overflow: hidden;
        }

        .wheel svg {
            width: 100%;
            height: 100%;
            transform: rotate(-4.86deg); /* Ajuste fino inicial si es necesario */
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
                align-items: stretch;
                justify-content: center;
                gap: 50px;
                padding: 40px;
            }
            .wheel-wrapper {
                flex: 0 0 450px;
                display: flex;
                align-items: center;
                justify-content: flex-end;
            }
            .wheel {
                width: 450px;
                height: 450px;
                border-width: 25px;
            }
            .board-wrapper {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: flex-start;
                max-width: 1100px;
            }
            .betting-board {
                width: 100%;
                height: 100%;
                max-height: 600px;
                grid-template-rows: repeat(3, 1fr) 70px 70px;
                border-radius: 10px;
            }
            .board-cell {
                font-size: 2.5rem;
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
            .roulette-container {
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 10px;
                overflow: hidden; /* Evitar scroll clipping roto */
            }
            
            /* Ocultar la rueda en móvil por ahora, mostrar solo el tapete */
            .wheel-wrapper {
                display: none; 
            }

            .board-wrapper { 
                width: 100%; 
                height: 75vh; /* Matches the rotated width of betting-board to prevent overlap */
                display: flex; 
                justify-content: center; 
                align-items: center;
                position: relative;
                margin: 20px 0;
            }

            /* Truco matemático: Intercambiamos viewport width (vw) y height (vh) 
               para que al rotar 90 grados encaje perfecto en pantallas portrait */
            .betting-board {
                width: 75vh;
                height: 85vw;
                grid-template-rows: repeat(3, 1fr) 45px 45px;
                transform: rotate(-90deg);
            }
            
            .board-cell { 
                transform: rotate(90deg); /* Textos rectos */
                font-size: 1.2rem;
            }
            
            .dozen-cell, .outside-cell {
                font-size: 0.8rem;
                padding: 0 5px;
            }

            /* Controles inferiores en móvil (apilados) */
            .game-controls {
                flex-direction: column;
                padding: 10px;
                gap: 10px;
            }
            .chip-selector {
                width: 100%;
                justify-content: center;
            }
            .chip {
                width: 40px; height: 40px; font-size: 12px;
            }
            .action-buttons {
                width: 100%;
                overflow-x: auto;
                justify-content: flex-start;
                padding-bottom: 5px;
            }
            .btn-action {
                font-size: 0.75rem;
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>

    <header class="game-header">
        <a href="{{ route('lobby.index') }}" class="text-white text-decoration-none">
            <i class="bi bi-chevron-left"></i> Lobby
        </a>
        <div class="fw-bold" style="letter-spacing: 2px; color: var(--gold-accent);">
            @if(isset($participanteLogueado))
                {{ $participanteLogueado->nombre }}
            @else
                INFINITY CASINO
            @endif
        </div>
        <a href="{{ route('cajero.show') }}?t={{ isset($participanteLogueado) ? $participanteLogueado->telefono : '' }}" class="balance-box" title="Cargar Fichas">
            <i class="bi bi-gem"></i> <span id="balanceDisplay">{{ isset($participanteLogueado) ? number_format($participanteLogueado->saldo_fichas, 0) : 'DEMO' }}</span>
            <div class="add-icon"><i class="bi bi-plus-lg"></i></div>
        </a>
    </header>

    <main class="roulette-container">
        <div class="table-logo">INFINITY RULETA</div>
        
        <div class="wheel-wrapper">
            <div class="wheel">
                @include('mockups.partials.wheel_svg')
            </div>
        </div>

        <div class="board-wrapper">
            <div class="betting-board" id="bettingBoard">
                <!-- ZERO -->
                <div class="board-cell zero-cell" data-bet-type="straight" data-bet-value="0">0</div>

                <!-- ROW 1 (TOP) 3, 6, 9... -->
                <div class="board-cell red-cell" style="grid-row:1; grid-column:2;" data-bet-type="straight" data-bet-value="3">3</div>
                <div class="board-cell black-cell" style="grid-row:1; grid-column:3;" data-bet-type="straight" data-bet-value="6">6</div>
                <div class="board-cell red-cell" style="grid-row:1; grid-column:4;" data-bet-type="straight" data-bet-value="9">9</div>
                <div class="board-cell red-cell" style="grid-row:1; grid-column:5;" data-bet-type="straight" data-bet-value="12">12</div>
                <div class="board-cell black-cell" style="grid-row:1; grid-column:6;" data-bet-type="straight" data-bet-value="15">15</div>
                <div class="board-cell red-cell" style="grid-row:1; grid-column:7;" data-bet-type="straight" data-bet-value="18">18</div>
                <div class="board-cell red-cell" style="grid-row:1; grid-column:8;" data-bet-type="straight" data-bet-value="21">21</div>
                <div class="board-cell black-cell" style="grid-row:1; grid-column:9;" data-bet-type="straight" data-bet-value="24">24</div>
                <div class="board-cell red-cell" style="grid-row:1; grid-column:10;" data-bet-type="straight" data-bet-value="27">27</div>
                <div class="board-cell red-cell" style="grid-row:1; grid-column:11;" data-bet-type="straight" data-bet-value="30">30</div>
                <div class="board-cell black-cell" style="grid-row:1; grid-column:12;" data-bet-type="straight" data-bet-value="33">33</div>
                <div class="board-cell red-cell" style="grid-row:1; grid-column:13;" data-bet-type="straight" data-bet-value="36">36</div>

                <!-- ROW 2 (MIDDLE) 2, 5, 8... -->
                <div class="board-cell black-cell" style="grid-row:2; grid-column:2;" data-bet-type="straight" data-bet-value="2">2</div>
                <div class="board-cell red-cell" style="grid-row:2; grid-column:3;" data-bet-type="straight" data-bet-value="5">5</div>
                <div class="board-cell black-cell" style="grid-row:2; grid-column:4;" data-bet-type="straight" data-bet-value="8">8</div>
                <div class="board-cell black-cell" style="grid-row:2; grid-column:5;" data-bet-type="straight" data-bet-value="11">11</div>
                <div class="board-cell red-cell" style="grid-row:2; grid-column:6;" data-bet-type="straight" data-bet-value="14">14</div>
                <div class="board-cell black-cell" style="grid-row:2; grid-column:7;" data-bet-type="straight" data-bet-value="17">17</div>
                <div class="board-cell black-cell" style="grid-row:2; grid-column:8;" data-bet-type="straight" data-bet-value="20">20</div>
                <div class="board-cell red-cell" style="grid-row:2; grid-column:9;" data-bet-type="straight" data-bet-value="23">23</div>
                <div class="board-cell black-cell" style="grid-row:2; grid-column:10;" data-bet-type="straight" data-bet-value="26">26</div>
                <div class="board-cell black-cell" style="grid-row:2; grid-column:11;" data-bet-type="straight" data-bet-value="29">29</div>
                <div class="board-cell red-cell" style="grid-row:2; grid-column:12;" data-bet-type="straight" data-bet-value="32">32</div>
                <div class="board-cell black-cell" style="grid-row:2; grid-column:13;" data-bet-type="straight" data-bet-value="35">35</div>

                <!-- ROW 3 (BOTTOM) 1, 4, 7... -->
                <div class="board-cell red-cell" style="grid-row:3; grid-column:2;" data-bet-type="straight" data-bet-value="1">1</div>
                <div class="board-cell black-cell" style="grid-row:3; grid-column:3;" data-bet-type="straight" data-bet-value="4">4</div>
                <div class="board-cell red-cell" style="grid-row:3; grid-column:4;" data-bet-type="straight" data-bet-value="7">7</div>
                <div class="board-cell black-cell" style="grid-row:3; grid-column:5;" data-bet-type="straight" data-bet-value="10">10</div>
                <div class="board-cell black-cell" style="grid-row:3; grid-column:6;" data-bet-type="straight" data-bet-value="13">13</div>
                <div class="board-cell red-cell" style="grid-row:3; grid-column:7;" data-bet-type="straight" data-bet-value="16">16</div>
                <div class="board-cell red-cell" style="grid-row:3; grid-column:8;" data-bet-type="straight" data-bet-value="19">19</div>
                <div class="board-cell black-cell" style="grid-row:3; grid-column:9;" data-bet-type="straight" data-bet-value="22">22</div>
                <div class="board-cell red-cell" style="grid-row:3; grid-column:10;" data-bet-type="straight" data-bet-value="25">25</div>
                <div class="board-cell black-cell" style="grid-row:3; grid-column:11;" data-bet-type="straight" data-bet-value="28">28</div>
                <div class="board-cell black-cell" style="grid-row:3; grid-column:12;" data-bet-type="straight" data-bet-value="31">31</div>
                <div class="board-cell red-cell" style="grid-row:3; grid-column:13;" data-bet-type="straight" data-bet-value="34">34</div>

                <!-- DOZENS -->
                <div class="board-cell dozen-cell doz-1" data-bet-type="dozen1" data-bet-value="null">1st 12</div>
                <div class="board-cell dozen-cell doz-2" data-bet-type="dozen2" data-bet-value="null">2nd 12</div>
                <div class="board-cell dozen-cell doz-3" data-bet-type="dozen3" data-bet-value="null">3rd 12</div>

                <!-- OUTSIDE BETS -->
                <div class="board-cell outside-cell out-1-18" data-bet-type="1-18" data-bet-value="null">1 to 18</div>
                <div class="board-cell outside-cell out-even" data-bet-type="even" data-bet-value="null">EVEN</div>
                <div class="board-cell outside-cell out-red" data-bet-type="red" data-bet-value="null"></div>
                <div class="board-cell outside-cell out-black" data-bet-type="black" data-bet-value="null"></div>
                <div class="board-cell outside-cell out-odd" data-bet-type="odd" data-bet-value="null">ODD</div>
                <div class="board-cell outside-cell out-19-36" data-bet-type="19-36" data-bet-value="null">19 to 36</div>

                <!-- COLUMNS -->
                <div class="board-cell col-bet-cell col-bet-1" data-bet-type="col1" data-bet-value="null">2:1</div>
                <div class="board-cell col-bet-cell col-bet-2" data-bet-type="col2" data-bet-value="null">2:1</div>
                <div class="board-cell col-bet-cell col-bet-3" data-bet-type="col3" data-bet-value="null">2:1</div>

            </div>
        </div>

    </main>

    <div class="game-controls">
        <div class="chip-selector">
            <div class="chip chip-10 selected" data-value="0.10">0.10</div>
            <div class="chip chip-50" data-value="0.50">0.50</div>
            <div class="chip chip-100" data-value="1">1</div>
            <div class="chip chip-500" data-value="5">5</div>
            <div class="chip chip-100" data-value="25" style="background-color:#555; color:white;">25</div>
            <div class="chip chip-500" data-value="100" style="background-color:#fff">100</div>
        </div>
        
        <div class="action-buttons">
            <button class="btn-action" id="btn-clear">ELIMINAR</button>
            <button class="btn-action" id="btn-undo">DESHACER</button>
            <button class="btn-action btn-spin" id="btn-spin">GIRAR</button>
            <button class="btn-action" id="btn-double">DOBLAR</button>
            <button class="btn-action" id="btn-rebet">REAPOSTAR</button>
        </div>
    </div>

    <!-- Resultados Modal Overlay -->
    <div id="result-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.8); z-index: 1000; justify-content: center; align-items: center; flex-direction: column;">
        <h1 id="result-number" style="font-size: 8rem; margin: 0; text-shadow: 0 0 20px rgba(255,255,255,0.5);">15</h1>
        <h2 id="result-message" style="color: var(--gold-accent); margin-top: 10px;">¡GANASTE $150!</h2>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const board = document.getElementById('bettingBoard');
            const cells = document.querySelectorAll('.board-cell');
            const chips = document.querySelectorAll('.chip-selector .chip');
            const btnClear = document.getElementById('btn-clear');
            const btnUndo = document.getElementById('btn-undo');
            const btnSpin = document.getElementById('btn-spin');
            const btnDouble = document.getElementById('btn-double');
            const btnRebet = document.getElementById('btn-rebet');
            const balanceDisplay = document.getElementById('balanceDisplay');
            const wheelSvg = document.querySelector('.wheel svg');
            
            let selectedChipValue = 0.10;
            let currentBets = []; // Array of { cell: node, type: str, value: str, amount: num, chipNode: node }
            let lastBets = []; // For Rebet
            let isSpinning = false;

            // CSRF Token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Select Chip
            chips.forEach(chip => {
                chip.addEventListener('click', (e) => {
                    chips.forEach(c => c.classList.remove('selected'));
                    chip.classList.add('selected');
                    selectedChipValue = parseFloat(chip.getAttribute('data-value'));
                });
            });

            // Place Bet
            cells.forEach(cell => {
                cell.addEventListener('click', (e) => {
                    if (isSpinning) return;
                    
                    const type = cell.getAttribute('data-bet-type');
                    const value = cell.getAttribute('data-bet-value');

                    // Visual Chip Creation
                    const chipEl = document.createElement('div');
                    chipEl.className = 'chip placed-chip';
                    // Determinar color de ficha
                    if(selectedChipValue <= 0.1) chipEl.style.backgroundColor = '#b0c4de';
                    else if(selectedChipValue <= 0.5) chipEl.style.backgroundColor = '#98fb98';
                    else if(selectedChipValue <= 1) chipEl.style.backgroundColor = '#ffb6c1';
                    else if(selectedChipValue <= 5) chipEl.style.backgroundColor = '#fff';
                    else if(selectedChipValue <= 25) { chipEl.style.backgroundColor = '#555'; chipEl.style.color = '#fff'; }
                    else { chipEl.style.backgroundColor = '#000'; chipEl.style.color = '#fff'; chipEl.style.border = '2px solid #d4af37'; }
                    
                    chipEl.innerText = selectedChipValue < 1 ? selectedChipValue : Math.floor(selectedChipValue);
                    
                    // Posicionamiento en el grid (alojado dentro de la celda es más fácil)
                    cell.appendChild(chipEl);

                    currentBets.push({
                        cell: cell,
                        type: type,
                        value: value,
                        amount: selectedChipValue,
                        chipNode: chipEl
                    });

                    updateTempBalance(-selectedChipValue);
                });
            });

            // Undo
            btnUndo.addEventListener('click', () => {
                if (isSpinning || currentBets.length === 0) return;
                const lastBet = currentBets.pop();
                lastBet.chipNode.remove();
                updateTempBalance(lastBet.amount);
            });

            // Clear
            btnClear.addEventListener('click', () => {
                if (isSpinning) return;
                let refund = 0;
                currentBets.forEach(bet => {
                    bet.chipNode.remove();
                    refund += bet.amount;
                });
                currentBets = [];
                updateTempBalance(refund);
            });

            // Double
            btnDouble.addEventListener('click', () => {
                if (isSpinning || currentBets.length === 0) return;
                const newBets = [];
                let totalCost = 0;
                currentBets.forEach(bet => {
                    const chipEl = bet.chipNode.cloneNode(true);
                    bet.cell.appendChild(chipEl);
                    newBets.push({
                        cell: bet.cell,
                        type: bet.type,
                        value: bet.value,
                        amount: bet.amount,
                        chipNode: chipEl
                    });
                    totalCost += bet.amount;
                });
                currentBets.push(...newBets);
                updateTempBalance(-totalCost);
            });

            // Rebet
            btnRebet.addEventListener('click', () => {
                if (isSpinning || lastBets.length === 0) return;
                btnClear.click();
                lastBets.forEach(betInfo => {
                    // Find the cell
                    const cell = Array.from(cells).find(c => c.getAttribute('data-bet-type') === betInfo.type && c.getAttribute('data-bet-value') === betInfo.value);
                    if(cell) {
                        const chipEl = document.createElement('div');
                        chipEl.className = 'chip placed-chip';
                        chipEl.style.backgroundColor = '#fff'; // Simplificado
                        chipEl.innerText = betInfo.amount;
                        cell.appendChild(chipEl);
                        
                        currentBets.push({
                            cell: cell,
                            type: betInfo.type,
                            value: betInfo.value,
                            amount: betInfo.amount,
                            chipNode: chipEl
                        });
                        updateTempBalance(-betInfo.amount);
                    }
                });
            });

            function updateTempBalance(change) {
                let currentStr = balanceDisplay.innerText.replace(/,/g, '');
                if (currentStr === 'DEMO') return;
                let current = parseFloat(currentStr);
                balanceDisplay.innerText = (current + change).toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 2});
            }

            // --- SPIN LOGIC ---
            // Orden de números en el cilindro para calcular el ángulo de rotación
            const wheelNumbers = [0, 32, 15, 19, 4, 21, 2, 25, 17, 34, 6, 27, 13, 36, 11, 30, 8, 23, 10, 5, 24, 16, 33, 1, 20, 14, 31, 9, 22, 18, 29, 7, 28, 12, 35, 3, 26];

            btnSpin.addEventListener('click', async () => {
                if (isSpinning || currentBets.length === 0) return;
                
                isSpinning = true;
                btnSpin.innerText = '...';

                // Preparar payload
                const payloadBets = currentBets.map(b => ({
                    type: b.type,
                    value: b.value,
                    amount: b.amount
                }));

                try {
                    const response = await fetch('/casino/ruleta/spin', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ bets: payloadBets })
                    });

                    const data = await response.json();

                    if (!data.success) {
                        alert(data.message);
                        isSpinning = false;
                        btnSpin.innerText = 'GIRAR';
                        return;
                    }

                    // Backup for Rebet
                    lastBets = [...currentBets];

                    // Cambiar a cámara de ruleta en móvil
                    if(window.innerWidth <= 900) {
                        document.querySelector('.board-wrapper').style.display = 'none';
                        document.querySelector('.wheel-wrapper').style.display = 'flex';
                    }

                    // Animar Rueda
                    const targetNum = data.winningNumber;
                    const index = wheelNumbers.indexOf(targetNum);
                    
                    // Cada slot es 360/37 = 9.729 grados
                    // En el SVG, el 0 está arriba. Necesitamos rotar para que el index coincida arriba.
                    const slotDegree = 360 / 37;
                    const targetRotation = 360 - (index * slotDegree);
                    
                    // Añadir giros extra (ej: 5 vueltas = 1800 grados)
                    const totalRotation = 1800 + targetRotation;

                    wheelSvg.style.transition = 'transform 4s cubic-bezier(0.25, 1, 0.5, 1)';
                    wheelSvg.style.transform = `rotate(${totalRotation}deg)`;

                    // Esperar a que termine la animación
                    setTimeout(() => {
                        // Reset rotation to exactly the target (without the 1800 extra) to avoid giant numbers over time
                        wheelSvg.style.transition = 'none';
                        wheelSvg.style.transform = `rotate(${targetRotation}deg)`;

                        // Mostrar modal
                        const overlay = document.getElementById('result-overlay');
                        const rNum = document.getElementById('result-number');
                        const rMsg = document.getElementById('result-message');
                        
                        rNum.innerText = targetNum;
                        if(targetNum === 0) rNum.style.color = 'var(--felt-color)';
                        else if(data.isRed) rNum.style.color = 'var(--red-num)';
                        else rNum.style.color = '#fff';

                        if(data.netProfit > 0) {
                            rMsg.innerText = `¡GANASTE $${data.totalWon}!`;
                            rMsg.style.color = 'var(--gold-accent)';
                        } else if (data.totalWon > 0) {
                            rMsg.innerText = `Recuperas $${data.totalWon}`;
                            rMsg.style.color = '#fff';
                        } else {
                            rMsg.innerText = `Suerte en la próxima`;
                            rMsg.style.color = '#aaa';
                        }

                        overlay.style.display = 'flex';
                        balanceDisplay.innerText = data.newBalance.toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 2});

                        // Cerrar modal tras 3 segundos y reset
                        setTimeout(() => {
                            overlay.style.display = 'none';
                            
                            // Limpiar mesa
                            currentBets.forEach(bet => bet.chipNode.remove());
                            currentBets = [];
                            
                            // Volver a cámara de mesa en móvil
                            if(window.innerWidth <= 900) {
                                document.querySelector('.board-wrapper').style.display = 'flex';
                                document.querySelector('.wheel-wrapper').style.display = 'none';
                            }

                            isSpinning = false;
                            btnSpin.innerText = 'GIRAR';
                        }, 3000);

                    }, 4000);

                } catch (error) {
                    alert('Error de conexión. Intenta nuevamente.');
                    isSpinning = false;
                    btnSpin.innerText = 'GIRAR';
                }
            });
        });
    </script>
</body>
</html>
