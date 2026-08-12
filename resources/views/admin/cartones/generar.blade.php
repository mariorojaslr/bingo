@extends('admin.layout')

@section('contenido')
<h2 class="mb-4">Generar Nuevos Cartones</h2>

<div class="card p-4 mb-4" style="max-width:600px;" id="generador-container">
    <div class="mb-3">
        <label class="form-label">Serie</label>
        <input type="text" id="serie" class="form-control" value="LR-2026-08" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Cantidad de Cartones a Generar</label>
        <input type="number" id="cantidad_total" class="form-control" min="1" max="1000000" value="50000" required>
    </div>

    <button id="btn-generar" class="btn btn-primary" onclick="startGeneration()">
        Generar Cartones
    </button>
</div>

<!-- Panel de Monitoreo (Oculto al inicio) -->
<div class="card p-4" style="max-width:600px; display:none;" id="monitor-panel">
    <h4 class="mb-3">Progreso de Generación</h4>
    
    <div class="d-flex justify-content-between mb-1">
        <span id="progreso-texto">0 / 0</span>
        <span id="cronometro" class="fw-bold" style="color: var(--accent);">00:00</span>
    </div>
    
    <div class="progress mb-4" style="height: 25px; background: rgba(255,255,255,0.05);">
        <div id="progreso-barra" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%; background-color: var(--accent);">0%</div>
    </div>
    
    <button id="btn-abortar" class="btn btn-danger w-100" onclick="abortGeneration()">
        <i class="bi bi-stop-circle"></i> Abortar Operación (Pánico)
    </button>
</div>

<script>
    let isGenerating = false;
    let abortRequested = false;
    let totalGenerados = 0;
    let cantidadObjetivo = 0;
    let startTime = null;
    let timerInterval = null;
    const chunkSize = 1000; // Pedimos de a mil cartones al backend

    function formatTime(seconds) {
        const m = Math.floor(seconds / 60).toString().padStart(2, '0');
        const s = (seconds % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    }

    function updateTimer() {
        const now = new Date();
        const diff = Math.floor((now - startTime) / 1000);
        document.getElementById('cronometro').innerText = formatTime(diff);
    }

    async function startGeneration() {
        const serie = document.getElementById('serie').value;
        const inputTotal = parseInt(document.getElementById('cantidad_total').value);

        if (!serie || inputTotal < 1) {
            alert('Por favor completa todos los campos correctamente.');
            return;
        }

        // Bloquear UI
        isGenerating = true;
        abortRequested = false;
        totalGenerados = 0;
        cantidadObjetivo = inputTotal;
        
        document.getElementById('btn-generar').disabled = true;
        document.getElementById('serie').disabled = true;
        document.getElementById('cantidad_total').disabled = true;
        document.getElementById('monitor-panel').style.display = 'block';
        document.getElementById('btn-abortar').style.display = 'block';
        
        // Iniciar cronómetro
        startTime = new Date();
        timerInterval = setInterval(updateTimer, 1000);
        
        updateProgressUI();

        // Bucle de peticiones AJAX
        while (totalGenerados < cantidadObjetivo && !abortRequested) {
            let pendientes = cantidadObjetivo - totalGenerados;
            let cantidadChunk = pendientes > chunkSize ? chunkSize : pendientes;

            try {
                const response = await fetch("{{ route('admin.cartones.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        serie: serie,
                        cantidad: cantidadChunk
                    })
                });

                if (!response.ok) {
                    throw new Error('Error en el servidor');
                }

                const data = await response.json();
                if (data.success) {
                    totalGenerados += data.generados;
                    updateProgressUI();
                } else {
                    throw new Error(data.message);
                }

            } catch (error) {
                alert('Fallo la conexión o el servidor devolvió un error: ' + error.message);
                break;
            }
        }

        finalizar();
    }

    function abortGeneration() {
        if (confirm('¿Estás seguro que deseas abortar la generación? Los cartones ya generados se mantendrán.')) {
            abortRequested = true;
            document.getElementById('btn-abortar').innerHTML = "Abortando... (Espera)";
            document.getElementById('btn-abortar').disabled = true;
        }
    }

    function updateProgressUI() {
        const porcentaje = Math.floor((totalGenerados / cantidadObjetivo) * 100);
        document.getElementById('progreso-texto').innerText = `${totalGenerados.toLocaleString()} / ${cantidadObjetivo.toLocaleString()} cartones`;
        document.getElementById('progreso-barra').style.width = porcentaje + '%';
        document.getElementById('progreso-barra').innerText = porcentaje + '%';
    }

    function finalizar() {
        isGenerating = false;
        clearInterval(timerInterval);
        
        document.getElementById('btn-abortar').style.display = 'none';
        document.getElementById('btn-generar').disabled = false;
        document.getElementById('btn-generar').innerText = "Generar Nuevos Lotes";
        document.getElementById('serie').disabled = false;
        document.getElementById('cantidad_total').disabled = false;

        if (totalGenerados === cantidadObjetivo) {
            document.getElementById('progreso-barra').classList.remove('progress-bar-animated');
            document.getElementById('progreso-barra').classList.add('bg-success');
            document.getElementById('progreso-barra').innerText = "¡Completado 100%!";
            alert(`¡Éxito nivel Dios! Se completó la generación de ${totalGenerados} cartones.`);
        } else if (abortRequested) {
            document.getElementById('progreso-barra').classList.remove('progress-bar-animated');
            document.getElementById('progreso-barra').classList.add('bg-warning');
            alert(`Operación Abortada. Se generaron ${totalGenerados} cartones antes de detenerse.`);
            document.getElementById('btn-abortar').innerHTML = '<i class="bi bi-stop-circle"></i> Abortar Operación (Pánico)';
            document.getElementById('btn-abortar').disabled = false;
        }
    }
</script>
@endsection
