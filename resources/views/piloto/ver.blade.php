<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bingo - Acceso Jugador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body text-center">
            <h2 class="mb-3">🎯 Bienvenido al Bingo</h2>
            <h4>{{ $participante->nombre }}</h4>
            <p class="text-muted">{{ $participante->telefono }}</p>

            <hr>

            <p>Tu acceso está correctamente habilitado.</p>
            <p class="fw-bold">Cuando comience la jugada, aquí verás tus cartones.</p>
        </div>
    </div>
</div>

</body>
</html>
