@extends('admin.layout')

@section('contenido')

{{-- ================= ENCABEZADO ================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>🎯 Jugada: {{ $jugada->nombre_jugada }}</h2>
    <a href="{{ route('admin.jugadas.index') }}" class="btn btn-secondary">← Volver</a>
</div>

{{-- ================= DATOS GENERALES ================= --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4"><strong>Organizador:</strong> {{ $jugada->organizador->nombre_fantasia }}</div>
            <div class="col-md-4"><strong>Institución:</strong> {{ $jugada->institucion->nombre }}</div>
            <div class="col-md-4">
                <strong>Estado:</strong>
                <span class="badge bg-primary">{{ strtoupper(str_replace('_',' ', $jugada->estado)) }}</span>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-3"><strong>Fecha:</strong> {{ $jugada->fecha_evento }}</div>
            <div class="col-md-3"><strong>Hora:</strong> {{ $jugada->hora_evento }}</div>
            <div class="col-md-3"><strong>Lugar:</strong> {{ $jugada->lugar }}</div>
            <div class="col-md-3"><strong>Formato:</strong> {{ $jugada->cartones_por_hoja }} por hoja</div>
        </div>

        <div class="row mt-2">
            <div class="col-md-3"><strong>Precio hoja:</strong> ${{ number_format($jugada->precio_hoja,2) }}</div>
        </div>
    </div>
</div>

{{-- ================= LOTES ================= --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>📦 Lotes de Producción</strong>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoLote">
            ➕ Nuevo Pedido
        </button>
    </div>

    <div class="card-body">
        @if($lotes->count() == 0)
            <p class="text-muted">No hay pedidos de lotes aún.</p>
        @else
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Lote</th>
                        <th>Fecha</th>
                        <th>Cartones</th>
                        <th>Hojas</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lotes as $lote)
                        <tr>
                            <td>{{ $lote->codigo_lote }}</td>
                            <td>{{ $lote->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $lote->cantidad_cartones }}</td>
                            <td>{{ $lote->cantidad_hojas }}</td>
                            <td>${{ number_format($lote->total_general,2) }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ strtoupper(str_replace('_',' ', $lote->estado)) }}
                                </span>
                            </td>
                            <td class="text-center">

                                @if($lote->estado === 'pedido')
                                    <form action="{{ route('admin.lotes.generar', $lote->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-warning">⚙ Generar</button>
                                    </form>

                                @elseif($lote->estado === 'generado')
                                    <form action="{{ route('admin.lotes.materializar', $lote->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">🏭 Materializar</button>
                                    </form>

                                @elseif($lote->estado === 'en_impresion')
                                    <a href="{{ route('admin.visor.lote', $lote->id) }}"
                                       class="btn btn-sm btn-primary" target="_blank">
                                        👁 Ver Cartones
                                    </a>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

{{-- ================= MODAL NUEVO PEDIDO ================= --}}
<div class="modal fade" id="modalNuevoLote" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ route('admin.jugadas.lotes.crear', $jugada->id) }}">
            @csrf
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">📦 Nuevo Pedido de Producción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Cantidad de Cartones</label>
                            <input type="number" class="form-control" id="cantidad_cartones" name="cantidad_cartones">
                        </div>
                        <div class="col-md-6">
                            <label>Cantidad de Hojas</label>
                            <input type="number" class="form-control" id="cantidad_hojas" name="cantidad_hojas">
                        </div>
                    </div>

                    <hr>

                    <div class="row text-center mb-2">
                        <div class="col"><strong>Formato:</strong> {{ $jugada->cartones_por_hoja }} por hoja</div>
                        <div class="col"><strong>Precio hoja:</strong> ${{ number_format($jugada->precio_hoja,2) }}</div>
                        <div class="col"><strong>Impresión:</strong> $<span id="total_impresion">0.00</span></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Costo de generación por cartón</label>
                            <input type="number" step="0.01" class="form-control" id="costo_unitario" name="costo_generacion" value="0">
                        </div>
                        <div class="col-md-6">
                            <label>Total General</label>
                            <input type="text" class="form-control" id="total_general" readonly>
                        </div>
                    </div>

                    <input type="hidden" id="cartones_por_hoja" value="{{ $jugada->cartones_por_hoja }}">
                    <input type="hidden" id="precio_hoja" value="{{ $jugada->precio_hoja }}">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">✔ Registrar Pedido</button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- ================= SCRIPT PROFESIONAL ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const cartonesInput = document.getElementById('cantidad_cartones');
    const hojasInput = document.getElementById('cantidad_hojas');
    const costoUnitarioInput = document.getElementById('costo_unitario');

    const cartonesPorHoja = parseInt(document.getElementById('cartones_por_hoja').value);
    const precioHoja = parseFloat(document.getElementById('precio_hoja').value);

    const totalImpresionSpan = document.getElementById('total_impresion');
    const totalGeneralInput = document.getElementById('total_general');
    const modal = document.getElementById('modalNuevoLote');

    function recalcularTotales() {
        let cartones = parseInt(cartonesInput.value) || 0;
        let hojas = parseInt(hojasInput.value) || 0;
        let costoUnitario = parseFloat(costoUnitarioInput.value) || 0;

        let totalImpresion = hojas * precioHoja;
        let totalGeneracion = cartones * costoUnitario;
        let totalGeneral = totalImpresion + totalGeneracion;

        totalImpresionSpan.innerText = totalImpresion.toFixed(2);
        totalGeneralInput.value = '$ ' + totalGeneral.toFixed(2);
    }

    function desdeCartones() {
        let cartones = parseInt(cartonesInput.value) || 0;
        let hojas = Math.ceil(cartones / cartonesPorHoja);
        hojasInput.value = cartones > 0 ? hojas : '';
        recalcularTotales();
    }

    function desdeHojas() {
        let hojas = parseInt(hojasInput.value) || 0;
        let cartones = hojas * cartonesPorHoja;
        cartonesInput.value = hojas > 0 ? cartones : '';
        recalcularTotales();
    }

    cartonesInput.addEventListener('input', desdeCartones);
    hojasInput.addEventListener('input', desdeHojas);
    costoUnitarioInput.addEventListener('input', recalcularTotales);

    modal.addEventListener('show.bs.modal', function () {
        cartonesInput.value = '';
        hojasInput.value = '';
        costoUnitarioInput.value = 0;
        totalImpresionSpan.innerText = '0.00';
        totalGeneralInput.value = '$ 0.00';
    });

});
</script>

@endsection
