<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinity SaaS - Owner Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { background-color: #0b0c0f; color: #fff; font-family: 'Outfit', sans-serif; }
        .glass-panel { background: rgba(25, 28, 36, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); }
    </style>
</head>
<body>
<div class="container mt-4" style="color: #fff; font-family: 'Outfit', sans-serif;">
    
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
        <h2 class="fw-bold" style="letter-spacing: -1px; text-transform: uppercase;">
            <i class="bi bi-globe text-info me-2"></i> Infinity SaaS <span class="text-white-50 fs-5 fw-light">/ Owner Dashboard</span>
        </h2>
        <div>
            <button class="btn btn-outline-info rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalEmpresa"><i class="bi bi-plus-circle me-1"></i> Nueva Empresa</button>
            <button class="btn btn-outline-warning rounded-pill px-4 ms-2" data-bs-toggle="modal" data-bs-target="#modalTarifa"><i class="bi bi-tags me-1"></i> Gestionar Tarifas</button>
        </div>
    </div>

    <!-- MÉTRICAS GLOBALES -->
    <div class="row mb-5 g-4">
        <div class="col-md-3">
            <div class="glass-panel text-center p-4 rounded-4" style="background: rgba(0, 168, 255, 0.1); border: 1px solid rgba(0,168,255,0.3);">
                <i class="bi bi-buildings text-info fs-1 mb-2"></i>
                <h5 class="text-white-50 mb-1">Empresas Activas</h5>
                <h2 class="fw-bold text-white">{{ $metricas['empresas_activas'] }} <span class="text-white-50 fs-5 fw-light">/ {{ $metricas['total_empresas'] }}</span></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel text-center p-4 rounded-4" style="background: rgba(0, 255, 136, 0.1); border: 1px solid rgba(0,255,136,0.3);">
                <i class="bi bi-cash-coin text-success fs-1 mb-2"></i>
                <h5 class="text-white-50 mb-1">Ingresos MRR</h5>
                <h2 class="fw-bold text-white">{{ $metricas['ingresos_estimados'] }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel text-center p-4 rounded-4" style="background: rgba(255, 0, 85, 0.1); border: 1px solid rgba(255,0,85,0.3);">
                <i class="bi bi-grid-3x3 text-danger fs-1 mb-2"></i>
                <h5 class="text-white-50 mb-1">Cartones Generados</h5>
                <h2 class="fw-bold text-white">{{ number_format($metricas['cartones_generados'], 0, ',', '.') }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-panel text-center p-4 rounded-4" style="background: rgba(255, 215, 0, 0.1); border: 1px solid rgba(255,215,0,0.3);">
                <i class="bi bi-broadcast text-warning fs-1 mb-2"></i>
                <h5 class="text-white-50 mb-1">Uso Streaming</h5>
                <h2 class="fw-bold text-white">450 <span class="text-white-50 fs-5 fw-light">GB</span></h2>
            </div>
        </div>
    </div>

    <!-- LISTA DE EMPRESAS Y TARIFAS -->
    <div class="row">
        <!-- EMPRESAS -->
        <div class="col-md-8">
            <div class="glass-panel rounded-4 p-4" style="background: rgba(25, 28, 36, 0.8); border: 1px solid #333;">
                <h4 class="mb-4 text-white-50"><i class="bi bi-list-ul me-2"></i> Directorio de Empresas</h4>
                
                @if($empresas->isEmpty())
                    <div class="alert alert-dark text-center py-5">
                        <i class="bi bi-inbox fs-1 text-white-50"></i>
                        <p class="mt-3 text-white-50">Aún no hay empresas (clientes) registradas en el ecosistema.</p>
                        <button class="btn btn-info mt-2">Crear mi primer Cliente</button>
                    </div>
                @else
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr class="text-white-50">
                                <th>CLIENTE</th>
                                <th>PLAN</th>
                                <th>CANON</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($empresas as $emp)
                            <tr>
                                <td class="fw-bold text-white">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle me-3" style="width: 40px; height: 40px; background: {{ $emp->color_primario }}; display: flex; align-items:center; justify-content:center; color: #111; font-weight: 900;">
                                            {{ strtoupper(substr($emp->nombre, 0, 1)) }}
                                        </div>
                                        <div>
                                            {{ $emp->nombre }}<br>
                                            <small class="text-info fw-light">{{ $emp->subdominio ? $emp->subdominio . '.infinitybingo.com' : 'Sin subdominio' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary">{{ $emp->tarifa_nombre ?? 'Sin plan' }}</span></td>
                                <td>${{ number_format($emp->canon_mensual ?? 0, 2) }}</td>
                                <td>
                                    @if($emp->activo)
                                        <span class="badge bg-success rounded-pill px-3">ACTIVO</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill px-3">SUSPENDIDO</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('demo.owner.impersonate', $emp->id) }}" class="btn btn-sm btn-outline-light"><i class="bi bi-eye"></i> Entrar como Admin</a>
                                    <a href="{{ route('casino.lobby', $emp->subdominio) }}" target="_blank" class="btn btn-sm btn-outline-info ms-2"><i class="bi bi-phone"></i> Ver Casino App</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <!-- TARIFAS -->
        <div class="col-md-4">
            <div class="glass-panel rounded-4 p-4" style="background: rgba(25, 28, 36, 0.8); border: 1px solid #333;">
                <h4 class="mb-4 text-white-50"><i class="bi bi-tags me-2"></i> Planes de Precios</h4>
                
                @if($tarifas->isEmpty())
                    <p class="text-white-50 text-center my-4">No has definido ninguna tarifa comercial.</p>
                @else
                    <ul class="list-group list-group-flush bg-transparent">
                        @foreach($tarifas as $tarifa)
                            <li class="list-group-item bg-transparent text-white border-secondary px-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong class="fs-5 text-info">{{ $tarifa->nombre }}</strong>
                                    <span class="fs-5 fw-bold">${{ number_format($tarifa->canon_mensual, 0) }}</span>
                                </div>
                                <div class="text-white-50 mt-1" style="font-size: 0.85rem;">
                                    <i class="bi bi-check2 text-success"></i> Máx Cartones: {{ $tarifa->max_cartones ? number_format($tarifa->max_cartones, 0, ',', '.') : 'Ilimitado' }}<br>
                                    <i class="bi bi-check2 text-success"></i> Comisión: ${{ $tarifa->comision_por_carton }} / cartón<br>
                                    <i class="bi bi-check2 text-success"></i> Streaming: {{ $tarifa->streaming_incluido ? 'Incluido' : 'No incluido' }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- MODAL NUEVA EMPRESA -->
<div class="modal fade" id="modalEmpresa" tabindex="-1" data-bs-theme="dark">
  <div class="modal-dialog">
    <div class="modal-content glass-panel text-white border-secondary">
      <div class="modal-header border-secondary">
        <h5 class="modal-title"><i class="bi bi-building"></i> Registrar Nueva Empresa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="/demo/owner/empresas" method="POST">
      @csrf
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Nombre de la Empresa / Cliente</label>
            <input type="text" name="nombre" class="form-control bg-dark text-white border-secondary" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Plan Comercial Asociado</label>
            <select name="tarifa_id" class="form-select bg-dark text-white border-secondary">
                <option value="">-- Sin Plan (Prueba) --</option>
                @foreach($tarifas as $tarifa)
                    <option value="{{ $tarifa->id }}">{{ $tarifa->nombre }} - ${{ number_format($tarifa->canon_mensual, 0) }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Color Principal (Marca)</label>
            <input type="color" name="color_primario" class="form-control form-control-color w-100 bg-dark border-secondary" value="#00ff88">
        </div>
      </div>
      <div class="modal-footer border-secondary">
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-info fw-bold">Guardar Empresa</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL NUEVA TARIFA -->
<div class="modal fade" id="modalTarifa" tabindex="-1" data-bs-theme="dark">
  <div class="modal-dialog">
    <div class="modal-content glass-panel text-white border-secondary">
      <div class="modal-header border-secondary">
        <h5 class="modal-title"><i class="bi bi-tags"></i> Definir Plan Comercial</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="/demo/owner/tarifas" method="POST">
      @csrf
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Nombre del Plan (Ej: Básico, Elite)</label>
            <input type="text" name="nombre" class="form-control bg-dark text-white border-secondary" required>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <label class="form-label">Canon Mensual ($)</label>
                <input type="number" step="0.01" name="canon_mensual" class="form-control bg-dark text-white border-secondary" value="0">
            </div>
            <div class="col-6 mb-3">
                <label class="form-label">Comisión x Cartón ($)</label>
                <input type="number" step="0.01" name="comision_por_carton" class="form-control bg-dark text-white border-secondary" value="0">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Límite de Cartones (Dejar vacío para ilimitado)</label>
            <input type="number" name="max_cartones" class="form-control bg-dark text-white border-secondary">
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="streaming_incluido" id="streamCheck" value="1">
            <label class="form-check-label" for="streamCheck">Incluir acceso a Streaming Video (Bunny.net)</label>
        </div>
      </div>
      <div class="modal-footer border-secondary">
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-warning fw-bold text-dark">Crear Tarifa</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
