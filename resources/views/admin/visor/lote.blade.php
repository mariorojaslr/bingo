@extends('admin.layout')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h6 class="text-uppercase" style="color: var(--neon-green); font-weight: 600; letter-spacing: 2px;">VISTA DE OJO DE HALCÓN</h6>
        <h2 class="display-6 fw-bold mb-0 text-white" style="font-family: 'Outfit';">Visor de Lote para Impresión</h2>
    </div>
    <div class="d-flex gap-3">
        <button onclick="window.print()" class="btn btn-neon rounded-pill px-4"><i class="bi bi-printer me-2"></i> Enviar a Láser</button>
        <a href="{{ route('admin.jugadas.show', $lote->jugada_id) }}" class="btn btn-outline-secondary rounded-pill px-4"><i class="bi bi-x-lg me-2"></i> Cerrar Visor</a>
    </div>
</div>

<div class="glass-card mb-4" style="border-radius: 16px;">
    <div class="card-body p-4 text-white-50 small">
        <i class="bi bi-info-circle me-1"></i> Este tablero muestra el formato crudo en alto contraste listo para la impresora fotocopiadora. El número de 'Sorteo Consuelo' está pre-inyectado en la esquina superior derecha de cada cartón.
    </div>
</div>

{{-- AREA DE IMPRESION - SOLO LO QUE SE IMPRIME --}}
<div class="area-impresion mt-4" style="background: #000; padding: 2rem; border-radius: 20px;">
    
    <div class="d-grid" style="grid-template-columns: repeat({{ $columnas }}, 1fr); gap: 30px;">
    @foreach($cartones as $carton)
        <div class="carton-laser">
            <div class="carton-header">
                <div class="fw-bold" style="font-size: 10px; letter-spacing: 1px;">
                    SERIE: {{ mb_strtoupper($lote->jugada->nombre_jugada) }}
                </div>
                <div class="fw-bold fs-6" style="letter-spacing: 1px;">
                    Sorteo Consuelo: <span style="font-size: 1.1rem; border-bottom: 2px solid #000;">{{ 1000 + $carton->numero }}</span>
                </div>
            </div>

            <table class="carton-tabla">
                <colgroup>
                    @for($i=1; $i<=9; $i++)
                        <col style="width:11.11%">
                    @endfor
                </colgroup>
                @foreach($carton->grilla as $fila)
                    <tr>
                        @foreach($fila as $celda)
                            <td class="{{ $celda == 0 ? 'vacio' : '' }}">
                                {{ $celda ?: '' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
            
            <div class="carton-footer">
                ID SISTEMA: {{ str_pad($carton->id, 8, '0', STR_PAD_LEFT) }} | IMPRESIÓN OFICIAL INFINITY
            </div>
        </div>
    @endforeach
    </div>

</div>

<div class="mt-4 pb-5 d-flex justify-content-center">
    <!-- Paginación con estilo oscuro -->
    <div class="pagination-dark">
        {{ $cartones->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
/* CSS PARA LA PANTALLA MODO DARK */
.pagination-dark .pagination {
    --bs-pagination-bg: rgba(255,255,255,0.05);
    --bs-pagination-border-color: rgba(255,255,255,0.1);
    --bs-pagination-color: #fff;
    --bs-pagination-hover-bg: rgba(255,255,255,0.1);
    --bs-pagination-hover-color: #00FF88;
}

/* CSS EXACTO PARA EL CARTON LÁSER B/N (INSPIRADO EN LA IMAGEN) */
.carton-laser {
    background: white;
    padding: 15px;
    border: 3px solid #000;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);
    color: #000;
}

.carton-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 10px;
    font-family: 'Arial', sans-serif;
    text-transform: uppercase;
}

.carton-tabla {
    width: 100%;
    border-collapse: collapse;
    border: 4px solid #000;
    table-layout: fixed;
    background: white;
}

.carton-tabla td {
    border: 2px solid #000;
    height: 48px;
    text-align: center;
    vertical-align: middle;
    font-weight: 900;
    font-size: 24px;
    font-family: 'Arial', sans-serif;
    color: #000;
    padding: 0;
}

.carton-tabla td.vacio {
    background: #D5D5D5; /* El fondo gris clásico */
    border: 2px solid #000;
}

.carton-footer {
    text-align: right;
    font-family: 'Courier New', Courier, monospace;
    font-size: 9px;
    font-weight: bold;
    color: #555;
    margin-top: 5px;
    border-top: 1px solid #ccc;
    padding-top: 2px;
}

/* MÉTODO DE IMPRESIÓN -> QUITAR TODO LO QUE NO ES CARTÓN */
@media print {
    body, html {
        background: white !important;
        margin: 0;
        padding: 0;
        min-height: auto;
    }
    .top-navbar, .sidebar, .top-bar, .d-flex.justify-content-between, .glass-card, .mt-4.pb-5 {
        display: none !important;
    }
    .area-impresion {
        background: transparent !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .carton-laser {
        box-shadow: none !important;
        margin-bottom: 20px;
        page-break-inside: avoid;
    }
    .d-grid {
        gap: 20px !important;
    }
}
</style>

@endsection
