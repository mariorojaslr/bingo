<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sala->nombre_jugada }} - Bingo Virtual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; color: #f8f9fa; }
        .card { background-color: #1e1e1e; color: #f8f9fa; }
        .bg-light { background-color: #2a2a2a !important; color: #f8f9fa !important; }
        .text-dark { color: #f8f9fa !important; }
    </style>
</head>
<body>
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

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-dark">
                <div class="card-header bg-dark text-white d-flex justify-content-between">
                    <h5 class="mb-0">Bolillas Sorteadas</h5>
                    <span id="estado-sala-badge" class="badge bg-{{ $sala->estado == 'creada' ? 'success' : 'danger' }}">{{ strtoupper($sala->estado) }}</span>
                </div>
                <div class="card-body">
                    <div id="bolillas-container" class="d-flex flex-wrap gap-2">
                        @if($sala->sorteo && $sala->sorteo->getBolillas())
                            @foreach($sala->sorteo->getBolillas() as $b)
                                <div class="bolilla rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-4" style="width:50px; height:50px;">{{ $b }}</div>
                            @endforeach
                        @else
                            <p class="text-muted w-100 text-center mb-0" id="sin-bolillas">El sorteo aún no ha comenzado.</p>
                        @endif
                    </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const salaId = {{ $sala->id }};
        const estadoUrl = "{{ route('casino.bingo_virtual.estado', $sala->id) }}";
        
        function pollEstado() {
            fetch(estadoUrl)
                .then(r => r.json())
                .then(data => {
                    if (data.estado_jugada === 'en_curso' || data.estado_sorteo === 'en_curso') {
                        // Ocultar formulario de compra
                        const formContainer = document.querySelector('.card.border-warning');
                        if (formContainer) {
                            formContainer.innerHTML = '<div class="card-body text-center"><h5 class="text-danger fw-bold">Juego en Curso</h5><p>¡Atento a las bolillas!</p></div>';
                        }
                        
                        // Actualizar bolillas
                        const container = document.getElementById('bolillas-container');
                        if (data.bolillas && data.bolillas.length > 0) {
                            const noBolillasText = document.getElementById('sin-bolillas');
                            if (noBolillasText) noBolillasText.remove();

                            // Reconstruir html de bolillas si la cantidad es diferente
                            const currentCount = container.querySelectorAll('.bolilla').length;
                            if (data.bolillas.length > currentCount) {
                                let html = '';
                                data.bolillas.forEach(b => {
                                    html += `<div class="bolilla rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-4" style="width:50px; height:50px;">${b}</div>`;
                                });
                                container.innerHTML = html;
                            }
                        }
                    }

                    if (data.estado_sorteo === 'finalizado') {
                        document.getElementById('estado-sala-badge').innerText = 'FINALIZADO';
                        document.getElementById('estado-sala-badge').className = 'badge bg-dark';
                        // Detener el polling
                        clearInterval(pollingInterval);
                    }
                })
                .catch(err => console.error(err));
        }

        // Consultar cada 3 segundos
        const pollingInterval = setInterval(pollEstado, 3000);
    });
</script>
</body>
</html>
