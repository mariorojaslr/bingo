@extends('dashboard')

@section('content')
<style>
    .bingo-grid {
        display: grid;
        grid-template-columns: repeat(9, 1fr);
        gap: 6px;
        margin-top: 10px;
        justify-items: center;
    }

    .bingo-cell {
        width: 46px;
        height: 46px;
        border-radius: 6px;
        background: #e5e7eb; /* gris claro */
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 22px;
        color: #000;
    }

    .bingo-number {
        background: #f9fafb;
        font-size: 24px;
        font-weight: 800;
    }

    .bingo-empty {
        font-size: 20px;
        opacity: 0.9;
    }
</style>

<div class="max-w-3xl mx-auto mt-12">
    <div class="bg-white shadow rounded-lg p-6 text-center">
        <h1 class="text-3xl font-bold mb-2">🎯 Bienvenido al Bingo</h1>

        <h2 class="text-xl font-semibold">{{ $participante->nombre }}</h2>

        @if($participante->telefono)
            <p class="text-gray-600">{{ $participante->telefono }}</p>
        @endif

        <hr class="my-4">

        <p class="text-green-700 font-medium">
            Tu acceso está correctamente habilitado.
        </p>

        <p class="mt-2">
            Cuando comience la jugada, aquí verás tus cartones.
        </p>

        @if(isset($cartones) && count($cartones) > 0)
            <div class="mt-6">
                <h2 class="text-lg font-bold mb-4 text-center">Tus cartones para esta jugada</h2>

                @foreach($cartones as $pcp)
                    <div class="mb-6 p-4 border rounded bg-white shadow">
                        <div class="text-sm text-gray-500 mb-2">
                            Cartón Nº {{ $pcp->carton->numero_carton ?? '---' }}
                        </div>

                        <div class="bingo-grid">
                            @foreach($pcp->carton->grilla as $fila)
                                @foreach($fila as $celda)
                                    @if($celda == 0)
                                        <div class="bingo-cell bingo-empty">♣</div>
                                    @else
                                        <div class="bingo-cell bingo-number">{{ $celda }}</div>
                                    @endif
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-4">
            <span class="text-sm text-gray-500">Código de acceso:</span>
            <strong class="text-lg">{{ $participante->codigo_acceso }}</strong>
        </div>
    </div>
</div>
@endsection
