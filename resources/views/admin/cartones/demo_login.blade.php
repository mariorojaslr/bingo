<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Infinity Bingo | Acceso Restringido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-dark: #050505;
            --bg-panel: #0d1117;
            --accent: #00A8FF;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: #ffffff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            background-image: radial-gradient(circle at center, rgba(0, 168, 255, 0.1) 0%, transparent 70%);
        }
        .login-box {
            background: var(--bg-panel);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5), 0 0 20px rgba(0, 168, 255, 0.1);
            text-align: center;
        }
        h1 {
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            letter-spacing: 2px;
            color: var(--accent);
            margin-bottom: 0.5rem;
        }
        h2 {
            font-family: 'Outfit', sans-serif;
            font-weight: 300;
            font-size: 1.1rem;
            color: #a0a0a0;
            margin-bottom: 2.5rem;
            letter-spacing: 1px;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            text-align: center;
            font-size: 1.2rem;
            letter-spacing: 3px;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent);
            box-shadow: 0 0 0 0.25rem rgba(0, 168, 255, 0.25);
            color: #fff;
        }
        .btn-enter {
            background: linear-gradient(135deg, #00A8FF 0%, #0077ff 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #fff;
            width: 100%;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
        }
        .btn-enter:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 168, 255, 0.4);
        }
    </style>
</head>
<body>

    <div class="login-box">
        <div class="mb-4">
            <i class="bi bi-shield-lock" style="font-size: 3rem; color: var(--accent);"></i>
        </div>
        
        <h1>INFINITY BINGO <span class="text-white">PRO</span></h1>
        <h2>Visor Profesional de Cartones</h2>

        @if(request('pwd'))
            <div class="alert alert-danger p-2" style="background: rgba(220, 53, 69, 0.1); border-color: rgba(220, 53, 69, 0.3); color: #ff6b6b; font-size: 0.9rem;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Clave de acceso incorrecta.
            </div>
        @endif

        <form method="GET" action="{{ route('demo.visor') }}">
            <div class="form-group">
                <input type="password" name="pwd" class="form-control" placeholder="••••••••" required autofocus>
            </div>
            <button type="submit" class="btn-enter">
                Acceder al Visor <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </form>
    </div>

</body>
</html>
