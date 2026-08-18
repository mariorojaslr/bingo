<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mega Sorteo - Infinity</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background-color: #0b0c10;
            font-family: 'Outfit', sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            user-select: none;
            -webkit-user-select: none;
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
            border: 1px solid #d4af37;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            color: #d4af37;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: 0.3s;
        }
        .balance-box:hover {
            background: rgba(212, 175, 55, 0.2);
            color: #fff;
        }
        .balance-box .add-icon {
            background: #d4af37;
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

        /* --- Hero Jackpot --- */
        .jackpot-container {
            text-align: center;
            padding: 40px 20px;
            background: radial-gradient(circle at center, #1a1c23 0%, #0b0c10 100%);
            border-bottom: 1px solid #222;
        }
        .jackpot-title {
            color: #ff3366;
            text-transform: uppercase;
            letter-spacing: 5px;
            font-weight: 900;
            font-size: 1.5rem;
            margin-bottom: 10px;
            text-shadow: 0 0 15px rgba(255,51,102,0.5);
        }
        .jackpot-amount {
            font-size: 4rem;
            font-weight: 900;
            color: #fff;
            text-shadow: 0 0 20px rgba(255,255,255,0.3);
            margin: 0;
            line-height: 1;
        }
        .draw-info {
            color: #888;
            margin-top: 15px;
            font-size: 1.1rem;
        }
        .draw-info strong {
            color: #00ff88;
        }

        /* --- Number Picker --- */
        .picker-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px 20px;
            flex-grow: 1;
        }
        
        .instructions {
            text-align: center;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        .instructions span {
            color: #00ff88;
            font-weight: bold;
        }

        .numbers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
            gap: 10px;
            margin-bottom: 30px;
        }

        .number-btn {
            background: rgba(255,255,255,0.05);
            border: 2px solid rgba(255,255,255,0.1);
            color: #fff;
            border-radius: 50%;
            width: 100%;
            aspect-ratio: 1/1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .number-btn:hover {
            background: rgba(255,255,255,0.1);
        }

        .number-btn.selected {
            background: #ff3366;
            border-color: #ff3366;
            box-shadow: 0 0 15px rgba(255,51,102,0.5);
            transform: scale(1.1);
        }

        /* --- Controls --- */
        .action-bar {
            background: #111;
            border-top: 1px solid #333;
            padding: 20px;
            position: sticky;
            bottom: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .selected-list {
            display: flex;
            gap: 10px;
        }

        .selected-ball {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #222;
            border: 2px solid #444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            color: #fff;
        }

        .selected-ball.filled {
            background: #ff3366;
            border-color: #ff3366;
            box-shadow: 0 0 10px rgba(255,51,102,0.5);
        }

        .btn-buy {
            background: #00ff88;
            color: #000;
            font-weight: 900;
            font-size: 1.2rem;
            padding: 12px 30px;
            border: none;
            border-radius: 30px;
            text-transform: uppercase;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-buy:disabled {
            background: #444;
            color: #888;
            cursor: not-allowed;
            box-shadow: none;
        }
        .btn-buy:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,255,136,0.4);
        }
        
        .btn-random {
            background: transparent;
            color: #00ff88;
            border: 1px solid #00ff88;
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-random:hover {
            background: rgba(0,255,136,0.1);
        }

        @media (max-width: 768px) {
            .jackpot-amount { font-size: 3rem; }
            .numbers-grid { grid-template-columns: repeat(auto-fill, minmax(50px, 1fr)); }
            .number-btn { font-size: 1.2rem; }
            .action-bar { flex-direction: column; gap: 20px; }
            .selected-list { order: -1; }
        }
    </style>
</head>
<body>

    <header class="game-header">
        <a href="{{ route('lobby.index') }}" class="text-white text-decoration-none">
            <i class="bi bi-chevron-left"></i> Lobby
        </a>
        <div class="fw-bold text-uppercase" style="letter-spacing: 2px; color: #ff3366;">
            Mega Sorteo
        </div>
        <a href="{{ route('cajero.show') }}?t={{ isset($participanteLogueado) ? $participanteLogueado->telefono : '' }}" class="balance-box" title="Cargar Fichas">
            <i class="bi bi-gem"></i> <span id="balanceDisplay">{{ isset($participanteLogueado) ? number_format($participanteLogueado->saldo_fichas, 0) : 'DEMO' }}</span>
            <div class="add-icon"><i class="bi bi-plus-lg"></i></div>
        </a>
    </header>

    <div class="jackpot-container">
        <div class="jackpot-title">Pozo Acumulado</div>
        <h1 class="jackpot-amount" id="jackpotAmount">${{ number_format($nextSorteo->accumulated_jackpot, 0) }}</h1>
        <div class="draw-info">
            Próximo Sorteo: <strong>{{ $nextSorteo->draw_date->format('d/m/Y H:i') }}</strong><br>
            Valor del Ticket: <strong class="text-white">${{ number_format($nextSorteo->ticket_price, 0) }}</strong>
        </div>
    </div>

    <div class="picker-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="instructions">Selecciona <span id="countDisplay">0/6</span> números</div>
            <button class="btn-random" id="btnRandom"><i class="bi bi-shuffle"></i> Aleatorio</button>
        </div>

        <div class="numbers-grid" id="numbersGrid">
            @for($i = 0; $i <= 45; $i++)
                <div class="number-btn" data-number="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</div>
            @endfor
        </div>
    </div>

    <div class="action-bar">
        <div class="selected-list" id="selectedList">
            <div class="selected-ball" id="ball-0"></div>
            <div class="selected-ball" id="ball-1"></div>
            <div class="selected-ball" id="ball-2"></div>
            <div class="selected-ball" id="ball-3"></div>
            <div class="selected-ball" id="ball-4"></div>
            <div class="selected-ball" id="ball-5"></div>
        </div>
        
        <div class="d-flex gap-3 align-items-center">
            <a href="{{ route('casino.megasorteo.mytickets') }}" class="text-white text-decoration-none border-bottom border-secondary pb-1">Mis Tickets</a>
            <button class="btn-buy" id="btnBuy" disabled>Comprar Ticket</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btns = document.querySelectorAll('.number-btn');
            const countDisplay = document.getElementById('countDisplay');
            const btnBuy = document.getElementById('btnBuy');
            const btnRandom = document.getElementById('btnRandom');
            const balanceDisplay = document.getElementById('balanceDisplay');
            const jackpotAmount = document.getElementById('jackpotAmount');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const sorteoId = {{ $nextSorteo->id }};
            const ticketPrice = {{ $nextSorteo->ticket_price }};
            let selectedNumbers = [];

            function updateUI() {
                // Update grid
                btns.forEach(btn => {
                    const num = parseInt(btn.getAttribute('data-number'));
                    if (selectedNumbers.includes(num)) {
                        btn.classList.add('selected');
                    } else {
                        btn.classList.remove('selected');
                    }
                });

                // Update text
                countDisplay.innerText = `${selectedNumbers.length}/6`;

                // Update balls
                const sorted = [...selectedNumbers].sort((a,b) => a-b);
                for(let i=0; i<6; i++) {
                    const ball = document.getElementById(`ball-${i}`);
                    if (i < sorted.length) {
                        ball.innerText = sorted[i].toString().padStart(2, '0');
                        ball.classList.add('filled');
                    } else {
                        ball.innerText = '';
                        ball.classList.remove('filled');
                    }
                }

                // Enable/Disable Buy
                btnBuy.disabled = selectedNumbers.length !== 6;
            }

            btns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const num = parseInt(btn.getAttribute('data-number'));
                    
                    if (selectedNumbers.includes(num)) {
                        selectedNumbers = selectedNumbers.filter(n => n !== num);
                    } else {
                        if (selectedNumbers.length < 6) {
                            selectedNumbers.push(num);
                        }
                    }
                    updateUI();
                });
            });

            btnRandom.addEventListener('click', () => {
                selectedNumbers = [];
                while(selectedNumbers.length < 6) {
                    const r = Math.floor(Math.random() * 46);
                    if(!selectedNumbers.includes(r)) {
                        selectedNumbers.push(r);
                    }
                }
                updateUI();
            });

            btnBuy.addEventListener('click', async () => {
                if (selectedNumbers.length !== 6) return;

                const currentBalance = parseFloat(balanceDisplay.innerText.replace(/,/g, ''));
                if (isNaN(currentBalance)) {
                    alert("Por favor inicia sesión para comprar.");
                    return;
                }

                if (currentBalance < ticketPrice) {
                    alert(`Saldo insuficiente. Necesitas $${ticketPrice} fichas.`);
                    return;
                }

                btnBuy.disabled = true;
                btnBuy.innerText = 'PROCESANDO...';

                try {
                    const response = await fetch(`/casino/mega-sorteo/buy/${sorteoId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ numbers: selectedNumbers })
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert(data.message);
                        // Update balance and jackpot
                        balanceDisplay.innerText = data.new_balance.toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 2});
                        jackpotAmount.innerText = "$" + data.new_jackpot.toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 2});
                        
                        // Reset
                        selectedNumbers = [];
                        updateUI();
                    } else {
                        alert(data.message);
                    }
                } catch (err) {
                    alert("Error de conexión");
                }

                btnBuy.innerText = 'Comprar Ticket';
                updateUI();
            });
        });
    </script>
</body>
</html>
