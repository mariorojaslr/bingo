@extends('layouts.app')

@section('content')
<div class="container py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ $sala->nombre_jugada }}</h2>
        <a href="{{ route('casino.bingo_virtual.index') }}" class="btn btn-secondary">Volver al Lobby</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-info h-100">
                <div class="card-body">
                    <h5 class="text-info">Detalles de la Sala</h5>
                    <p class="mb-1"><strong>Precio Cartón:</strong> ${{ number_format($sala->precio_hoja, 2) }}</p>
                    <p class="mb-1"><strong>Pozo Acumulado:</strong> <span class="text-success fw-bold">${{ number_format($sala->pozo_acumulado, 2) }}</span></p>
                    <p class="mb-1"><strong>Se gana Pozo si hay Bingo antes de:</strong> Bolilla {{ $sala->limite_bolilla_pozo }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm border-warning h-100">
                <div class="card-body text-center">
                    <h5 class="text-warning">Comprar Cartones</h5>
                    @if($sala->estado == 'creada')
                        <form action="{{ route('casino.bingo_virtual.comprar', $sala->id) }}" method="POST" class="d-flex justify-content-center align-items-center gap-2 mt-3">
                            @csrf
                            <input type="number" name="cantidad" value="1" min="1" max="10" class="form-control w-25 text-center">
                            <button type="submit" class="btn btn-warning fw-bold">Comprar</button>
                        </form>
                    @else
                        <p class="text-danger mt-3 fw-bold">La venta está cerrada. Sala en juego.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <h3 class="mb-3">Tus Cartones</h3>
    <div class="row">
        @forelse($cartonesJugador as $pc)
            <div class="col-md-4 mb-4">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white text-center">
                        Cartón #{{ $pc->carton->numero_carton }}
                    </div>
                    <div class="card-body bg-light p-2">
                        @php
                            // decodificar json si existe
                            $grilla = is_string($pc->carton->grilla) ? json_decode($pc->carton->grilla, true) : $pc->carton->grilla;
                        @endphp
                        @if($grilla && is_array($grilla))
                            <table class="table table-bordered text-center mb-0" style="table-layout: fixed;">
                                @foreach($grilla as $row)
                                    <tr>
                                        @foreach($row as $num)
                                            <td class="p-1 fw-bold fs-5 {{ $num == 0 ? 'bg-secondary text-secondary' : 'text-dark' }}">
                                                {{ $num > 0 ? $num : '' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            <p class="text-center text-muted my-4">Formato no soportado aún.</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    Aún no compraste cartones para esta sala.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
