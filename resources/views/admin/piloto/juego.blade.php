<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bingo Piloto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body { background:#0b1220; color:white; font-family:Arial; margin:0; }
        .header { background:#111827; padding:15px; text-align:center; font-size:22px; }
        .jugador { text-align:center; margin:15px; }
        .cartones { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:15px; padding:15px; }
        .carton { background:#1f2937; border-radius:8px; padding:10px; }
        .carton h4 { text-align:center; margin-bottom:8px; }
        .numero { width:32px; height:32px; border-radius:50%; background:#374151; display:inline-flex; align-items:center; justify-content:center; margin:2px; }
    </style>
</head>
<body>

<div class="header">
    🎯 {{ $jugada->nombre_jugada ?? 'Prueba Interna' }}
</div>

<div class="jugador">
    <h2>{{ $participante->nombre }} {{ $participante->apellido }}</h2>
    <p>Tel: {{ $participante->telefono }}</p>
</div>

<div class="cartones">
    @foreach($cartones as $pc)
        <div class="carton">
            <h4>Cartón Nº {{ $pc->carton->numero_carton }}</h4>
            <div>
                @foreach($pc->carton->numeros as $num)
                    <span class="numero">{{ $num }}</span>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

</body>
</html>
