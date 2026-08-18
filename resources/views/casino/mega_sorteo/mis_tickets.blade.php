<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Mis Tickets - Mega Sorteo</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background-color: #0b0c10;
            font-family: 'Outfit', sans-serif;
            color: #fff;
        }

        .game-header {
            background: rgba(0,0,0,0.8);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 15px 20px;
            display: flex;
            align-items: center;
        }

        .ticket-card {
            background: #1a1c23;
            border: 1px solid #333;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #d4af37; color: #000; }
        .status-drawn { background: #00ff88; color: #000; }
        
        .numbers-row {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .ball {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ff3366;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 0 10px rgba(255,51,102,0.3);
        }

        .results {
            background: rgba(0,0,0,0.3);
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            color: #aaa;
        }
    </style>
</head>
<body>

    <header class="game-header gap-3">
        <a href="{{ route('casino.megasorteo.index') }}" class="text-white text-decoration-none">
            <i class="bi bi-chevron-left"></i> Volver
        </a>
        <h4 class="m-0 text-uppercase" style="color: #ff3366; font-weight: 900;">Mis Tickets</h4>
    </header>

    <div class="container mt-4 pb-5">
        @if($tickets->isEmpty())
            <div class="text-center text-muted mt-5">
                <i class="bi bi-ticket-perforated" style="font-size: 4rem;"></i>
                <p class="mt-3">Aún no has comprado ningún ticket para el Mega Sorteo.</p>
                <a href="{{ route('casino.megasorteo.index') }}" class="btn btn-outline-light mt-2">Comprar Ahora</a>
            </div>
        @else
            <div class="row">
                @foreach($tickets as $ticket)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="ticket-card">
                            <div class="ticket-header">
                                <div>
                                    <div class="text-muted" style="font-size: 0.9rem;">Sorteo #{{ $ticket->megaSorteo->id }}</div>
                                    <div class="fw-bold">{{ $ticket->megaSorteo->draw_date->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="status-badge status-{{ $ticket->megaSorteo->status }}">
                                    {{ $ticket->megaSorteo->status == 'pending' ? 'Pendiente' : 'Sorteado' }}
                                </div>
                            </div>
                            
                            <div class="numbers-row">
                                @foreach($ticket->numbers as $num)
                                    <div class="ball">{{ str_pad($num, 2, '0', STR_PAD_LEFT) }}</div>
                                @endforeach
                            </div>

                            @if($ticket->megaSorteo->status == 'drawn')
                                <div class="results">
                                    Aciertos: <strong class="text-white">{{ $ticket->hits }}</strong><br>
                                    Premio: <strong style="color: #00ff88;">${{ number_format($ticket->won_amount, 2) }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</body>
</html>
