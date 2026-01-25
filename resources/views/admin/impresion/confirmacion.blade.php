@extends('admin.layout')

@section('titulo')
    Confirmación de Lote
@endsection

@section('contenido')

<h2>Resumen de impresión</h2>

<p><strong>Serie:</strong> {{ $datos['serie'] }}</p>
<p><strong>Evento:</strong> {{ $datos['titulo'] }}</p>
<p><strong>Rango:</strong> {{ $datos['desde'] }} al {{ $datos['hasta'] }}</p>
<p><strong>Total cartones:</strong> {{ $cantidadCartones }}</p>
<p><strong>Cartones por hoja:</strong> {{ $datos['cartones_por_hoja'] }}</p>
<p><strong>Total de hojas:</strong> {{ $cantidadHojas }}</p>
<p><strong>Precio por hoja:</strong> ${{ $datos['precio_hoja'] }}</p>

<h3>Total a cobrar: ${{ $total }}</h3>

<form method="POST" action="{{ route('admin.impresion.generar') }}">
    @csrf

    @foreach($datos as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

    <button class="btn-generar">
        Confirmar y generar PDF
    </button>
</form>

@endsection
