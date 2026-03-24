<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinity Bingo | Acceso Concedido</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #020202;
            color: #fff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #00FF88;
            background-image: radial-gradient(circle at 50% 50%, #00FF88 0%, #020202 100%);
            animation: bg-pulse 2s ease-out forwards;
        }

        @keyframes bg-pulse {
            0% { background-color: #00FF88; }
            100% { background-color: #020202; }
        }

        .success-card {
            background: transparent;
            text-align: center;
            padding: 2rem;
            animation: pop-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes pop-in {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .icon-huge {
            font-size: 6rem;
            color: #00FF88;
            filter: drop-shadow(0 0 30px rgba(0,255,136,0.6));
            margin-bottom: 20px;
        }
        
    </style>
</head>
<body>
    <div class="success-card">
        <i class="bi bi-check-circle-fill icon-huge"></i>
        <h2 class="fw-bold" style="color: #00FF88; letter-spacing: 1px;">ACCESO AUTORIZADO</h2>
        <p class="text-white-50 mt-3">Has desbloqueado exitosamente la computadora a larga distancia.</p>
        <p class="small text-muted mt-5">Ya puedes mirar la pantalla de tu PC.<br>Esta ventana del celular es segura de cerrar.</p>
    </div>
</body>
</html>
