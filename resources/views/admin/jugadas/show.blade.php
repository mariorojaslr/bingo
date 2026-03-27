@extends('admin.layout')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h6 class="text-uppercase" style="color: var(--neon-blue); font-weight: 600; letter-spacing: 2px;"><i class="bi bi-router me-2"></i>ESTACIÓN DE CONTROL DE SALA</h6>
        <h2 class="display-6 fw-bold mb-0 text-white" style="font-family: 'Outfit';">{{ mb_strtoupper($jugada->nombre_jugada) }}</h2>
    </div>
    <a href="{{ route('admin.jugadas.index') }}" class="btn btn-outline-secondary rounded-pill px-4">← Salir al Listado</a>
</div>

{{-- PANEL PRINCIPAL DE MÉTRICAS --}}
<div class="row g-4 mb-5">
    <!-- INFO BÁSICA -->
    <div class="col-lg-8">
        <div class="glass-card h-100 position-relative p-4" style="border-radius: 20px;">
            <div class="position-absolute top-0 end-0 m-4">
                <span class="badge rounded-pill px-3 py-2" style="background: rgba(0, 168, 255, 0.1); color: var(--neon-blue); border: 1px solid var(--neon-blue);">
                    ESTADO: {{ strtoupper(str_replace('_',' ', $jugada->estado)) }}
                </span>
            </div>
            <h5 class="text-white fw-bold mb-4" style="font-family: 'Outfit';"><i class="bi bi-info-circle me-2 text-white-50"></i>Ficha Técnica del Evento</h5>
            
            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <p class="text-muted small mb-1 text-uppercase">Organizador / Titular</p>
                    <h6 class="text-white"><i class="bi bi-building me-2 text-white-50"></i>{{ $jugada->organizador->nombre_fantasia }}</h6>
                </div>
                <div class="col-md-4">
                    <p class="text-muted small mb-1 text-uppercase">Institución a Beneficio</p>
                    <h6 class="text-white"><i class="bi bi-bank me-2 text-white-50"></i>{{ $jugada->institucion->nombre }}</h6>
                </div>
                <div class="col-md-4">
                    <p class="text-muted small mb-1 text-uppercase">Logística de Impresión</p>
                    <h6 class="text-white"><i class="bi bi-file-earmark-ruled me-2 text-white-50"></i>{{ $jugada->cartones_por_hoja }} Cartones x Hoja</h6>
                </div>
                <div class="col-md-4">
                    <p class="text-muted small mb-1 text-uppercase">Fecha y Hora</p>
                    <h6 class="text-white"><i class="bi bi-calendar-event me-2 text-white-50"></i>{{ \Carbon\Carbon::parse($jugada->fecha_evento)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($jugada->hora_evento)->format('H:i') }}H</h6>
                </div>
                <div class="col-md-8">
                    <p class="text-muted small mb-1 text-uppercase">Lugar Físico / Streaming</p>
                    <h6 class="text-white"><i class="bi bi-geo-alt me-2 text-white-50"></i>{{ $jugada->lugar }}</h6>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ACCESOS RÁPIDOS MÓDULOS -->
    <div class="col-lg-4">
        <div class="glass-card h-100 p-4" style="border-radius: 20px; background: linear-gradient(135deg, rgba(20,20,25,0.9), rgba(5,5,10,0.9)); border-color: rgba(255,255,255,0.1);">
            <h5 class="text-white fw-bold mb-4" style="font-family: 'Outfit';"><i class="bi bi-toggles me-2 text-white-50"></i>Lanzamiento de Módulos</h5>
            
            <div class="d-grid gap-3">
                <a href="/sorteador/jugada/{{ $jugada->id }}" target="_blank" class="btn btn-outline-light text-start py-3" style="border-radius: 12px; border-color: rgba(255,255,255,0.1) !important;">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark text-white rounded p-2 me-3 fs-4"><i class="bi bi-joystick"></i></div>
                        <div>
                            <div class="fw-bold" style="letter-spacing: 1px;">Sorteador Operativo</div>
                            <div class="small text-white-50">Control de mesa en vivo</div>
                        </div>
                    </div>
                </a>
                
                <a href="/monitor/jugada/{{ $jugada->id }}" target="_blank" class="btn btn-outline-light text-start py-3" style="border-radius: 12px; border-color: rgba(255,255,255,0.1) !important;">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark text-white rounded p-2 me-3 fs-4"><i class="bi bi-display"></i></div>
                        <div>
                            <div class="fw-bold" style="letter-spacing: 1px;">Monitor Clásico (Matriz)</div>
                            <div class="small text-white-50">Proyector de sala 1-90</div>
                        </div>
                    </div>
                </a>
                
                <a href="/monitor-tv/{{ $jugada->id }}" target="_blank" class="btn btn-outline-light text-start py-3" style="border-radius: 12px; border-color: rgba(255,255,255,0.1) !important;">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark text-white rounded p-2 me-3 fs-4"><i class="bi bi-camera-video"></i></div>
                        <div>
                            <div class="fw-bold" style="letter-spacing: 1px;">Monitor Audiovisual (TV)</div>
                            <div class="small text-white-50">Streaming Bunny Integrado</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- CONFIGURACIÓN DE STREAMING Y OBS --}}
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="glass-card p-4" style="border-radius: 20px; border-left: 4px solid var(--neon-blue);">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h5 class="text-white fw-bold mb-2" style="font-family: 'Outfit';"><i class="bi bi-broadcast me-2 text-info"></i>Configuración de Streaming (OBS / Directo)</h5>
                    <p class="text-white-50 small mb-4">Usa estos datos en tu software de transmisión (OBS Studio, vMix) para conectar la sala con el Monitor TV.</p>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small text-uppercase">Servidor RTMP (Bunny)</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-dark text-white-50 border-secondary small" value="{{ $jugada->streaming_server ?? 'rtmp://video.bunny.net/live' }}" readonly>
                                <button class="btn btn-outline-secondary btn-sm" onclick="navigator.clipboard.writeText('{{ $jugada->streaming_server ?? 'rtmp://video.bunny.net/live' }}')"><i class="bi bi-copy"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small text-uppercase">Clave de Retransmisión</label>
                            <div class="input-group">
                                <input type="password" class="form-control bg-dark text-white-50 border-secondary small" value="{{ $jugada->streaming_key ?? '••••••••••••' }}" readonly id="streamKey">
                                <button class="btn btn-outline-secondary btn-sm" onclick="let x = document.getElementById('streamKey'); x.type = x.type==='password'?'text':'password';"><i class="bi bi-eye"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-end">
                    <button class="btn btn-outline-info rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalStreaming">
                        <i class="bi bi-gear-fill me-2"></i> Configurar Canal Bunny
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CONFIGURACIÓN STREAMING --}}
<div class="modal fade" id="modalStreaming" tabindex="-1" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.jugadas.streaming.update', $jugada->id) }}">
            @csrf
            <div class="modal-content" style="background: #0d0d0d; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px;">
                <div class="modal-header border-bottom border-secondary pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="font-family: 'Outfit'; color: var(--neon-blue);"><i class="bi bi-broadcast-pin me-2"></i>Ajustes de Transmisión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-white-50 small fw-bold">URL DE REPRODUCCIÓN (IFRAME/HLS)</label>
                        <input type="text" name="streaming_url" class="form-control bg-dark text-white border-secondary" value="{{ $jugada->streaming_url }}" placeholder="URL de YouTube, Vimeo o ID de Bunny">
                        <div class="form-text small text-muted">Si usas Bunny, pon solo el ID del video si prefieres.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-white-50 small fw-bold">RTMP SERVER (Para OBS)</label>
                        <input type="text" name="streaming_server" class="form-control bg-dark text-white border-secondary" value="{{ $jugada->streaming_server }}" placeholder="rtmp://video.bunny.net/live">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white-50 small fw-bold">STREAM KEY (Para OBS)</label>
                        <input type="text" name="streaming_key" class="form-control bg-dark text-white border-secondary" value="{{ $jugada->streaming_key }}" placeholder="Tu clave de flujo">
                    </div>

                    <hr class="border-secondary my-4">
                    
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label text-white-50 small fw-bold">BUNNY LIBRARY ID</label>
                            <input type="text" name="bunny_library_id" class="form-control bg-dark text-white border-secondary" value="{{ $jugada->bunny_library_id }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-white-50 small fw-bold">BUNNY STREAM ID</label>
                            <input type="text" name="bunny_live_stream_id" class="form-control bg-dark text-white border-secondary" value="{{ $jugada->bunny_live_stream_id }}">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top border-secondary px-4 py-3">
                    <button type="button" class="btn text-white-50" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info rounded-pill px-4">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- TABLA DE LOTES (ÓRDENES DE EXTRACCIÓN DEL ESTANQUE) --}}
<div class="glass-card mb-4" style="border-radius: 20px;">
    <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center p-4 bg-transparent">
        <div>
            <h5 class="mb-0 text-white fw-bold" style="font-family: 'Outfit';"><i class="bi bi-box-seam me-2 text-white-50"></i>Lotes de Extracción (Talonarios)</h5>
            <p class="text-white-50 small mb-0 mt-1">Los cartones se extraen aleatoriamente de la Pecera Maestra del Servidor.</p>
        </div>
        <button class="btn btn-neon rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalNuevoLote">
            <i class="bi bi-cart-plus me-2"></i> Solicitar Talonario
        </button>
    </div>

    <div class="card-body p-0">
        @if($lotes->count() == 0)
            <div class="text-center py-5">
                <i class="bi bi-inboxes text-white-50" style="font-size: 3rem; opacity: 0.2;"></i>
                <p class="text-secondary mt-3">No hay talonarios generados. Solicita uno para poblar la sala.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0" style="background: transparent;">
                    <thead style="background: rgba(0,0,0,0.5);">
                        <tr>
                            <th class="border-secondary text-secondary fw-normal py-3 px-4">LOTE ID</th>
                            <th class="border-secondary text-secondary fw-normal py-3">CREACIÓN</th>
                            <th class="border-secondary text-secondary fw-normal py-3 text-center">CANTIDAD TRASPASADA</th>
                            <th class="border-secondary text-secondary fw-normal py-3 text-center">COMPOSICIÓN PAPEL</th>
                            <th class="border-secondary text-secondary fw-normal py-3 text-center">ESTADO</th>
                            <th class="border-secondary text-end fw-normal py-3 px-4">IMPRESIÓN / ACCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lotes as $lote)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td class="px-4 text-white fw-bold" style="font-family: monospace;">{{ $lote->codigo_lote }}</td>
                                <td class="text-white-50 small">{{ $lote->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <span class="fs-5 text-white fw-bold">{{ $lote->cantidad_cartones }}</span>
                                    <span class="text-muted small d-block">Cartones Matemáticos</span>
                                </td>
                                <td class="text-center">
                                    <span class="fs-5 text-white fw-bold">{{ $lote->cantidad_hojas }}</span>
                                    <span class="text-muted small d-block">Hojas PDF ({{ $jugada->cartones_por_hoja }} x/h)</span>
                                </td>
                                <td class="text-center align-middle">
                                    @if($lote->estado === 'pedido')
                                        <span class="badge border border-warning text-warning bg-transparent rounded-pill px-3">PENDIENTE EN PECERA</span>
                                    @elseif($lote->estado === 'generado')
                                        <span class="badge border border-info text-info bg-transparent rounded-pill px-3">ASIGNADOS A JUGADA</span>
                                    @elseif($lote->estado === 'en_impresion')
                                        <span class="badge border border-success text-success bg-transparent rounded-pill px-3">LISTO PARA IMPRIMIR</span>
                                    @endif
                                </td>
                                <td class="text-end px-4 align-middle">
                                    @if($lote->estado === 'pedido')
                                        <form action="{{ route('admin.lotes.generar', $lote->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-warning rounded-pill px-3"><i class="bi bi-cpu me-1"></i> Invocar Generador</button>
                                        </form>

                                    @elseif($lote->estado === 'generado')
                                        <form action="{{ route('admin.lotes.materializar', $lote->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-info rounded-pill px-3"><i class="bi bi-file-earmark-pdf me-1"></i> Generar Archivos Láser</button>
                                        </form>

                                    @elseif($lote->estado === 'en_impresion')
                                        <!-- AQUÍ EVENTUALMENTE IRÁ LA FÁBRICA LASER (PDF REAL) -->
                                        <a href="{{ route('admin.visor.lote', $lote->id) }}" class="btn btn-sm" style="background: var(--neon-green); color: #000; font-weight: 600; border-radius: 50px; px-3" target="_blank">
                                            <i class="bi bi-printer-fill me-1"></i> Despachar a Láser (Visor)
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- MODAL SOLICITUD DE EXTRACCIÓN A PECERA --}}
<div class="modal fade" id="modalNuevoLote" tabindex="-1" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('admin.jugadas.lotes.crear', $jugada->id) }}">
            @csrf
            <div class="modal-content" style="background: #0d0d0d; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px;">
                <div class="modal-header border-bottom border-secondary pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="font-family: 'Outfit'; color: var(--neon-blue);"><i class="bi bi-box-arrow-in-down me-2"></i>Extracción de Pecera</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Indica la cantidad de cartones que necesitas vender/imprimir. El sistema los extraerá del pozo inagotable oficial y garantizará cero colisiones numéricas.</p>
                    
                    <div class="mb-4">
                        <label class="form-label text-white-50 small fw-bold">CANTIDAD DE CARTONES FÍSICOS REQUERIDOS</label>
                        <input type="number" class="form-control form-control-lg bg-dark text-white border-secondary" id="cantidad_cartones" name="cantidad_cartones" placeholder="Ej: 1000" style="font-family: monospace; font-size: 1.5rem;" required>
                    </div>

                    <div class="p-3 rounded" style="background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.1);">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Cartones por Hoja de Impresión:</span>
                            <span class="text-white fw-bold">{{ $jugada->cartones_por_hoja }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Total Resmas/Hojas Requeridas:</span>
                            <span class="text-white fw-bold" id="resumen_hojas" style="color: var(--neon-green) !important;">0</span>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs if needed by Controller (based on previous code) -->
                <input type="hidden" name="cantidad_hojas" id="hidden_hojas">
                <input type="hidden" name="costo_generacion" value="0">

                <div class="modal-footer border-top border-secondary px-4 py-3">
                    <button type="button" class="btn text-white-50" data-bs-dismiss="modal">Cancelar Extracción</button>
                    <button type="submit" class="btn btn-neon rounded-pill px-4"><i class="bi bi-cpu me-2"></i> Confirmar Extracción</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('cantidad_cartones').addEventListener('input', function(e) {
        const cant = parseInt(e.target.value) || 0;
        const xhoja = {{ $jugada->cartones_por_hoja }};
        const hojas = Math.ceil(cant / xhoja);
        document.getElementById('resumen_hojas').innerText = hojas;
        document.getElementById('hidden_hojas').value = hojas;
    });
</script>

@endsection
