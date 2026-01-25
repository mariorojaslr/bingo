@extends('admin.layout')

@section('contenido')

<h2 class="mb-3">Visor Profesional de Cartones</h2>

<form method="GET" action="{{ route('admin.cartones.listado') }}" class="row g-3 mb-4 align-items-end">

    <div class="col-auto">
        <label class="form-label">Columnas</label>
        <select name="columnas" class="form-select form-select-sm">
            @for($i=1;$i<=4;$i++)
                <option value="{{ $i }}" {{ request('columnas',3)==$i?'selected':'' }}>{{ $i }}</option>
            @endfor
        </select>
    </div>

    <div class="col-auto">
        <label class="form-label">Filas</label>
        <select name="filas" class="form-select form-select-sm">
            @for($i=1;$i<=4;$i++)
                <option value="{{ $i }}" {{ request('filas',2)==$i?'selected':'' }}>{{ $i }}</option>
            @endfor
        </select>
    </div>

    <div class="col-auto">
        <label class="form-label">Ir al cartón Nº</label>
        <input type="number" name="numero" class="form-control form-control-sm" placeholder="Ej: 322">
    </div>

    <div class="col-auto">
        <button class="btn btn-primary btn-sm">Aplicar</button>
    </div>

</form>

<div class="row">

@foreach($cartones as $carton)

    @php $grilla = is_array($carton->grilla) ? $carton->grilla : json_decode($carton->grilla, true); @endphp

    <div class="col-md-{{ 12 / $columnas }} mb-4">

        <div class="border p-2 bg-white shadow-sm">

            <div class="fw-bold mb-1">Cartón Nº {{ $carton->numero_carton }}</div>

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
