<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - {{ $empresa->nombre }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { background-color: #0b0c0f; color: #fff; font-family: 'Outfit', sans-serif; }
        .glass-panel { background: rgba(25, 28, 36, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); }
        .brand-color { color: {{ $empresa->color_primario }}; }
        .btn-brand { background-color: {{ $empresa->color_primario }}; color: #000; font-weight: bold; border: none; }
        .btn-brand:hover { background-color: #fff; color: #000; }
        .module-card { transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer; text-decoration: none; display: block;}
        .module-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.5); }
    </style>
</head>
<body>
<div class="container mt-4">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom border-secondary pb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center fs-3 fw-bold" style="width: 60px; height: 60px; background: {{ $empresa->color_primario }}; color: #111;">
                {{ strtoupper(substr($empresa->nombre, 0, 1)) }}
            </div>
            <div>
                <h2 class="fw-bold mb-0" style="text-transform: uppercase;">{{ $empresa->nombre }}</h2>
                <span class="text-white-50">Panel de Administración de Cliente (Agencia)</span>
            </div>
        </div>
        <div>
            <a href="/demo/owner?pwd=infinity2026" class="btn btn-outline-light rounded-pill"><i class="bi bi-arrow-left"></i> Volver al Owner</a>
        </div>
    </div>

    <h4 class="mb-4 text-white-50"><i class="bi bi-grid"></i> Ecosistema de Módulos (Salas de Juego)</h4>

    <div class="row g-4">
        <!-- Generar Cartones -->
        <div class="col-md-4">
            <a href="/admin/cartones/generar" target="_blank" class="glass-panel p-4 rounded-4 module-card h-100 text-white">
                <i class="bi bi-printer brand-color fs-1 mb-3"></i>
                <h4 class="fw-bold">Central de Cartones</h4>
                <p class="text-white-50 mb-0">Emitir, validar e imprimir nuevos cartones masivamente para la venta.</p>
            </a>
        </div>

        <!-- Consola del Locutor (Sorteador) -->
        <div class="col-md-4">
            <a href="/demo/sorteador?pwd=infinity2026" target="_blank" class="glass-panel p-4 rounded-4 module-card h-100 text-white" style="border-color: rgba(0,255,136,0.3);">
                <i class="bi bi-mic text-success fs-1 mb-3"></i>
                <h4 class="fw-bold">Consola del Locutor</h4>
                <p class="text-white-50 mb-0">El sorteador maestro. Generador manual y automático de bolillas.</p>
            </a>
        </div>

        <!-- Visor Jugadores -->
        <div class="col-md-4">
            <a href="/demo/visor?pwd=infinity2026" target="_blank" class="glass-panel p-4 rounded-4 module-card h-100 text-white" style="border-color: rgba(0,168,255,0.3);">
                <i class="bi bi-phone text-info fs-1 mb-3"></i>
                <h4 class="fw-bold">Visor Jugadores (App)</h4>
                <p class="text-white-50 mb-0">La interfaz web para que los jugadores sigan sus cartones desde casa.</p>
            </a>
        </div>

        <!-- Monitor TV -->
        <div class="col-md-6">
            <a href="/demo/monitor-tv?pwd=infinity2026" target="_blank" class="glass-panel p-4 rounded-4 module-card h-100 text-white" style="background: rgba(255,0,85,0.05); border-color: rgba(255,0,85,0.3);">
                <i class="bi bi-tv text-danger fs-1 mb-3"></i>
                <h4 class="fw-bold">Monitor TV (Transmisión)</h4>
                <p class="text-white-50 mb-0">Gráfica transparente lista para OBS o vMix en transmisiones de streaming satelital o Facebook Live.</p>
            </a>
        </div>

        <!-- Monitor Común -->
        <div class="col-md-6">
            <a href="/demo/monitor-comun?pwd=infinity2026" target="_blank" class="glass-panel p-4 rounded-4 module-card h-100 text-white" style="background: rgba(255,215,0,0.05); border-color: rgba(255,215,0,0.3);">
                <i class="bi bi-projector text-warning fs-1 mb-3"></i>
                <h4 class="fw-bold">Monitor de Salón (Físico)</h4>
                <p class="text-white-50 mb-0">La clásica grilla gigante de 90 números pensada para ser proyectada con un cañón en clubes y estadios.</p>
            </a>
        </div>
    </div>
</div>
</body>
</html>
