@extends('admin.layout')

@section('contenido')
<h2 class="mb-4">Generar Nuevos Cartones</h2>

<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="{{ route('admin.cartones.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Serie</label>
            <input type="text" name="serie" class="form-control" value="ARG-2026-01" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Cantidad de Cartones a Generar</label>
            <input type="number" name="cantidad" class="form-control" min="1" max="500000" required>
        </div>

        <button class="btn btn-primary">
            Generar Cartones
        </button>
    </form>
</div>
@endsection
