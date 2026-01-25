@extends('admin.layout')

@section('content')
<h2>Visor Interno de Cartones</h2>

<form method="GET" class="mb-3">
    Mostrar
    <select name="por_pagina" onchange="this.form.submit()">
        @foreach([1,2,4,6] as $n)
            <option value="{{ $n }}" {{ $porPagina == $n ? 'selected' : '' }}>{{ $n }}</option>
        @endforeach
    </select>
    cartones por pantalla

    &nbsp;&nbsp; Ir al cartón Nº
    <input type="number" name="numero" value="{{ request('numero') }}">
    <button type="submit">Ver</button>
</form>

<div class="grid-cartones" style="display:grid;grid-template-columns:repeat({{ $porPagina > 2 ? 2 : 1 }},1fr);gap:20px;">

@foreach($cartones as $carton)
    @php
        $grilla = is_string($carton->grilla) ? json_decode($carton->grilla, true) : $carton->grilla;
    @endphp

    <div class="carton-web">
        <strong>Cartón Nº {{ $carton->numero_carton }}</strong>

        <table class="bingo-web" style="border-collapse:collapse;margin-top:5px;">
            @foreach($grilla as $fila)
                <tr>
                    @foreach($fila as $valor)
                        @if($valor == 0)
                            <td style="width:32px;height:32px;border:1px solid #000;background:#e6e6e6;"></td>
                        @else
                            <td style="width:32px;height:32px;border:1px solid #000;
                                       font-size:22px;font-weight:bold;
                                       text-align:center;vertical-align:middle;">
                                {{ $valor }}
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
    </div>

@endforeach

</div>

<div class="mt-3">
    {{ $cartones->links() }}
</div>
@endsection
