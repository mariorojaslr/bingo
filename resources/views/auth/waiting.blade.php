<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinity Bingo | Autorización Remota</title>
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
            background-image: radial-gradient(circle at 50% 50%, rgba(0, 255, 136, 0.05) 0%, transparent 60%);
        }

        .waiting-card {
            background: rgba(10, 10, 10, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            position: relative;
        }

        .qr-container {
            background: #fff;
            padding: 15px;
            border-radius: 15px;
            display: inline-block;
            margin: 20px 0;
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.2);
            position: relative;
            overflow: hidden;
        }

        .radar {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            border: 1px solid rgba(0, 255, 136, 0.5);
            animation: pulse-ring 2s infinite;
            pointer-events: none;
        }

        @keyframes pulse-ring {
            0% { width: 100%; height: 100%; opacity: 1; }
            100% { width: 150%; height: 150%; opacity: 0; }
        }

        .status-title { font-weight: 800; font-size: 1.5rem; letter-spacing: 2px; color: #D4AF37; }
        .status-desc { color: #A1A1AA; font-size: 0.95rem; line-height: 1.5; }
        
        .timer { font-size: 0.8rem; color: #ff6b6b; margin-top: 15px; letter-spacing: 1px; }
    </style>
</head>
<body>

    <div class="waiting-card">
        <div class="badge rounded-pill border border-secondary text-secondary mb-3 px-3 py-2" style="background: rgba(255,255,255,0.05);">
            <i class="bi bi-shield-lock-fill me-2" style="color: #00FF88;"></i> 2FA OUT-OF-BAND DETECTADO
        </div>
        
        <h3 class="status-title">AUTORIZACIÓN REMOTA</h3>
        <p class="status-desc">Para abrir la bóveda en esta computadora, saca tu celular y escanea este código. Tu celular actuará como llave biométrica maestra.</p>

        <div class="qr-container position-relative">
            <!-- El QR Generado Nativamente que apunta a la URL única del celular -->
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(220)->margin(0)->color(20, 20, 20)->generate($magicLink) !!}
            <div class="radar"></div>
        </div>

        <p class="mb-0 text-white-50 small"><i class="bi bi-broadcast me-1"></i> Esperando señal criptográfica de tu celular...</p>
        <div class="timer" id="timer">SESIÓN EXPIRA EN 05:00</div>
    </div>

    <!-- Petición AJAX (Polling) para saber si el celular ya autorizó -->
    <script>
        let timeLeft = 300;
        const timerEl = document.getElementById('timer');

        // Cuenta atrás visual
        const interval = setInterval(() => {
            timeLeft--;
            let mins = Math.floor(timeLeft / 60).toString().padStart(2, '0');
            let secs = (timeLeft % 60).toString().padStart(2, '0');
            timerEl.innerText = `SESIÓN EXPIRA EN ${mins}:${secs}`;
            if (timeLeft <= 0) clearInterval(interval);
        }, 1000);

        // Polling en background cada 2 segundos a la DB
        function checkStatus() {
            fetch('{{ route("auth.check") }}', {
                headers: { 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'approved') {
                    document.querySelector('.status-title').innerText = "ACCESO DESBLOQUEADO";
                    document.querySelector('.status-title').style.color = "#00FF88";
                    document.querySelector('.status-desc').innerText = "Señal del celular recibida. Abriendo Bóveda...";
                    document.querySelector('.qr-container').style.opacity = '0.2';
                    setTimeout(() => window.location.href = data.redirect, 1000);
                } else if (data.status === 'expired') {
                    window.location.href = '/login';
                } else {
                    setTimeout(checkStatus, 2000); // Sigue esperando
                }
            })
            .catch(() => setTimeout(checkStatus, 2000));
        }

        // Iniciar polling
        setTimeout(checkStatus, 2000);
    </script>
</body>
</html>
