<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación Biométrica Requerida</title>
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
            background-image: radial-gradient(circle at 50% 50%, rgba(212, 175, 55, 0.05) 0%, transparent 60%);
        }

        .auth-card {
            background: transparent;
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        /* The Fingerprint SVG pulse */
        .fingerprint-icon {
            font-size: 8rem;
            color: rgba(255,255,255,0.05);
            transition: all 0.5s ease;
            cursor: pointer;
            filter: drop-shadow(0 0 0 transparent);
        }
        
        .fingerprint-icon.scanning {
            color: #00FF88;
            filter: drop-shadow(0 0 30px rgba(0,255,136,0.6));
            animation: pulse-scan 1s infinite alternate;
        }
        .fingerprint-icon.success {
            color: #D4AF37;
            filter: drop-shadow(0 0 40px rgba(212,175,55,0.8));
        }

        @keyframes pulse-scan {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.05); opacity: 1; }
        }

        .scan-line {
            width: 100px;
            height: 4px;
            background: #00FF88;
            border-radius: 10px;
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 0 15px #00FF88;
            opacity: 0;
        }
        
        .scanning .scan-line {
            opacity: 1;
            animation: scan-move 2s infinite ease-in-out;
        }

        @keyframes scan-move {
            0% { top: -10px; }
            50% { top: 130px; }
            100% { top: -10px; }
        }

        /* Status Texts */
        .status-title { font-weight: 800; font-size: 1.5rem; margin-top: 30px; letter-spacing: 2px; }
        .status-subtitle { color: #A1A1AA; font-size: 0.9rem; margin-top: 10px; }
        
        /* Hardware prompt mock box */
        .hardware-prompt {
            background: rgba(20,20,20,0.9);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 20px;
            margin-top: 40px;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .hardware-prompt.visible {
            transform: translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="badge rounded-pill border border-secondary text-secondary mb-4 px-3 py-2" style="background: rgba(255,255,255,0.05);">
            <i class="bi bi-shield-lock-fill me-2" style="color: #D4AF37;"></i> AUTENTICACIÓN 2FA DE DUEÑO DE SISTEMA
        </div>
        
        <div class="position-relative d-inline-block mt-4 mb-3" id="fp-container" onclick="iniciarEscaneo()">
            <div class="scan-line"></div>
            <i class="bi bi-fingerprint fingerprint-icon" id="fp-icon"></i>
        </div>

        <h3 class="status-title text-uppercase" id="status-title">Esperando Lector</h3>
        <p class="status-subtitle" id="status-desc">Acerca el dispositivo al lector de huellas o usa FaceID para validar tu jerarquía de Owner.</p>

        <div class="hardware-prompt" id="hardware-prompt">
            <div class="d-flex align-items-center text-start gap-3">
                <i class="bi bi-phone-vibrate fs-1 text-white-50"></i>
                <div>
                    <strong class="d-block text-white mb-1">Petición Passkey Enviada</strong>
                    <span class="text-white-50 small">Revisa tu teléfono móvil y coloca tu huella.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Simulación pura JS de la Api WebAuthn Nativa para el flujo local (Visual Roll-Royce) -->
    <script>
        const fpIcon = document.getElementById('fp-icon');
        const fpContainer = document.getElementById('fp-container');
        const title = document.getElementById('status-title');
        const desc = document.getElementById('status-desc');
        const prompt = document.getElementById('hardware-prompt');

        // Se simula la llamada a navigator.credentials.create() / WebAuthn API
        function iniciarEscaneo() {
            if(fpContainer.classList.contains('scanning')) return;
            
            fpContainer.classList.add('scanning');
            title.innerText = "ESCANEO DETECTADO...";
            title.style.color = "#00FF88";
            desc.innerText = "Intercambiando llaves criptográficas con el servidor.";
            prompt.classList.add('visible');

            // Simulamos 3 segundos del usuario apoyando el dedo en su celular...
            setTimeout(() => {
                // Éxito Criptográfico
                fpContainer.classList.remove('scanning');
                fpIcon.classList.add('success');
                title.innerText = "IDENTIDAD CONFIRMADA";
                title.style.color = "#D4AF37";
                desc.innerText = "Bienvenido Supremo. Accediendo a la Bóveda del Owner...";
                prompt.style.opacity = '0';
                
                // Disparamos la petición AJAX real a nuestro servidor para validar todo backend
                fetch('{{ route("auth.biometric.verify") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ verified: true })
                })
                .then(r => r.json())
                .then(data => {
                    if(data.success) {
                        setTimeout(() => window.location.href = data.redirect, 1000);
                    }
                });

            }, 3500);
        }
    </script>
</body>
</html>
