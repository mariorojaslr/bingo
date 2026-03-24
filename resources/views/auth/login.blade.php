<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinity Bingo | Acceso Restringido</title>
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
            background-image: radial-gradient(circle at 50% 10%, rgba(0, 255, 136, 0.05) 0%, transparent 40%);
        }
        
        /* Floating Ring Background */
        .ring {
            position: absolute;
            width: 80vh;
            height: 80vh;
            border: 1px solid rgba(212, 175, 55, 0.1);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: spin 30s linear infinite;
            z-index: -1;
        }
        .ring::before {
            content: ''; position: absolute; top: -5px; left: 50%;
            width: 10px; height: 10px; background: rgba(0,255,136,0.5); border-radius: 50%;
            box-shadow: 0 0 20px #00FF88;
        }
        @keyframes spin { 100% { transform: translate(-50%, -50%) rotate(360deg); } }

        .auth-card {
            background: rgba(10, 10, 10, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }

        .auth-input {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            width: 100%;
            transition: all 0.3s;
        }
        .auth-input:focus {
            background: rgba(0,255,136,0.02);
            border-color: #00FF88;
            outline: none;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.2);
            color: #fff;
        }

        .btn-neon {
            background: transparent;
            color: #00FF88;
            border: 1px solid #00FF88;
            padding: 15px;
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            width: 100%;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .btn-neon::before {
            content: ''; position: absolute; top: 0; left: 0; width: 0%; height: 100%;
            background: #00FF88; z-index: -1; transition: all 0.3s ease;
        }
        .btn-neon:hover { color: #000; box-shadow: 0 0 20px rgba(0, 255, 136, 0.4); }
        .btn-neon:hover::before { width: 100%; }

        .logo-svg {
            width: 60px; height: 60px;
            filter: drop-shadow(0 0 10px rgba(0, 255, 136, 0.4));
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="ring"></div>

    <div class="auth-card">
        <div class="text-center mb-4">
            <svg class="logo-svg" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="45" stroke="#00FF88" stroke-width="2" stroke-dasharray="10 5" fill="rgba(0,255,136,0.05)"/>
                <circle cx="50" cy="50" r="30" stroke="#D4AF37" stroke-width="4" stroke-linecap="round" fill="none"/>
                <circle cx="50" cy="50" r="15" fill="#00FF88" filter="blur(2px)"/>
                <circle cx="50" cy="50" r="10" fill="#FFFFFF"/>
            </svg>
            <h3 class="fw-bold mb-0">SISTEMA <span style="color: #D4AF37;">SAAS</span></h3>
            <p class="text-white-50 small" style="letter-spacing: 2px;">ACCESO AUTORIZADO</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="background: rgba(220,53,69,0.1); border-color: rgba(220,53,69,0.3); color: #ff6b6b; font-size: 0.85rem;">
                <i class="bi bi-shield-x me-2"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-4">
                <label class="text-white-50 small mb-2 text-uppercase tracking-wider">Identidad (Email)</label>
                <input type="email" name="email" class="auth-input" placeholder="ejecutivo@empresa.com" required autofocus>
            </div>
            
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="text-white-50 small text-uppercase tracking-wider">Clave de Acceso</label>
                    <a href="#" class="text-muted small text-decoration-none">¿Olvidada?</a>
                </div>
                <input type="password" name="password" class="auth-input" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn-neon mt-3">Iniciar Protocolo</button>
        </form>
        
        <div class="text-center mt-4">
            <p class="text-muted small mb-0"><i class="bi bi-lock me-1"></i> Plataforma encriptada End-to-End</p>
        </div>
    </div>
</body>
</html>
