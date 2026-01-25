<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">

<style>
@page { margin: 10mm; }

body {
    font-family: Helvetica, Arial, sans-serif;
    font-size: 10pt;
}

/* ===== CABECERA ===== */

.header {
    width: 100%;
    border-bottom: 1px solid #000;
    padding-bottom: 4mm;
    margin-bottom: 4mm;
}

.header-table {
    width: 100%;
    border-collapse: collapse;
}

.logo-box, .qr-box {
    width: 30mm;
    height: 22mm;
    border: 0.3mm solid #999;
    text-align: center;
    font-size: 8pt;
}

.header-center {
    text-align: center;
}

.titulo {
    font-size: 16pt;
    font-weight: bold;
}

.subtitulo {
    font-size: 9pt;
}

/* ===== CARTONES ===== */

.carton {
    border: 0.3mm solid #000;
    padding: 3mm;
    margin-bottom: 4mm;
    height: 68mm; /* 1 cm más chico */
    position: relative;
}

.numero-carton {
    position: absolute;
    top: 2mm;
    right: 2mm; /* pegado al borde derecho */
    font-size: 16pt;
    font-weight: bold;
}

.id-carton {
    position: absolute;
    bottom: 1.5mm;
    width: 100%;
    text-align: center;
    font-size: 6pt;
}

.tabla-bingo {
    border-collapse: collapse;
    margin: 0 auto;
}

.tabla-bingo td {
    width: 13mm;   /* agrandamos fuerte la grilla */
    height: 13mm;
    border: 0.3mm solid #000;
    text-align: center;
    vertical-align: middle;
    font-size: 16pt;
    font-weight: bold;
}

.vacio { background: #e0e0e0; }

/* ===== PIE ===== */

.footer {
    border-top: 1px solid #000;
    margin-top: 3mm;
    padding-top: 2mm;
    font-size: 8pt;
    text-align: center;
    position: relative;
}

.footer-qr {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 12mm;
    height: 12mm;
    border: 0.3mm solid #999;
    font-size: 6pt;
    text-align: center;
}
</style>
</head>

<body>

<div class="header">
<table class="header-table">
<tr>
    <td class="logo-box">LOGO</td>
    <td class="header-center">
        <div class="titulo">{{ $evento['titulo'] }}</div>
        <div class="subtitulo">
            {{ $evento['organiza'] }} – {{ $evento['fecha'] }}<br>
            Serie: {{ $evento['serie'] }} | Lote: {{ $evento['lote'] }}
        </div>
    </td>
    <td class="qr-box">QR</td>
</tr>
</table>
</div>

@foreach($grupos as $pagina)
@foreach($pagina as $carton)

@php
$grilla = is_string($carton->grilla) ? json_decode($carton->grilla, true) : $carton->grilla;
@endphp

<div class="carton">
    <div class="numero-carton">Nº {{ $carton->numero_carton }}</div>

    <table class="tabla-bingo">
        @foreach($grilla as $fila)
        <tr>
            @foreach($fila as $valor)
                @if($valor==0)
                    <td class="vacio"></td>
                @else
                    <td>{{ $valor }}</td>
                @endif
            @endforeach
        </tr>
        @endforeach
    </table>

    <div class="id-carton">ID: {{ $carton->id }}</div>
</div>

@endforeach

<div class="footer">
    {{ $evento['texto_pie'] }} – Hoja {{ $loop->iteration }} de {{ $totalHojas }}
    <div class="footer-qr">QR</div>
</div>

<div style="page-break-after: always;"></div>
@endforeach

</body>
</html>
