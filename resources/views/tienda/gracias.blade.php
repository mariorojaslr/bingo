<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>¡Ticket de Acceso Digital! | Infinity Bingo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { 
            background: #020202; color: #fff; font-family: 'Inter', sans-serif;
            background-image: radial-gradient(circle at 50% 10%, rgba(212, 175, 55, 0.15) 0%, transparent 60%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .ticket-glass {
            background: rgba(20, 20, 25, 0.9); border: 2px solid rgba(212, 175, 55, 0.3);
            border-radius: 20px; padding: 40px; box-shadow: 0 20px 50px rgba(0,0,0,0.8), 0 0 30px rgba(212,175,55,0.1);
            backdrop-filter: blur(15px); max-width: 500px; width: 100%; text-align: center;
            position: relative; overflow: hidden;
        }
        /* Rayas de ticket falso (punch hole) */
        .ticket-glass::before, .ticket-glass::after {
            content: ''; position: absolute; top: 60%; width: 40px; height: 40px;
            background: #020202; border-radius: 50%; box-shadow: inset 0 2px 5px rgba(255,255,255,0.1);
        }
        .ticket-glass::before { left: -20px; }
        .ticket-glass::after { right: -20px; }

        .btn-play {
            display: block; width: 100%; text-decoration: none; text-align: center;
            background: #00FF88; color: #000; font-family: 'Outfit'; font-weight: 900;
            font-size: 1.4rem; padding: 20px; border-radius: 50px; text-transform: uppercase; letter-spacing: 2px;
            box-shadow: 0 5px 25px rgba(0,255,136,0.4); transition: 0.3s; margin-top: 30px;
        }
        .btn-play:hover { background: #fff; box-shadow: 0 10px 40px rgba(0,255,136,0.8); transform: translateY(-5px); color: #000; }
        .token-box { background: rgba(0,0,0,0.6); border: 1px dashed rgba(255,255,255,0.2); border-radius: 12px; padding: 15px; font-family: 'monospace'; font-size: 1.2rem; color: #D4AF37; margin: 20px 0; letter-spacing: 3px; }
    </style>
</head>
<body>

<div class="px-3" style="width: 100%;">
    <div class="ticket-glass mx-auto">
        <i class="bi bi-check-circle-fill mb-3" style="font-size: 4rem; color: #00FF88;"></i>
        <h2 class="fw-bold fs-1" style="font-family: 'Outfit'; text-transform: uppercase;">¡COMPRA EXITOSA!</h2>
        <p class="text-white-50">Hola <span class="text-white fw-bold">{{ $participante->nombre }}</span>, tus cartones han sido generados y vinculados a tu cuenta digital.</p>
        
        <div class="px-2 pb-4 pt-3 mt-4" style="border-top: 1px dashed rgba(255,255,255,0.1); border-bottom: 1px dashed rgba(255,255,255,0.1);">
            <div class="d-flex justify-content-between mb-2 small text-secondary fw-bold text-uppercase">
                <span>Evento:</span> <span class="text-white">{{ $jugada->nombre_jugada }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2 small text-secondary fw-bold text-uppercase">
                <span>Cartones Adquiridos:</span> <span class="text-white">{{ $comprados }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2 small text-secondary fw-bold text-uppercase">
                <span>Teléfono de Respaldo:</span> <span class="text-white">{{ $participante->telefono }}</span>
            </div>
        </div>

        <p class="text-muted small mt-4 mb-1">Este es tu token único e intransferible para jugar online.</p>
        <div class="token-box">
            {{ strtoupper(substr($participante->token, 0, 13)) }}...
        </div>

        <a href="{{ route('piloto.ver', $participante->token) }}" class="btn-play">
            <i class="bi bi-play-circle-fill me-2 text-danger"></i> ENTRAR A LA SALA
        </a>
        
        <p class="text-muted mt-3 mb-0" style="font-size: 0.75rem;"><i class="bi bi-bell-fill text-warning me-1"></i> Puedes cerrar esta ventana, nosotros te enviaremos este link mágico por WhatsApp para que no lo pierdas antes de que empiece el show.</p>
    </div>
</div>

</body>
</html>
