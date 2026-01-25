<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Preview Impresión A4</title>
    <link rel="stylesheet" href="/css/bingo.css">

    <style>
        .hoja-a4 {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 15mm;
            box-sizing: border-box;
        }

        .serie-hoja {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3mm;
        }

        .encabezado {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 5mm;
        }

        .datos-evento {
            display: flex;
            align-items: center;
        }

        .datos-evento img {
            width: 28mm;
            margin-right: 10mm;
        }

        .titulo-evento {
            font-size: 22px;
            font-weight: bold;
        }

        .subtitulo-evento {
            font-size: 13px;
            line-height: 1.3;
        }

        .qr-evento svg {
            width: 25mm;
            height: 25mm;
        }

        .cuerpo-cartones {
            margin-top: 8mm;
            display: flex;
            flex-direction: column;
            gap: 14mm;
        }

        .carton-container {
            position: relative;
        }

        .qr-carton {
            position: absolute;
            right: 4mm;
            top: 4mm;
            width: 12mm;
            height: 12mm;
        }

        .qr-carton svg {
            width: 12mm;
            height: 12mm;
        }

        .pie {
            border-top: 2px solid #000;
            margin-top: 10mm;
            padding-top: 5mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="hoja-a4">

    <div class="serie-hoja">Serie: {{ $evento['serie'] }}</div>

    <div class="encabezado">
        <div class="datos-evento">
            <img src="{{ $evento['logo'] }}">
            <div>
                <div class="titulo-evento">{{ $evento['titulo'] }}</div>
                <div class="subtitulo-evento">
                    Organiza: {{ $evento['organiza'] }}<br>
                    Fecha: {{ $evento['fecha'] }}
                </div>
            </div>
        </div>
        <div class="qr-evento">{!! $qrEvento !!}</div>
    </div>

    <div class="cuerpo-cartones">
        @foreach($cartones as $carton)
            @php $grilla = json_decode($carton->grilla, true); @endphp
            <div class="carton-container">
                <div class="qr-carton">{!! $qrCartones[$carton->id] !!}</div>
                <div class="carton-title">Nº {{ $carton->numero_carton }}</div>

                <table class="carton-table">
                    @foreach($grilla as $fila)
                        <tr>
                            @foreach($fila as $valor)
                                @if($valor == 0)
                                    <td class="carton-vacio">
                                        <img src="/images/simbolos/trebol.png">
                                    </td>
                                @else
                                    <td><span class="carton-numero">{{ $valor }}</span></td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach
    </div>

    <div class="pie">
        <div>{{ $evento['texto_pie'] }}</div>
        <div>{!! $qrSponsor !!}</div>
    </div>

</div>

</body>
</html>
