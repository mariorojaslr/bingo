<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación por Código de Seguridad</title>
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

        .auth-card {
            background: rgba(10, 10, 10, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }

        .auth-input {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            color: #00FF88;
            padding: 15px 20px;
            border-radius: 10px;
            width: 100%;
            font-size: 2rem;
            letter-spacing: 15px;
            text-align: center;
            font-weight: 800;
            transition: all 0.3s;
        }
        .auth-input:focus {
            background: rgba(0,255,136,0.02);
            border-color: #00FF88;
            outline: none;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.2);
            color: #00FF88;
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
        
        /* Auto-format placeholder */
        .auth-input::placeholder {
            color: rgba(255,255,255,0.1);
            letter-spacing: 15px;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="mb-4">
            <i class="bi bi-telegram" style="font-size: 4rem; color: #0088cc; filter: drop-shadow(0 0 15px rgba(0, 136, 204, 0.4));"></i>
        </div>
        
        <h3 class="fw-bold mb-2">VALIDACIÓN REQUERIDA</h3>
        <p class="text-white-50 small mb-4" style="line-height: 1.5;">Hemos enviado un código seguro de 5 dígitos a tu aplicación oficial de <b>Telegram</b> conectada.</p>

        @if($errors->any())
            <div class="alert alert-danger mb-4" style="background: rgba(220,53,69,0.1); border-color: rgba(220,53,69,0.3); color: #ff6b6b; font-size: 0.85rem;">
                <i class="bi bi-shield-x me-2"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('auth.otp.verify') }}">
            @csrf
            <div class="mb-4">
                <input type="text" name="code" class="auth-input" placeholder="00000" maxlength="5" required autofocus oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            </div>
            
            <button type="submit" class="btn-neon mt-2">Acceder a la Bóveda</button>
        </form>
        
        <div class="text-center mt-4">
            <p class="text-muted small mb-0"><i class="bi bi-shield-lock me-1"></i> Autenticación Militar Nivel 2</p>
            <p class="text-muted small mt-2">¿No está en la bandeja? Revisa el correo Spam.</p>
        </div>
    </div>
    
    <!-- (MODO DESARROLLADOR DE EMERGENCIA) 
         Si tu hosting CPanel no tiene activado o configurado el SMTP, aquí tienes el PIN secreto que el sistema guardó en la DB:
         PIN DE LA SESIÓN: {{ \App\Models\LoginApproval::where('user_id', session('pending_otp_user_id'))->where('status', 'pending')->latest()->first()->token ?? 'Ninguno' }}
    -->
</body>
</html>
