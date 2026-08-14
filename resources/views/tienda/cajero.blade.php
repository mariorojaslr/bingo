<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Cajero Infinity</title>
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
    </style>
</head>
<body>

<div class="container container-box">
    <div class="glass-card">
        <div class="text-center mb-4">
            <h1 class="mb-2 text-white" style="font-family: 'Outfit', sans-serif; font-weight: 800;">CAJERO MULTIPASARELA</h1>
            <p class="text-white-50 small">Elige tu método de pago preferido. Los recargos financieros se aplican según la plataforma elegida.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm">{{ session('error') }}</div>
        @endif

        <form action="{{ route('cajero.procesar') }}" method="POST">
            @csrf
            <input type="hidden" name="telefono" value="{{ $participante->telefono }}">
            
            <h5 class="text-white mb-3"><i class="bi bi-wallet2 text-warning"></i> Comprar Fichas Infinity</h5>

            <div class="mb-4">
                <label class="form-label text-white-50 small text-uppercase fw-bold">1. Selecciona el Paquete</label>
                <select name="paquete_fichas" class="form-select form-select-lg bg-dark text-white border-secondary" required>
                    <option value="500">500 Fichas ($500 Base)</option>
                    <option value="1000">1,000 Fichas ($1,000 Base)</option>
                    <option value="5000">5,000 Fichas ($5,000 Base)</option>
                </select>
            </div>

            <div class="mb-5">
                <label class="form-label text-white-50 small text-uppercase fw-bold">2. Selecciona el Método de Pago</label>
                
                <div class="list-group">
                    <label class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center">
                        <div>
                            <input class="form-check-input me-2" type="radio" name="metodo_pago" value="mp" required>
                            <i class="bi bi-credit-card"></i> MercadoPago
                        </div>
                        <span class="badge bg-danger rounded-pill">+10% Recargo</span>
                    </label>

                    <label class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center">
                        <div>
                            <input class="form-check-input me-2" type="radio" name="metodo_pago" value="airtm">
                            Airtm
                        </div>
                        <span class="badge bg-warning rounded-pill text-dark">+5% Recargo</span>
                    </label>

                    <label class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center">
                        <div>
                            <input class="form-check-input me-2" type="radio" name="metodo_pago" value="prex_ar">
                            Prex Argentina
                        </div>
                        <span class="badge bg-success rounded-pill">0% Recargo</span>
                    </label>

                    <label class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center">
                        <div>
                            <input class="form-check-input me-2" type="radio" name="metodo_pago" value="prex_uy">
                            Prex Uruguay
                        </div>
                        <span class="badge bg-success rounded-pill">0% Recargo</span>
                    </label>

                    <label class="list-group-item list-group-item-action bg-dark text-white border-secondary d-flex justify-content-between align-items-center">
                        <div>
                            <input class="form-check-input me-2" type="radio" name="metodo_pago" value="arq">
                            ARQ (Pago Directo)
                        </div>
                        <span class="badge bg-success rounded-pill">0% Recargo</span>
                    </label>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark">
                    CONTINUAR AL PAGO
                </button>
                <a href="{{ route('tienda.show', 1) }}" class="btn btn-outline-secondary mt-2">Volver a la Tienda</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
