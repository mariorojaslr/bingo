@extends('admin.layout')

@section('contenido')

<h2 class="mb-3">Visor Profesional de Cartones</h2>

<div class="card p-4 mb-5 border-0 shadow" style="background: linear-gradient(135deg, #0d1117 0%, #161b22 100%);">
    <div class="row align-items-center text-center text-md-start">
        <div class="col-md-8">
            <h1 class="text-uppercase mb-2" style="color: var(--accent); font-weight: 900; letter-spacing: 2px;">
                Infinity Bingo <span class="text-white">PRO</span>
            </h1>
            <h4 class="text-muted mb-3">SERIE: {{ $serieFiltro }}</h4>
            <p class="mb-0" style="font-size: 1.1rem; line-height: 1.6;">
                En esta serie se han pre-computado <strong>{{ number_format($totalCartones, 0, ',', '.') }} cartones</strong> con validación matemática estricta y algoritmo <em>Antibombas</em> para garantizar cero colisiones (Cartones únicos garantizados).
            </p>
            <p class="text-muted mt-2 mb-0" style="font-size: 0.9rem;">
                * La arquitectura <em>Elastic-Pool</em> del sistema permite escalar la generación a 100.000 o 1.000.000 de cartones simultáneos bajo demanda en cuestión de minutos.
            </p>
        </div>
        <div class="col-md-4 text-center mt-4 mt-md-0">
            <div class="p-3 border rounded" style="background: rgba(0, 168, 255, 0.1); border-color: var(--accent) !important;">
                <h5 class="text-white mb-1">Volumen Actual</h5>
                <h2 class="mb-0" style="color: var(--accent); font-weight: 800;">{{ number_format($totalCartones, 0, ',', '.') }}</h2>
                <small class="text-uppercase" style="letter-spacing: 1px;">Cartones Disponibles</small>
            </div>
        </div>
    </div>
</div>

<div class="row">

@foreach($cartones as $carton)

    @php $grilla = is_array($carton->grilla) ? $carton->grilla : json_decode($carton->grilla, true); @endphp

    <div class="col-md-{{ 12 / $columnas }} mb-4">

        <div class="border p-2 bg-white shadow-sm">

            <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                <div style="font-size: 0.75rem; color: #666;">
                    ID: {{ $carton->numero_carton }}
                </div>
                <div class="fw-bold fs-5" style="letter-spacing: 2px;">
                    {{ $carton->numero_suerte ?? '0000000' }}
                </div>
            </div>

            <table class="tabla-bingo">
                @foreach($grilla as $fila)
                    <tr>
                        @foreach($fila as $valor)
                            @if($valor == 0)
                                <td class="vacio"></td>
                            @else
                                <td class="numero">{{ $valor }}</td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </table>

        </div>

    </div>

@endforeach

</div>

<div class="d-flex justify-content-center mt-3">
    {{ $cartones->withQueryString()->links() }}
</div>

<style>
.tabla-bingo {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.tabla-bingo td {
    border: 1px solid #000;
    height: 42px;
    text-align: center;
    vertical-align: middle;
    font-family: Helvetica, Arial, sans-serif;
    font-size: 22px;
    font-weight: bold;
}

.tabla-bingo td.numero {
    background: #ffffff;
    color: #000000;
}

.tabla-bingo td.vacio {
    background: #e0e0e0; /* gris 15% */
}
</style>


<style>
/* Normaliza tamaño del paginador */
.pagination {
    font-size: 14px !important;
}

.pagination svg {
    width: 16px !important;
    height: 16px !important;
}

.pagination li {
    margin: 0 2px;
}

.pagination a,
.pagination span {
    padding: 4px 8px !important;
}
</style>



@endsection
