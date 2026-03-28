@extends('admin.layout')

@section('contenido')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold fs-3 text-white m-0">
            <i class="bi bi-bullseye me-2 text-primary"></i> Jugadas de Prueba
        </h2>
        <span class="badge bg-primary px-3 py-2 rounded-pill">Test Mode</span>
    </div>

    <div class="card bg-dark border-glass shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle m-0 border-0">
                    <thead class="bg-panel text-muted">
                        <tr>
                            <th class="ps-4 py-3 border-0">JUGADA</th>
                            <th class="py-3 border-0">FECHA</th>
                            <th class="py-3 border-0 text-center">ESTADO</th>
                            <th class="pe-4 py-3 border-0 text-end">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jugadas as $j)
                        <tr class="border-glass">
                            <td class="ps-4">
                                <span class="fw-semibold text-white">{{ $j->nombre_jugada }}</span>
                                <div class="text-muted small">#{{ $j->numero_jugada }} — {{ $j->serie }}</div>
                            </td>
                            <td>
                                <div class="text-white">{{ $j->created_at->format('d/m/Y') }}</div>
                                <div class="text-muted small">{{ $j->created_at->format('H:i') }} hs</div>
                            </td>
                            <td class="text-center">
                                @if($j->estado == 'finalizado')
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary px-3">Finalizada</span>
                                @elseif($j->estado == 'en_curso')
                                    <span class="badge bg-success-subtle text-success border border-success px-3">En Curso</span>
                                @else
                                    <span class="badge bg-info-subtle text-info border border-info px-3">{{ ucfirst($j->estado ?? 'Pendiente') }}</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('admin.jugadas.show', $j->id) }}" class="btn btn-sm btn-outline-light border-glass rounded-pill">
                                    <i class="bi bi-eye"></i> Detalle
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                No hay jugadas registradas para pruebas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .border-glass {
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }
    .bg-panel {
        background-color: #0d0d0d !important;
    }
</style>
@endsection
