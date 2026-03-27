@extends('admin.layouts.master')

@section('content')
<div class="top-header">
    <div>
        <h1 class="fw-bold mb-1 text-white" style="font-size: 2.2rem;">Panel de Control Galáctico</h1>
        <p class="text-muted mb-0" style="letter-spacing: 1px; font-size: 0.9rem;">SISTEMA MULTI-TENANT BINGO INFINITY &bull; ESTADO: <span class="text-success fw-bold">OPERATIVO</span></p>
    </div>
    
    <div class="d-flex align-items-center gap-4">
        <div class="text-end d-none d-xl-block">
            <div class="text-white-50 small mb-1">LATENCIA GLOBAL</div>
            <div class="text-success fw-bold" style="font-family: monospace;">12ms <i class="bi bi-broadcast"></i></div>
        </div>
        <div class="owner-badge"><i class="bi bi-shield-lock-fill"></i> Nivel de Acceso: <strong>OWNER SUPREMO</strong></div>
        <button class="btn btn-dark rounded-circle p-2" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-glass); width: 45px; height: 45px;">
            <i class="bi bi-gear-fill"></i>
        </button>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Stat 1: Empresas -->
    <div class="col-md-6 col-lg-3">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div class="icon-box gold">
                    <i class="bi bi-buildings"></i>
                </div>
                <div class="text-end">
                    <span class="badge-neon">+12.5%</span>
                </div>
            </div>
            <p class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 2px;">Empresas Activas</p>
            <div class="stat-value">{{ $organizadores->count() }}</div>
            <div class="progress mt-3" style="height: 4px; background: rgba(255,255,255,0.05);">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 75%"></div>
            </div>
        </div>
    </div>
    
    <!-- Stat 2: Streaming -->
    <div class="col-md-6 col-lg-3">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div class="icon-box emerald">
                    <i class="bi bi-broadcast"></i>
                </div>
                <div class="text-end">
                    <div class="spinner-grow spinner-grow-sm text-success" role="status"></div>
                </div>
            </div>
            <p class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 2px;">Bunny Streamings</p>
            <div class="stat-value">08</div>
            <div class="mt-2 text-white-50 small"><i class="bi bi-check2-circle text-success me-1"></i> Ancho de banda: 4.2TB</div>
        </div>
    </div>
    
    <!-- Stat 3: Storage -->
    <div class="col-md-6 col-lg-3">
        <div class="glass-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div class="icon-box blue">
                    <i class="bi bi-cloud-check"></i>
                </div>
            </div>
            <p class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 2px;">Bunny Storage</p>
            <div class="stat-value">412<span style="font-size: 1.2rem; margin-left: 5px;">GB</span></div>
            <div class="mt-2 text-white-50 small">Capacidad total: 2TB</div>
        </div>
    </div>
    
    <!-- Stat 4: Revenue -->
    <div class="col-md-6 col-lg-3">
        <div class="glass-card h-100" style="border-left: 2px solid var(--accent-gold);">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div class="icon-box purple">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1 small">SaaS Revenue</span>
            </div>
            <p class="text-muted small fw-bold mb-1 text-uppercase" style="letter-spacing: 2px;">Facturación Mensual</p>
            <div class="stat-value">$325.0K</div>
            <div class="mt-2 text-muted small">Próximo cobro: 01/04/2026</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Main Panel: Lista de Tenants con diseño PREMIUM -->
    <div class="col-lg-8">
        <div class="glass-card h-100 p-0 overflow-hidden">
            <div class="p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-white" style="font-family: 'Outfit';">Central de Franquicias (Tenants)</h5>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control bg-dark border-secondary text-white-50 px-3 py-2 rounded-pill small" placeholder="Buscar empresa..." style="width: 200px; font-size: 0.8rem;">
                    <button class="btn btn-neon rounded-pill px-4" style="background: var(--accent-gold); color: #000; font-weight: 700; font-size: 0.8rem; border: none;">
                        <i class="bi bi-plus-lg me-1"></i> NUEVA EMPRESA
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead style="background: rgba(255,255,255,0.01);">
                        <tr>
                            <th class="py-4 px-4 text-muted small fw-bold border-0">CLIENTE / INFRAESTRUCTURA</th>
                            <th class="py-4 text-muted small fw-bold border-0 text-center">LICENCIA SaaS</th>
                            <th class="py-4 text-muted small fw-bold border-0 text-center">ESTADO</th>
                            <th class="py-4 px-4 border-0"></th>
                        </tr>
                    </thead>
                    <tbody class="border-0">
                        @forelse($organizadores as $org)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                            <td class="px-4 py-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar shadow-sm" style="background: linear-gradient(135deg, #121212, #000); border: 1px solid var(--border-glass);">
                                        <span class="text-white-50 fw-bold">{{ substr($org->nombre_fantasia, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-white fw-bold" style="letter-spacing: 0.5px;">{{ mb_strtoupper($org->nombre_fantasia) }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">ID: {{ str_pad($org->id, 5, '0', STR_PAD_LEFT) }} &bull; {{ $org->email_contacto }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge border border-warning text-warning bg-transparent rounded-pill px-3 py-2" style="font-size: 0.7rem; background: rgba(212, 175, 55, 0.05) !important;">
                                    <i class="bi bi-gem me-1"></i> PLAN PRO GOLD
                                </span>
                            </td>
                            <td class="text-center">
                                @if($org->activo)
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="position-relative d-flex" style="width: 8px; height: 8px;">
                                            <span class="animate-ping position-absolute h-100 w-100 rounded-full bg-success opacity-75" style="border-radius: 50%; width: 8px; height: 8px; background: rgba(0,255,0,0.5);"></span>
                                            <span class="position-relative rounded-full bg-success" style="border-radius: 50%; width: 8px; height: 8px; background: #00FF88;"></span>
                                        </span>
                                        <span class="text-success small fw-bold">ONLINE</span>
                                    </div>
                                @else
                                    <span class="badge border border-danger text-danger bg-transparent rounded-pill px-3">BLOQUEADO</span>
                                @endif
                            </td>
                            <td class="px-4 text-end">
                                <div class="btn-group gap-2">
                                    <button class="btn btn-sm btn-outline-secondary border-0" title="Configurar Módulos" data-bs-toggle="modal" data-bs-target="#configModal{{ $org->id }}">
                                        <i class="bi bi-sliders fs-5"></i>
                                    </button>
                                    <a href="{{ route('admin.impersonate', $org->id) }}" class="btn btn-outline-light rounded-pill px-4 py-2" style="font-size: 0.75rem; border: 1px solid rgba(255,255,255,0.1); font-weight: 600;">
                                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i> VISIÓN OMNISCIENTE
                                    </a>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL PARAMETRIC --}}
                        <div class="modal fade" id="configModal{{ $org->id }}" tabindex="-1" data-bs-theme="dark">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="background: #080808; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; box-shadow: 0 0 50px #000;">
                                    <div class="modal-header border-0 pt-4 px-4 pb-2">
                                        <h5 class="modal-title fw-bold text-white">Gestión de Licencias: <span class="text-warning">{{ $org->nombre_fantasia }}</span></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <p class="text-muted small mb-4">Activa o restringe módulos específicos para esta empresa. Los cambios se aplican en tiempo real.</p>
                                        
                                        @php $modulos = [
                                            ['icon' => 'printer', 'title' => 'Talonarios Físicos', 'desc' => 'PDF de alta resolución para imprenta.'],
                                            ['icon' => 'joystick', 'title' => 'Sorteador Operativo', 'desc' => 'Control de mesa y bolillero virtual.'],
                                            ['icon' => 'display', 'title' => 'Monitor TV Sala', 'desc' => 'Interface para proyectores y pantallas.'],
                                            ['icon' => 'camera-video', 'title' => 'Streaming Bunny', 'desc' => 'Transmisión en vivo integrada.'],
                                            ['icon' => 'globe', 'title' => 'Lobby Telebingo', 'desc' => 'Interface B2C para jugadores online.']
                                        ]; @endphp

                                        @foreach($modulos as $mod)
                                        <div class="d-flex align-items-center justify-content-between p-3 rounded-4 mb-2" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.03);">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="icon-box" style="width: 40px; height: 40px; font-size: 1.2rem; background: rgba(255,255,255,0.03);">
                                                    <i class="bi bi-{{ $mod['icon'] }}"></i>
                                                </div>
                                                <div>
                                                    <div class="text-white small fw-bold">{{ $mod['title'] }}</div>
                                                    <div class="text-muted" style="font-size: 0.65rem;">{{ $mod['desc'] }}</div>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" checked>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="modal-footer border-0 p-4">
                                        <button type="button" class="btn btn-dark w-100 rounded-pill py-3 fw-bold" style="background: var(--accent-gold); color: #000;" data-bs-dismiss="modal">ACTUALIZAR LICENCIAS</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted py-5">
                                    <i class="bi bi-box-seam fs-1 opacity-25 mb-3 d-block"></i>
                                    No se encontraron empresas registradas.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 text-center">
                <a href="#" class="text-muted text-decoration-none small hover-white">Ver reporte completo de la red <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Panel: Actividad con Estética OLED -->
    <div class="col-lg-4">
        <div class="glass-card h-100">
            <h5 class="fw-bold mb-4 text-white" style="font-family: 'Outfit';">Log de Actividad Global</h5>
            
            <div class="activity-timeline">
                <div class="d-flex gap-3 mb-4 position-relative">
                    <div style="z-index: 1;"><i class="bi bi-check-circle-fill text-success fs-5"></i></div>
                    <div class="pb-4 border-start border-secondary px-3 position-absolute" style="left: 10px; top: 25px; height: 80%;"></div>
                    <div>
                        <div class="text-white fs-6 fw-bold">Sorteo Finalizado</div>
                        <div class="text-muted small"><strong>Bingo Central</strong> cerró jugada con 1.5M en premios.</div>
                        <div class="text-info mt-1" style="font-size: 0.65rem; font-family: monospace;">Hace 4 min</div>
                    </div>
                </div>
                
                <div class="d-flex gap-3 mb-4 position-relative">
                    <div style="z-index: 1;"><i class="bi bi-cloud-plus-fill text-info fs-5"></i></div>
                    <div class="pb-4 border-start border-secondary px-3 position-absolute" style="left: 10px; top: 25px; height: 80%;"></div>
                    <div>
                        <div class="text-white fs-6 fw-bold">Nuevos Cartones (CDN)</div>
                        <div class="text-muted small"><strong>Club Social</strong> subió 5,000 cartones a Bunny.net.</div>
                        <div class="text-info mt-1" style="font-size: 0.65rem; font-family: monospace;">Hace 2 horas</div>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-4">
                    <div style="z-index: 1;"><i class="bi bi-incognito text-warning fs-5"></i></div>
                    <div>
                        <div class="text-white fs-6 fw-bold">Acceso Omnisciente</div>
                        <div class="text-muted small">El administrador entró a <strong>Casino Royal</strong>.</div>
                        <div class="text-info mt-1" style="font-size: 0.65rem; font-family: monospace;">Ayer a las 23:15</div>
                    </div>
                </div>
            </div>

            <div class="glass-card mt-5 p-3" style="background: rgba(0,210,255,0.05); border-color: rgba(0,210,255,0.1);">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-info-circle text-info fs-4"></i>
                    <div class="small text-white-50">Próximo mantenimiento programado: <strong>01/04 - 03:00 AM</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    .hover-white:hover {
        color: white !important;
    }
    .btn-neon:hover {
        box-shadow: 0 0 20px rgba(212, 175, 55, 0.4);
        transform: scale(1.05);
    }
</style>
@endsection
