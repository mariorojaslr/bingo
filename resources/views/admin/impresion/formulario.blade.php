@extends('admin.layout')

@section('titulo')
    Impresión de Cartones
@endsection

@section('contenido')

<h2>Generar Lote de Cartones</h2>

<form method="POST" action="{{ route('admin.impresion.calcular') }}" class="form-impresion">
    @csrf

    <h3>Datos del Evento</h3>

    <div class="form-grupo">
        <label>Título del Evento</label>
        <input type="text" name="titulo" value="Gran Bingo Solidario" required>
    </div>

    <div class="form-grupo">
        <label>Organiza</label>
        <input type="text" name="organiza" value="Bomberos Voluntarios de La Rioja" required>
    </div>

    <div class="form-grupo">
        <label>Fecha y Hora</label>
        <input type="datetime-local" name="fecha" required>
    </div>

    <div class="form-grupo">
        <label>Serie</label>
        <input type="text" name="serie" value="ARG-2026-01" required>
    </div>

    <hr>

    <h3>Rango de Cartones</h3>

    <div class="form-grupo">
        <label>Desde Nº de Cartón</label>
        <input type="number" name="desde" min="1" required>
    </div>

    <div class="form-grupo">
        <label>Hasta Nº de Cartón</label>
        <input type="number" name="hasta" min="1" required>
    </div>

    <div class="form-grupo">
        <label>Cartones por Hoja</label>
        <select name="cartones_por_hoja">
            <option value="3" selected>3 cartones por hoja (A4 vertical)</option>
            <option value="4">4 cartones por hoja</option>
            <option value="6">6 cartones por hoja</option>
        </select>
    </div>

    <hr>

    <h3>Modo de Selección de Cartones</h3>

    <div class="form-grupo">
        <label>
            <input type="radio" name="modo_seleccion" value="aleatorio" checked>
            Aleatorio (mezclados al azar)
        </label>
    </div>

    <div class="form-grupo">
        <label>
            <input type="radio" name="modo_seleccion" value="secuencial">
            Secuencial (en orden numérico)
        </label>
    </div>

    <hr>

    <h3>Datos Comerciales</h3>

    <div class="form-grupo">
        <label>Precio por Hoja</label>
        <input type="number" name="precio_hoja" step="0.01" required>
    </div>

    <div class="form-grupo">
        <label>Texto de Pie / Auspicio</label>
        <input type="text" name="texto_pie" value="Auspicia: Mercadito Don Pepe">
    </div>

    <button type="submit" class="btn-generar">
        Calcular Lote e Importe
    </button>

</form>

@endsection
