@extends('admin.layout')

@section('contenido')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold fs-3 text-white m-0">
            <i class="bi bi-people me-2 text-success"></i> Participantes Piloto
        </h2>
        <span class="text-muted small">Personas reales participando en pruebas</span>
    </div>

    <!-- Formulario de Registro -->
    <div class="card bg-panel border-glass shadow-sm mb-4">
        <div class="card-body p-4">
            <h5 class="text-white mb-3 small text-uppercase fw-bold opacity-75">Nuevo Participante</h5>
            <form action="{{ route('admin.pruebas.participantes.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-glass text-muted"><i class="bi bi-person"></i></span>
                        <input type="text" name="nombre" class="form-control bg-dark border-glass text-white" placeholder="Nombre completo" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-glass text-muted"><i class="bi bi-whatsapp"></i></span>
                        <input type="text" name="telefono" class="form-control bg-dark border-glass text-white" placeholder="Teléfono (opcional)">
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success w-100 fw-bold">
                        <i class="bi bi-person-plus-fill me-2"></i> Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Listado -->
    <div class="card bg-dark border-glass shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle m-0 border-0">
                    <thead class="bg-panel text-muted">
                        <tr>
                            <th class="ps-4 py-3 border-0">NOMBRE</th>
                            <th class="py-3 border-0">CONTACTO</th>
                            <th class="py-3 border-0">LINK DE ACCESO (VISTA JUGADOR)</th>
                            <th class="pe-4 py-3 border-0 text-end">ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($participantes as $p)
                        <tr class="border-glass">
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-panel border-glass d-flex align-items-center justify-content-center text-success fw-bold" style="width: 40px; height: 40px; font-size: 0.8rem;">
                                        {{ strtoupper(substr($p->nombre, 0, 2)) }}
                                    </div>
                                    <span class="fw-semibold text-white">{{ $p->nombre }}</span>
                                </div>
                            </td>
                            <td>
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $p->telefono) }}" target="_blank" class="text-muted text-decoration-none hover-white">
                                    <i class="bi bi-whatsapp text-success me-1"></i> {{ $p->telefono ?? 'N/A' }}
                                </a>
                            </td>
                            <td style="min-width: 300px;">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control bg-panel border-glass text-muted small" 
                                        value="{{ url('/piloto/'.$p->token) }}" readonly id="link-{{ $p->id }}">
                                    <button class="btn btn-outline-secondary border-glass" type="button" onclick="copyLink('link-{{ $p->id }}')">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="pe-4 text-end">
                                <span class="badge bg-success-subtle text-success border border-success px-3">Activo</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted opacity-50">
                                <i class="bi bi-people fs-1 d-block mb-2"></i>
                                No hay participantes registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function copyLink(id) {
    var copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    
    // Feedback visual simple
    alert('Link copiado al portapapeles');
}
</script>

<style>
    .bg-panel { background-color: #0d0d0d !important; }
    .border-glass { border-color: rgba(255, 255, 255, 0.08) !important; }
    .hover-white:hover { color: white !important; }
    .table-hover tbody tr:hover { background-color: rgba(255, 255, 255, 0.03) !important; }
    .form-control:focus { 
        background-color: #151515 !important; 
        border-color: var(--accent) !important; 
        box-shadow: 0 0 10px rgba(0, 168, 255, 0.2); 
    }
</style>
@endsection
