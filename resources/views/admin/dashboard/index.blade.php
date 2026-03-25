@extends('admin.layouts.master')

@section('content')
<div class="top-header">
    <div>
        <h2 class="fw-bold mb-1">Visión Panorámica</h2>
        <p class="text-muted mb-0">Monitor de Sistema Global - Servidor Central</p>
    </div>
    
    <div class="d-flex align-items-center gap-3">
        <span class="owner-badge"><i class="bi bi-shield-check me-2"></i>OWNER SUPREMO</span>
        <button class="btn btn-dark" style="background: var(--bg-panel); border: 1px solid var(--border-glass);">
            <i class="bi bi-bell"></i>
        </button>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Stat 1 -->
    <div class="col-md-6 col-lg-3">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="icon-box gold">
                    <i class="bi bi-buildings"></i>
                </div>
                <span class="badge" style="background: rgba(0, 255, 136, 0.1); color: #00FF88;">+2 esta sem.</span>
            </div>
            <p class="text-muted small fw-bold mb-1 text-uppercase">Empresas Activas (SaaS)</p>
            <div class="stat-value">14</div>
        </div>
    </div>
    
    <!-- Stat 2 -->
    <div class="col-md-6 col-lg-3">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="icon-box emerald">
                    <i class="bi bi-broadcast"></i>
                </div>
                <span class="badge" style="background: rgba(0, 255, 136, 0.1); color: #00FF88;">Estable</span>
            </div>
            <p class="text-muted small fw-bold mb-1 text-uppercase">Streaming Activos (Bunny)</p>
            <div class="stat-value">3</div>
        </div>
    </div>
    
    <!-- Stat 3 -->
    <div class="col-md-6 col-lg-3">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="icon-box blue">
                    <i class="bi bi-hdd-network"></i>
                </div>
            </div>
            <p class="text-muted small fw-bold mb-1 text-uppercase">Almacenamiento Global</p>
            <div class="d-flex align-items-baseline gap-2">
                <div class="stat-value" style="font-size: 2rem;">245</div>
                <span class="text-white-50">GB / 1 TB</span>
            </div>
        </div>
    </div>
    
    <!-- Stat 4 -->
    <div class="col-md-6 col-lg-3">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="icon-box purple">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <p class="text-muted small fw-bold mb-1 text-uppercase">Usuarios Totales (Jugadores)</p>
            <div class="stat-value" style="font-size: 2rem;">12.4K</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Main Panel: Lista de Tenants -->
    <div class="col-lg-8">
        <div class="glass-card h-100 p-0" style="overflow: hidden;">
            <div class="p-4 border-bottom" style="border-color: var(--border-glass) !important;">
                <h5 class="fw-bold mb-0">Empresas Cliente Recientes</h5>
            </div>
            
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0" style="background: transparent;">
                    <thead style="background: rgba(255,255,255,0.02);">
                        <tr>
                            <th class="py-3 px-4 fw-normal text-muted border-0 small">CLIENTE / DOMINIO</th>
                            <th class="py-3 px-4 fw-normal text-muted border-0 small text-center">PLAN</th>
                            <th class="py-3 px-4 fw-normal text-muted border-0 small text-center">ESTADO</th>
                            <th class="py-3 px-4 fw-normal border-0"></th>
                        </tr>
                    </thead>
                    <tbody style="border-top: none;">
                        @forelse($organizadores as $org)
                        <tr style="border-color: rgba(255,255,255,0.05);">
                            <td class="py-3 px-4 align-middle">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(0, 168, 255, 0.1); color: #00A8FF; display: flex; align-items: center; justify-content: center;"><i class="bi bi-building"></i></div>
                                    <div>
                                        <div class="fw-bold">{{ mb_strtoupper($org->nombre_fantasia) }}</div>
                                        <div class="small text-muted">{{ $org->email_contacto ?? 'sin-correo@sistema.com' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 align-middle text-center"><span class="badge bg-dark border" style="border-color: var(--accent-gold) !important; color: var(--accent-gold);">Pro Max ($50k)</span></td>
                            <td class="py-3 px-4 align-middle text-center">
                                @if($org->activo)
                                    <span class="text-success small fw-bold"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> ACTIVO</span>
                                @else
                                    <span class="text-danger small fw-bold"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> MOROSO</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 align-middle text-end">
                                <!-- BOTÓN DE PARAMETRIZACIÓN (MÓDULOS) -->
                                <button class="btn btn-sm btn-outline-secondary border-0 me-2" data-bs-toggle="modal" data-bs-target="#configModal{{ $org->id }}">
                                    <i class="bi bi-sliders text-warning"></i> Licencias
                                </button>

                                <!-- BOTON MÁGICO DE TELETRANSPORTE -->
                                <a href="{{ route('admin.impersonate', $org->id) }}" class="btn btn-sm btn-outline-light border-0">
                                    <i class="bi bi-box-arrow-in-right text-success"></i> Entrar
                                </a>
                            </td>
                        </tr>

                        <!-- MODAL DE PARAMETRIZACION SAAS PARA ESTE CLIENTE -->
                        <div class="modal fade" id="configModal{{ $org->id }}" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="background: #0d0d0d; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px;">
                                    <div class="modal-header border-bottom border-secondary pt-4 px-4">
                                        <h5 class="modal-title fw-bold" style="font-family: 'Outfit'; color: var(--neon-gold);">Parametrización de Módulos</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <p class="text-muted small mb-4">Configura qué herramientas de software activas o bloqueas para <strong>{{ mb_strtoupper($org->nombre_fantasia) }}</strong> según lo que hayan pagado.</p>
                                        
                                        <div class="form-check form-switch mb-3" style="font-size: 1.1rem;">
                                            <input class="form-check-input" type="checkbox" id="modTalonarios{{ $org->id }}" checked>
                                            <label class="form-check-label text-white" for="modTalonarios{{ $org->id }}">
                                                <i class="bi bi-printer text-white-50 me-2"></i> Fotocopia/Láser (Cartones Físicos)
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mb-3" style="font-size: 1.1rem;">
                                            <input class="form-check-input" type="checkbox" id="modSorteador{{ $org->id }}" checked>
                                            <label class="form-check-label text-white" for="modSorteador{{ $org->id }}">
                                                <i class="bi bi-joystick text-white-50 me-2"></i> Visor Operativo (Sorteador)
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mb-3" style="font-size: 1.1rem;">
                                            <input class="form-check-input" type="checkbox" id="modMonitor{{ $org->id }}">
                                            <label class="form-check-label text-white" for="modMonitor{{ $org->id }}">
                                                <i class="bi bi-display text-white-50 me-2"></i> Monitor TV 1-90 (Proyector Local)
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mb-3" style="font-size: 1.1rem;">
                                            <input class="form-check-input" type="checkbox" id="modVirtual{{ $org->id }}">
                                            <label class="form-check-label text-white" for="modVirtual{{ $org->id }}">
                                                <i class="bi bi-globe text-white-50 me-2"></i> Telebingo (Lobby Virtual Público)
                                            </label>
                                        </div>
                                        <div class="form-check form-switch mb-3" style="font-size: 1.1rem;">
                                            <input class="form-check-input" type="checkbox" id="modStream{{ $org->id }}">
                                            <label class="form-check-label text-white" for="modStream{{ $org->id }}">
                                                <i class="bi bi-camera-video text-white-50 me-2"></i> Streaming Bunny.net Limitless
                                            </label>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top border-secondary px-4 py-3">
                                        <button type="button" class="btn text-white-50" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-success rounded-pill px-4" data-bs-dismiss="modal">Guardar Licencia</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted small">
                                <i class="bi bi-inboxes mb-2 d-block" style="font-size: 2rem; color: rgba(255,255,255,0.1);"></i>
                                No hay franquicias registradas en la Base de Datos.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 text-center border-top" style="border-color: var(--border-glass) !important; background: rgba(255,255,255,0.01);">
                <a href="#" class="text-decoration-none text-muted small hover-white">Ver todas las empresas <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Panel: Actividad -->
    <div class="col-lg-4">
        <div class="glass-card h-100">
            <h5 class="fw-bold border-bottom pb-3 mb-4" style="border-color: var(--border-glass) !important;">Actividad del Clúster</h5>
            
            <div class="d-flex gap-3 mb-4">
                <div style="color: #00FF88;"><i class="bi bi-check-circle-fill"></i></div>
                <div>
                    <div class="text-white small fw-bold">Migración de Cartones completada</div>
                    <div class="text-muted" style="font-size: 0.8rem;">Bingo del Norte generó 50K cartones sin colisión.</div>
                    <div class="text-muted mt-1" style="font-size: 0.7rem;">Hace 12 min</div>
                </div>
            </div>
            
            <div class="d-flex gap-3 mb-4">
                <div style="color: #00A8FF;"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                <div>
                    <div class="text-white small fw-bold">Sincronización Bunny.net</div>
                    <div class="text-muted" style="font-size: 0.8rem;">4 nuevos assets publicitarios subidos a CDN.</div>
                    <div class="text-muted mt-1" style="font-size: 0.7rem;">Hace 2 horas</div>
                </div>
            </div>
            
            <div class="d-flex gap-3">
                <div style="color: var(--accent-gold);"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <div class="text-white small fw-bold">Facturación Automática</div>
                    <div class="text-muted" style="font-size: 0.8rem;">Cargo de $25.000 procesado (Sorteos Solidarios).</div>
                    <div class="text-muted mt-1" style="font-size: 0.7rem;">Hace 5 horas</div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
