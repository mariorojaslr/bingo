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
            --table-color: #0b3d1f;
            --table-border: #8b5a2b;
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

        .hand-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 10;
            min-height: 150px;
        }

        .dealer-area { margin-top: 10px; }
        .player-area { margin-bottom: 20px; }

        .score-badge {
            background: rgba(0,0,0,0.6);
            color: #fff;
            padding: 3px 12px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 15px;
            border: 1px solid rgba(255,255,255,0.2);
            display: none;
        }

        .score-badge.player-score { margin-bottom: 0; margin-top: 15px; }

        .cards-container {
            display: flex;
            gap: -40px; 
            transition: all 0.3s;
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
            margin-right: -40px;
            transition: transform 0.3s ease, margin 0.3s ease;
            animation: slideIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        
        @keyframes slideIn {
            from { transform: translateY(-50px) scale(0.8); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }

        .playing-card:last-child { margin-right: 0; }
        .playing-card:hover { transform: translateY(-10px); }

        .card-red { color: #d00; }
        .card-black { color: #000; }

        .card-value { font-size: 1.2rem; font-weight: 900; line-height: 1; }
        .card-suit { font-size: 1.5rem; text-align: center; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }

        .card-back {
            background: repeating-linear-gradient(45deg, #0f4c75, #0f4c75 10px, #1b262c 10px, #1b262c 20px);
            border: 4px solid #fff;
            color: transparent;
        }

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
            cursor: pointer;
            transition: 0.2s;
        }
        .bet-circle:hover { background: rgba(255,255,255,0.1); }
        .bet-circle::after {
            content: 'PLACE BET';
            position: absolute;
            color: rgba(255,255,255,0.2);
            font-size: 0.7rem;
            text-align: center;
            pointer-events: none;
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
            cursor: pointer;
            transition: 0.2s;
        }
        .chip:hover { transform: scale(1.1); }

        .chip-10 { background: #3498db; }
        .chip-50 { background: #e74c3c; }
        .chip-100 { background: #2c3e50; }
        .chip-500 { background: #9b59b6; }

        .chip-stack {
            position: absolute;
            display: flex;
            flex-direction: column;
            align-items: center;
            pointer-events: none;
        }
        
        .chip-stack .chip { position: absolute; pointer-events: all; }

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
        .btn-action:disabled { filter: grayscale(1); opacity: 0.5; pointer-events: none; }
        .btn-action:active { transform: translateY(4px); box-shadow: 0 0 0 rgba(0,0,0,0); }
        .btn-deal { background: #3498db; color: #fff; }
        .btn-hit { background: #2ecc71; }
        .btn-stand { background: #e74c3c; color: #fff; }
        .btn-double { background: #f1c40f; }
        .btn-clear { background: #7f8c8d; color: #fff; }
        .btn-action i { font-size: 1.5rem; margin-bottom: 2px; }

        .game-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.85);
            padding: 20px 40px;
            border-radius: 20px;
            border: 2px solid var(--gold-accent);
            color: var(--gold-accent);
            font-size: 3rem;
            font-weight: 900;
            text-align: center;
            z-index: 999;
            box-shadow: 0 0 50px rgba(212, 175, 55, 0.4);
            display: none;
            flex-direction: column;
        }
        .game-message.show { display: flex; animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes popIn { from { transform: translate(-50%, -50%) scale(0.5); opacity: 0; } to { transform: translate(-50%, -50%) scale(1); opacity: 1; } }

        .bet-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            justify-content: center;
            display: none; /* hidden when playing */
        }

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

    <header class="game-header">
        <a href="javascript:history.back()" class="text-white text-decoration-none">
            <i class="bi bi-chevron-left"></i> Lobby
        </a>
        <div class="fw-bold" style="letter-spacing: 2px; color: var(--gold-accent);">INFINITY CASINO</div>
        <div class="balance-box">
            $<span id="balanceDisplay">5000</span>
        </div>
    </header>

    <main class="blackjack-table">
        <div class="table-edge"></div>
        <div class="table-logo">INFINITY BLACKJACK</div>
        
        <div class="insurance-line">
            <span>Insurance Pays 2 to 1</span>
        </div>

        <!-- Mensaje de Resultado -->
        <div id="gameMessage" class="game-message">
            <span id="msgTitle">YOU WIN</span>
            <small id="msgSub" class="text-white fs-5 mt-2">+$200</small>
        </div>

        <div class="hand-area dealer-area">
            <div id="dealerScoreBadge" class="score-badge">0</div>
            <div id="dealerCards" class="cards-container"></div>
        </div>

        <div class="hand-area player-area">
            
            <div id="betZone" class="bet-circle" onclick="placeBet()">
                <div id="chipStack" class="chip-stack"></div>
            </div>

            <!-- Fichas para elegir (Solo en fase apuesta) -->
            <div id="betSelector" class="bet-selector">
                <div class="chip chip-10" onclick="selectChip(10)">10</div>
                <div class="chip chip-50" onclick="selectChip(50)">50</div>
                <div class="chip chip-100" onclick="selectChip(100)">100</div>
                <div class="chip chip-500" onclick="selectChip(500)">500</div>
            </div>

            <div id="playerCards" class="cards-container mt-3"></div>
            <div id="playerScoreBadge" class="score-badge player-score">0</div>
        </div>
    </main>

    <div class="game-controls">
        <button id="btnDeal" class="btn-action btn-deal" onclick="deal()" disabled>
            <i class="bi bi-play-circle-fill"></i><span>DEAL</span>
        </button>
        <button id="btnClear" class="btn-action btn-clear" onclick="clearBet()">
            <i class="bi bi-trash"></i><span>CLEAR</span>
        </button>

        <button id="btnHit" class="btn-action btn-hit" style="display:none;" onclick="hit()">
            <i class="bi bi-plus-square"></i><span>HIT</span>
        </button>
        <button id="btnStand" class="btn-action btn-stand" style="display:none;" onclick="stand()">
            <i class="bi bi-dash-square"></i><span>STAND</span>
        </button>
        <button id="btnDouble" class="btn-action btn-double" style="display:none;" onclick="doubleDown()">
            <i class="bi bi-x-square"></i><span>DOUBLE</span>
        </button>
    </div>

    <script>
        // Logica de Blackjack Simple
        const suits = ['♥', '♦', '♣', '♠'];
        const values = ['2','3','4','5','6','7','8','9','10','J','Q','K','A'];
        
        let deck = [];
        let playerHand = [];
        let dealerHand = [];
        let balance = 5000;
        let currentBet = 0;
        let selectedChip = 10;
        let gameState = 'betting'; // betting, playing, ended

        function initDeck() {
            deck = [];
            for(let s of suits) {
                for(let v of values) {
                    deck.push({suit: s, value: v, weight: getWeight(v)});
                }
            }
            deck = deck.sort(() => Math.random() - 0.5); // shuffle
        }

        function getWeight(value) {
            if(['J','Q','K'].includes(value)) return 10;
            if(value === 'A') return 11;
            return parseInt(value);
        }

        function getScore(hand) {
            let score = 0;
            let aces = 0;
            for(let c of hand) {
                score += c.weight;
                if(c.value === 'A') aces++;
            }
            while(score > 21 && aces > 0) {
                score -= 10;
                aces--;
            }
            return score;
        }

        function renderCard(card, isHidden = false) {
            if (isHidden) {
                return `<div class="playing-card card-back"></div>`;
            }
            let colorClass = (card.suit === '♥' || card.suit === '♦') ? 'card-red' : 'card-black';
            return `
                <div class="playing-card ${colorClass}">
                    <div class="card-value">${card.value}</div>
                    <div class="card-suit">${card.suit}</div>
                    <div class="card-value" style="position:absolute; bottom:8px; right:8px; transform: rotate(180deg);">${card.value}</div>
                </div>
            `;
        }

        // --- SISTEMA DE APUESTAS ---
        function updateBalanceUI() {
            document.getElementById('balanceDisplay').innerText = balance;
        }

        function selectChip(val) {
            selectedChip = val;
            // Visual feedback could be added
        }

        function placeBet() {
            if(gameState !== 'betting') return;
            if(balance >= selectedChip) {
                balance -= selectedChip;
                currentBet += selectedChip;
                updateBalanceUI();
                renderChips();
                document.getElementById('btnDeal').disabled = false;
                document.getElementById('btnClear').disabled = false;
            }
        }

        function clearBet() {
            if(gameState !== 'betting') return;
            balance += currentBet;
            currentBet = 0;
            updateBalanceUI();
            renderChips();
            document.getElementById('btnDeal').disabled = true;
            document.getElementById('btnClear').disabled = true;
        }

        function renderChips() {
            const stack = document.getElementById('chipStack');
            stack.innerHTML = '';
            if (currentBet === 0) return;
            // Simplified stacking: just show one chip representing the total or a few
            stack.innerHTML = `<div class="chip chip-100" style="bottom: 0px;">${currentBet}</div>`;
        }

        // --- JUEGO ---
        function deal() {
            if(currentBet === 0) return;
            gameState = 'playing';
            initDeck();
            
            // Switch UI
            document.getElementById('btnDeal').style.display = 'none';
            document.getElementById('btnClear').style.display = 'none';
            document.getElementById('betSelector').style.display = 'none';
            document.getElementById('btnHit').style.display = 'flex';
            document.getElementById('btnStand').style.display = 'flex';
            if (balance >= currentBet) document.getElementById('btnDouble').style.display = 'flex';

            document.getElementById('gameMessage').classList.remove('show');

            playerHand = [deck.pop(), deck.pop()];
            dealerHand = [deck.pop(), deck.pop()];

            renderHands(true);

            // Check immediate Blackjack
            if(getScore(playerHand) === 21) {
                endGame('blackjack');
            }
        }

        function renderHands(hideDealerSecond = false) {
            document.getElementById('playerCards').innerHTML = playerHand.map(c => renderCard(c)).join('');
            document.getElementById('playerScoreBadge').innerText = getScore(playerHand);
            document.getElementById('playerScoreBadge').style.display = 'block';

            if(hideDealerSecond) {
                document.getElementById('dealerCards').innerHTML = renderCard(dealerHand[0]) + renderCard(dealerHand[1], true);
                document.getElementById('dealerScoreBadge').innerText = getWeight(dealerHand[0].value);
            } else {
                document.getElementById('dealerCards').innerHTML = dealerHand.map(c => renderCard(c)).join('');
                document.getElementById('dealerScoreBadge').innerText = getScore(dealerHand);
            }
            document.getElementById('dealerScoreBadge').style.display = 'block';
        }

        function hit() {
            document.getElementById('btnDouble').style.display = 'none'; // Only allowed on first move
            playerHand.push(deck.pop());
            renderHands(true);
            if(getScore(playerHand) > 21) {
                endGame('bust');
            } else if (getScore(playerHand) === 21) {
                stand();
            }
        }

        function doubleDown() {
            if (balance >= currentBet) {
                balance -= currentBet;
                currentBet *= 2;
                updateBalanceUI();
                renderChips();
                playerHand.push(deck.pop());
                renderHands(true);
                if(getScore(playerHand) > 21) endGame('bust');
                else stand();
            }
        }

        async function stand() {
            document.getElementById('btnHit').style.display = 'none';
            document.getElementById('btnStand').style.display = 'none';
            document.getElementById('btnDouble').style.display = 'none';
            
            renderHands(false); // Reveal dealer card
            
            // Dealer draws
            while(getScore(dealerHand) < 17) {
                await new Promise(r => setTimeout(r, 800)); // Delay for effect
                dealerHand.push(deck.pop());
                renderHands(false);
            }

            const pScore = getScore(playerHand);
            const dScore = getScore(dealerHand);

            if(dScore > 21) endGame('dealerBust');
            else if(pScore > dScore) endGame('win');
            else if(pScore < dScore) endGame('lose');
            else endGame('push');
        }

        function endGame(reason) {
            gameState = 'ended';
            renderHands(false); // Make sure dealer hand is revealed
            
            document.getElementById('btnHit').style.display = 'none';
            document.getElementById('btnStand').style.display = 'none';
            document.getElementById('btnDouble').style.display = 'none';

            let msg = '';
            let subMsg = '';
            let winAmount = 0;

            if(reason === 'blackjack') {
                msg = 'BLACKJACK!';
                winAmount = currentBet + (currentBet * 1.5);
                balance += winAmount;
                subMsg = '+$' + winAmount;
            } else if (reason === 'win' || reason === 'dealerBust') {
                msg = reason === 'dealerBust' ? 'DEALER BUSTS' : 'YOU WIN';
                winAmount = currentBet * 2;
                balance += winAmount;
                subMsg = '+$' + winAmount;
            } else if (reason === 'push') {
                msg = 'PUSH';
                balance += currentBet;
                subMsg = 'Bet Returned';
            } else {
                msg = 'YOU LOSE';
                subMsg = '-$' + currentBet;
            }

            updateBalanceUI();

            const msgBox = document.getElementById('gameMessage');
            document.getElementById('msgTitle').innerText = msg;
            document.getElementById('msgSub').innerText = subMsg;
            msgBox.classList.add('show');

            // Reset UI for next hand after a delay
            setTimeout(() => {
                currentBet = 0;
                renderChips();
                document.getElementById('btnDeal').style.display = 'flex';
                document.getElementById('btnClear').style.display = 'flex';
                document.getElementById('betSelector').style.display = 'flex';
                document.getElementById('btnDeal').disabled = true;
                document.getElementById('btnClear').disabled = true;
                gameState = 'betting';
                
                // Clear cards visual
                document.getElementById('playerCards').innerHTML = '';
                document.getElementById('dealerCards').innerHTML = '';
                document.getElementById('playerScoreBadge').style.display = 'none';
                document.getElementById('dealerScoreBadge').style.display = 'none';
                msgBox.classList.remove('show');
            }, 3000);
        }

        // Init
        document.getElementById('betSelector').style.display = 'flex';
        updateBalanceUI();

    </script>
</body>
</html>
