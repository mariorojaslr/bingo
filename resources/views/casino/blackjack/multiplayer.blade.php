<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Blackjack Infinity - Multiplayer</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --table-green: #0a4f27;
            --felt-border: #4d2b18;
            --gold-accent: #d4af37;
        }

        body {
            background-color: #0b0c10;
            font-family: 'Outfit', sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            user-select: none;
        }

        /* Header */
        .game-header {
            background: rgba(0,0,0,0.8);
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

        /* Table */
        .casino-table {
            flex-grow: 1;
            background: radial-gradient(circle at center, #11783f 0%, var(--table-green) 100%);
            border: 20px solid var(--felt-border);
            border-radius: 100px;
            margin: 20px;
            position: relative;
            box-shadow: inset 0 0 50px rgba(0,0,0,0.8), 0 10px 30px rgba(0,0,0,0.8);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .table-logo {
            position: absolute;
            top: 40%;
            opacity: 0.1;
            font-size: 4rem;
            font-weight: 900;
            letter-spacing: 5px;
            pointer-events: none;
            text-align: center;
        }

        /* Dealer */
        .dealer-area {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            height: 120px;
        }
        .cards-container {
            display: flex;
            gap: -20px;
        }
        .card {
            width: 70px;
            height: 100px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #000;
            margin-left: -30px;
            position: relative;
        }
        .card:first-child { margin-left: 0; }
        .card.red { color: #d32f2f; }
        .card.hidden {
            background: repeating-linear-gradient(45deg, #111, #111 10px, #333 10px, #333 20px);
            border: 2px solid #fff;
        }
        .hand-value {
            background: rgba(0,0,0,0.6);
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 0.9rem;
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* Seats */
        .seats-container {
            position: absolute;
            bottom: 20px;
            width: 100%;
            display: flex;
            justify-content: space-evenly;
            align-items: flex-end;
            padding: 0 40px;
        }

        .seat {
            width: 120px;
            height: 140px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            position: relative;
            border: 2px dashed rgba(255,255,255,0.2);
            border-radius: 50%;
            padding: 10px;
            transition: all 0.3s;
        }
        .seat.active {
            border-style: solid;
            border-color: var(--gold-accent);
            background: rgba(255,255,255,0.05);
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
        }
        .seat.current-turn {
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.6);
            border-color: #00ff88;
        }

        .seat-player-name {
            font-size: 0.8rem;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            margin-top: 5px;
        }

        .seat-bet {
            position: absolute;
            top: -30px;
            font-size: 0.9rem;
            font-weight: bold;
            color: #00ff88;
            background: rgba(0,0,0,0.8);
            padding: 2px 8px;
            border-radius: 10px;
            border: 1px solid #00ff88;
        }

        .seat-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid #fff;
            color: #fff;
            border-radius: 20px;
            padding: 5px 15px;
            cursor: pointer;
        }
        .seat-btn:hover { background: rgba(255,255,255,0.2); }

        .seat-cards {
            position: absolute;
            bottom: 50px;
            display: flex;
            gap: -20px;
            z-index: 10;
        }
        .seat-cards .card {
            width: 50px;
            height: 70px;
            font-size: 1.1rem;
            margin-left: -20px;
        }
        .seat-cards .card:first-child { margin-left: 0; }

        .seat-result {
            position: absolute;
            top: 40%;
            background: rgba(0,0,0,0.8);
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
            z-index: 20;
            font-size: 0.8rem;
        }

        /* Controls */
        .controls-bar {
            background: #111;
            padding: 15px;
            display: flex;
            justify-content: center;
            gap: 10px;
            border-top: 2px solid #333;
        }
        .btn-action {
            background: #333;
            color: #fff;
            border: 1px solid #555;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
        }
        .btn-action:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .btn-hit { background: #1976d2; border-color: #1565c0; }
        .btn-stand { background: #d32f2f; border-color: #c62828; }
        .btn-bet { background: #388e3c; border-color: #2e7d32; }

    </style>
</head>
<body>

    <header class="game-header">
        <a href="{{ route('lobby.index') }}" class="text-white text-decoration-none">
            <i class="bi bi-chevron-left"></i> Lobby
        </a>
        <div class="fw-bold" style="letter-spacing: 2px; color: var(--gold-accent);">
            {{ $table->name }}
        </div>
        <a href="{{ route('cajero.show') }}?t={{ isset($participanteLogueado) ? $participanteLogueado->telefono : '' }}" class="balance-box" title="Cargar Fichas">
            <i class="bi bi-gem"></i> <span id="balanceDisplay">{{ isset($participanteLogueado) ? number_format($participanteLogueado->saldo_fichas, 0) : 'DEMO' }}</span>
            <div class="add-icon"><i class="bi bi-plus-lg"></i></div>
        </a>
    </header>

    <div class="casino-table">
        <div class="table-logo">INFINITY<br>BLACKJACK</div>

        <!-- Dealer -->
        <div class="dealer-area">
            <div class="cards-container" id="dealerCards">
                <!-- Cards injected via JS -->
            </div>
            <div class="hand-value" id="dealerValue" style="display: none;">0</div>
        </div>

        <!-- Seats -->
        <div class="seats-container" id="seatsContainer">
            <!-- Seats injected via JS -->
        </div>
    </div>

    <!-- Controls -->
    <div class="controls-bar" id="controlsBar">
        <button class="btn-action" id="btnLeave" style="display:none;">Levantarse</button>
        <button class="btn-action btn-bet" id="btnBet10" style="display:none;">Apostar 10</button>
        <button class="btn-action btn-bet" id="btnBet50" style="display:none;">Apostar 50</button>
        <button class="btn-action" id="btnReady" style="display:none;">Repartir (Ready)</button>
        
        <button class="btn-action btn-hit" id="btnHit" style="display:none;">Pedir Carta</button>
        <button class="btn-action btn-stand" id="btnStand" style="display:none;">Plantarse</button>
        
        <button class="btn-action" id="btnClear" style="display:none;">Nueva Ronda</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tableId = {{ $table->id }};
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const myPlayerId = {{ $participanteLogueado ? $participanteLogueado->id : 'null' }};
            
            const seatsContainer = document.getElementById('seatsContainer');
            const dealerCards = document.getElementById('dealerCards');
            const dealerValue = document.getElementById('dealerValue');
            const balanceDisplay = document.getElementById('balanceDisplay');
            
            const btnLeave = document.getElementById('btnLeave');
            const btnBet10 = document.getElementById('btnBet10');
            const btnBet50 = document.getElementById('btnBet50');
            const btnReady = document.getElementById('btnReady');
            const btnHit = document.getElementById('btnHit');
            const btnStand = document.getElementById('btnStand');
            const btnClear = document.getElementById('btnClear');

            let amISeated = false;
            let mySeat = null;

            // Render Card Helper
            function renderCard(card) {
                if (card.suit === 'hidden') return `<div class="card hidden"></div>`;
                const isRed = ['hearts', 'diamonds'].includes(card.suit);
                const suitSym = { hearts: '♥', diamonds: '♦', clubs: '♣', spades: '♠' }[card.suit];
                return `<div class="card ${isRed ? 'red' : ''}">${card.value}<br>${suitSym}</div>`;
            }

            // Fetch State
            async function fetchState() {
                try {
                    const res = await fetch(`/casino/blackjack/state/${tableId}`);
                    const data = await res.json();
                    renderTable(data);
                } catch(e) { console.error(e); }
            }

            // Render State
            function renderTable(data) {
                const table = data.table;
                const seats = data.seats;

                // Dealer
                dealerCards.innerHTML = '';
                if (table.dealer_hand) {
                    table.dealer_hand.forEach(c => dealerCards.innerHTML += renderCard(c));
                    if (table.dealer_value) {
                        dealerValue.innerText = table.dealer_value;
                        dealerValue.style.display = 'block';
                    } else {
                        dealerValue.style.display = 'none';
                    }
                } else {
                    dealerValue.style.display = 'none';
                }

                // Seats
                seatsContainer.innerHTML = '';
                amISeated = false;
                mySeat = null;

                seats.forEach(seat => {
                    const isMe = seat.player && seat.player.id === myPlayerId;
                    if(isMe) { amISeated = true; mySeat = seat; balanceDisplay.innerText = seat.player.saldo; }

                    const el = document.createElement('div');
                    el.className = `seat ${seat.status !== 'empty' ? 'active' : ''} ${table.current_turn_seat === seat.seat_number ? 'current-turn' : ''}`;
                    
                    let innerHTML = '';
                    if (seat.status === 'empty') {
                        innerHTML += `<button class="seat-btn" onclick="joinSeat(${seat.seat_number})">Sentarse</button>`;
                    } else {
                        if (seat.bet_amount > 0) {
                            innerHTML += `<div class="seat-bet">$${seat.bet_amount}</div>`;
                        }
                        if (seat.hand && seat.hand.length > 0) {
                            let cardsHtml = '<div class="seat-cards">';
                            seat.hand.forEach(c => cardsHtml += renderCard(c));
                            cardsHtml += '</div>';
                            innerHTML += cardsHtml;
                            innerHTML += `<div class="hand-value" style="margin-bottom: 15px;">${seat.hand_value}</div>`;
                        }
                        if (seat.result) {
                            const colors = {win: '#00ff88', loss: '#ff3366', push: '#aaa', blackjack: '#d4af37'};
                            innerHTML += `<div class="seat-result" style="color: ${colors[seat.result]}">${seat.result}</div>`;
                        }
                        innerHTML += `<div class="seat-player-name">${seat.player.name}</div>`;
                    }
                    el.innerHTML = innerHTML;
                    seatsContainer.appendChild(el);
                });

                updateControls(table);
            }

            function updateControls(table) {
                btnLeave.style.display = 'none';
                btnBet10.style.display = 'none';
                btnBet50.style.display = 'none';
                btnReady.style.display = 'none';
                btnHit.style.display = 'none';
                btnStand.style.display = 'none';
                btnClear.style.display = 'none';

                if (amISeated) {
                    if (table.status === 'waiting_bets') {
                        if (mySeat.status === 'waiting' || mySeat.status === 'empty') {
                            btnLeave.style.display = 'inline-block';
                            btnBet10.style.display = 'inline-block';
                            btnBet50.style.display = 'inline-block';
                        } else if (mySeat.status === 'betting') {
                            btnReady.style.display = 'inline-block';
                        }
                    } else if (table.status === 'playing') {
                        if (mySeat.status === 'playing' && table.current_turn_seat === mySeat.seat_number) {
                            btnHit.style.display = 'inline-block';
                            btnStand.style.display = 'inline-block';
                        }
                    } else if (table.status === 'finished') {
                        btnClear.style.display = 'inline-block';
                        btnLeave.style.display = 'inline-block';
                    }
                }
            }

            // Expose for onClick
            window.joinSeat = async function(seatNum) {
                try {
                    await fetch(`/casino/blackjack/sit/${tableId}/${seatNum}`, {
                        method: 'POST', headers: {'X-CSRF-TOKEN': csrfToken}
                    });
                    fetchState();
                } catch(e) {}
            };

            btnLeave.onclick = async () => {
                await fetch(`/casino/blackjack/leave/${tableId}`, { method: 'POST', headers: {'X-CSRF-TOKEN': csrfToken} });
                fetchState();
            };

            async function placeBet(amt) {
                await fetch(`/casino/blackjack/bet/${tableId}`, {
                    method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken},
                    body: JSON.stringify({amount: amt})
                });
                fetchState();
            }
            btnBet10.onclick = () => placeBet(10);
            btnBet50.onclick = () => placeBet(50);

            btnReady.onclick = async () => {
                await fetch(`/casino/blackjack/deal/${tableId}`, { method: 'POST', headers: {'X-CSRF-TOKEN': csrfToken} });
                fetchState();
            };

            btnHit.onclick = async () => {
                await fetch(`/casino/blackjack/hit/${tableId}`, { method: 'POST', headers: {'X-CSRF-TOKEN': csrfToken} });
                fetchState();
            };

            btnStand.onclick = async () => {
                await fetch(`/casino/blackjack/stand/${tableId}`, { method: 'POST', headers: {'X-CSRF-TOKEN': csrfToken} });
                fetchState();
            };

            btnClear.onclick = async () => {
                await fetch(`/casino/blackjack/clear/${tableId}`, { method: 'POST', headers: {'X-CSRF-TOKEN': csrfToken} });
                fetchState();
            };

            // Polling
            setInterval(fetchState, 2000);
            fetchState();
        });
    </script>
</body>
</html>
