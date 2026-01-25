<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <style>
        @page {
            margin: 12mm 10mm 15mm 10mm;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10pt;
        }

        .encabezado {
            width: 100%;
            margin-bottom: 6mm;
        }

        .titulo {
            font-size: 14pt;
            font-weight: bold;
        }

        .subtitulo {
            font-size: 10pt;
        }

        .derecha {
            text-align: right;
            font-size: 9pt;
        }

        .carton {
            display: inline-block;
            margin: 5mm;
            text-align: center;
        }

        .titulo-carton {
            font-weight: bold;
            margin-bottom: 2mm;
            font-size: 9pt;
        }

        table.bingo {
            border-collapse: collapse;
            margin: 0 auto;
        }

        table.bingo td {
            width: 12mm;
            height: 12mm;
            border: 0.4mm solid #000;
            text-align: center;
            vertical-align: middle;
            font-size: 16pt;
            font-weight: bold;
        }

        td.vacio {
            background-color: #e0e0e0;
        }

        td.numero {
            background-color: #ffffff;
            color: #000000;
        }

        .pie {
            position: fixed;
            bottom: 8mm;
            left: 10mm;
            right: 10mm;
            font-size: 9pt;
            border-top: 0.3mm solid #000;
            padding-top: 2mm;
        }

        .pie-izq {
            float: left;
        }

        .pie-der {
            float: right;
        }

        .pagina {
            text-align: center;
            margin-top: 2mm;
        }
    </style>
</head>
<body>

@php $pagina = 1; @endphp

@foreach($grupos as $grupo)

    <!-- ENCABEZADO -->
    <table class="encabezado">
        <tr>
            <td>
                <div class="titulo">{{ $evento['titulo'] }}</div>
                <div class="subtitulo">{{ $evento['organiza'] }} — {{ $evento['fecha'] }}</div>
            </td>
            <td class="derecha">
                Serie: {{ $evento['serie'] }}<br>
                Lote: {{ $evento['lote'] }}
            </td>
        </tr>
    </table>

    <!-- CARTONES -->
    @foreach($grupo as $carton)

        @php
            $grilla = is_string($carton->grilla) ? json_decode($carton->grilla, true) : $carton->grilla;
        @endphp

        <div class="carton">
            <div class="titulo-carton">
                Cartón Nº {{ $carton->numero_carton }}
            </div>

            <table class="bingo">
                @foreach($grilla as $fila)
                    <tr>
                        @foreach($fila as $valor)
                            @if($valor == 0)
                                <td class="vacio"></td>
                            @else
                                <td class="numero">{{ $valor }}</td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </table>
        </div>

    @endforeach

    <!-- PIE DE PÁGINA -->
    <div class="pie">
        <div class="pie-izq">{{ $evento['texto_pie'] }}</div>
        <div class="pie-der">Hoja {{ $pagina }} de {{ $totalHojas }}</div>
    </div>

    @php $pagina++; @endphp

    <div style="page-break-after: always;"></div>

@endforeach

</body>
</html>
