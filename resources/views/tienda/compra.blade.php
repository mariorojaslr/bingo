<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Boletería | {{ $jugada->nombre_jugada ?? 'Evento' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { 
            background: #020202; color: #fff; font-family: 'Inter', sans-serif;
            background-image: radial-gradient(circle at 50% 0%, rgba(0, 255, 136, 0.15) 0%, transparent 60%);
        }
        .container-box {
            max-width: 600px; margin: 3rem auto;
        }
        .glass-card {
            background: rgba(20, 20, 25, 0.8); border: 1px solid rgba(255,255,255,0.05);
            border-radius: 20px; padding: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            backdrop-filter: blur(10px);
        }
        .btn-neon {
            background: #00FF88; color: #000; font-weight: 800; font-family: 'Outfit';
            font-size: 1.2rem; border-radius: 50px; padding: 15px; border: none;
            text-transform: uppercase; letter-spacing: 2px;
            box-shadow: 0 5px 20px rgba(0,255,136,0.3); transition: 0.3s;
        }
        .btn-neon:hover { background: #fff; box-shadow: 0 5px 30px rgba(0,255,136,0.6); }
        .form-control { background: rgba(0,0,0,0.5); border: 1px solid #333; color: #fff; font-family: 'Outfit'; }
        .form-control:focus { background: #111; color: #fff; box-shadow: 0 0 10px rgba(0,255,136,0.2); border-color: #00FF88; }
        .badge-premium { background: rgba(212, 175, 55, 0.2); color: #D4AF37; font-family: 'Outfit'; font-weight: 800; letter-spacing: 1px; }
    </style>
</head>
<body>

<!-- Navegación simple -->
<div class="px-4 py-3 d-flex justify-content-between align-items-center" style="background: rgba(0,0,0,0.8); border-bottom: 1px solid rgba(255,255,255,0.05);">
    <a href="/salas" class="text-white text-decoration-none"><i class="bi bi-arrow-left me-2"></i> Volver a Cartelera</a>
    <span style="font-weight: 900; font-family: 'Outfit'; font-size: 1.2rem;">INFINITY<span style="color:#00FF88">BINGO</span></span>
</div>

<div class="container container-box">
    
    <!-- AVISO DE MODO DEMO -->
    <div class="alert alert-warning text-center border-warning fw-bold mb-4 shadow-sm" style="background: rgba(255, 193, 7, 0.1); color: #ffc107;">
        <i class="bi bi-exclamation-triangle-fill fs-4 d-block mb-2"></i>
        ATENCIÓN: MODO DE PRUEBA Y DEMOSTRACIÓN<br>
        <span class="small fw-normal">Todos los movimientos, saldos y compras en este sector operan en el plano de lo ficticio. Es solo una simulación. No tendrás que pagar nada real, ni podrás cobrar premios reales. Disfruta la experiencia.</span>
    </div>

    <div class="text-center mb-4">
        <h6 class="badge badge-premium px-3 py-2 mb-3">SORTEO EN VIVO (PLATAFORMA B2C)</h6>
        <h2 class="fw-bold" style="font-family: 'Outfit';">{{ mb_strtoupper($jugada->nombre_jugada) }}</h2>
        <p class="text-muted">Auspiciado por: <span class="text-white">{{ mb_strtoupper($jugada->organizador->nombre_fantasia ?? 'Club Test') }}</span></p>
    </div>

    <div class="glass-card">
        <h5 class="fw-bold mb-4 text-center pb-3" style="border-bottom: 1px dashed rgba(255,255,255,0.1); font-family: 'Outfit';"><i class="bi bi-ticket-perforated me-2"></i> BOLETERÍA DIGITAL</h5>

        @if(session('error'))
            <div class="alert alert-danger border-0" style="background: rgba(255,0,85,0.1); color: #FF0055;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success border-0" style="background: rgba(0,255,136,0.1); color: #00FF88;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('tienda.procesar', $jugada->id) }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label text-white-50 small text-uppercase fw-bold">Nombre del Jugador</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-white-50"><i class="bi bi-person"></i></span>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej: Juan Pérez" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label text-white-50 small text-uppercase fw-bold">Teléfono Celular (Para identificar tus compras)</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-white-50"><i class="bi bi-whatsapp"></i></span>
                    <input type="tel" name="telefono" class="form-control" placeholder="Ej: 5491123456789" required>
                </div>
            </div>

            <div class="mb-5 bg-dark p-3 rounded" style="border: 1px solid rgba(255,255,255,0.05);">
                <label class="form-label text-white-50 small text-uppercase fw-bold">Cantidad de Cartones</label>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <select name="cantidad" class="form-select text-center fw-bold text-white fs-4" style="background: #000; border-color: #444; width: 120px;" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4" selected>4 (MÁX)</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <span class="text-muted small">Costo por cartón:</span><br>
                        <span class="fw-bold fs-5" style="color: #00FF88;"><i class="bi bi-gem"></i> 50 Infinity</span>
                    </div>
                </div>
                <div class="text-warning small mt-2"><i class="bi bi-info-circle me-1"></i> Puedes comprar un máximo de 4 por partida.</div>
            </div>

            <button type="submit" class="btn btn-neon w-100 mb-4">COMPRAR CARTONES <i class="bi bi-arrow-right-circle ms-2"></i></button>
        </form>

        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
            <h6 class="text-white-50 text-uppercase fw-bold mb-3"><i class="bi bi-wallet2"></i> Cajero de Fichas Infinity</h6>
            <form action="{{ route('tienda.fichas') }}" method="POST" class="d-flex gap-2">
                @csrf
                <input type="tel" name="telefono" class="form-control form-control-sm" placeholder="Tu Teléfono" required>
                <input type="number" name="monto" class="form-control form-control-sm" value="5000" required>
                <button type="submit" class="btn btn-outline-success btn-sm text-nowrap">Comprar Fichas</button>
            </form>
            <div class="small text-muted mt-2">Usa el mismo teléfono de arriba. Si no te has registrado antes, primero dale a Comprar Cartones para que el sistema cree tu billetera.</div>
        </div>
    </div>
</div>

</body>
</html>
