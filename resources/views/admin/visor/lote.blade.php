@extends('admin.layout')

@section('contenido')

<h3>🎯 {{ $lote->jugada->nombre_jugada }}</h3>
<p>{{ $lote->jugada->institucion->nombre }} — {{ $lote->jugada->organizador->nombre_fantasia }}</p>

<form method="GET" class="row mb-3 g-2">
    <div class="col-auto">
        <label>Columnas</label>
        <input type="number" name="columnas" value="{{ $columnas }}" class="form-control" min="1" max="6">
    </div>

    <div class="col-auto">
        <label>Filas</label>
        <input type="number" name="filas" value="{{ $filas }}" class="form-control" min="1" max="6">
    </div>

    <div class="col-auto">
        <label>Ir al cartón (ID o Nº)</label>
        <input type="text" name="ir_a_carton" value="{{ request('ir_a_carton') }}" class="form-control">
    </div>

    <div class="col-auto align-self-end">
        <button class="btn btn-primary">Actualizar Vista</button>
        <a href="{{ route('admin.jugadas.show', $lote->jugada_id) }}" class="btn btn-secondary">Volver</a>
    </div>
</form>

<div class="d-grid" style="grid-template-columns: repeat({{ $columnas }}, 1fr); gap: 15px;">
@foreach($cartones as $carton)
    <div class="carton" id="carton-{{ $carton->numero }}">
        <div class="carton-titulo">Cartón Nº {{ $carton->numero }}</div>

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
    </div>
@endforeach
</div>

<div class="mt-4">
    {{ $cartones->withQueryString()->links() }}
</div>

<style>
.carton {
    border:1px solid #ccc;
    padding:8px;
    border-radius:6px;
    background:white;
}

.carton-titulo {
    text-align:center;
    font-weight:bold;
    margin-bottom:6px;
}

.carton-tabla {
    width:100%;
    border-collapse:collapse;
    table-layout: fixed;   /* CLAVE: evita columnas colapsadas */
}

.carton-tabla td {
    border:1px solid #000;
    height:36px;
    text-align:center;
    font-weight:bold;
    font-size:14px;
    padding:0;
}

.carton-tabla td.vacio {
    background:#e0e0e0;
}
</style>

@endsection
