<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cartón {{ $carton->serie }} - Nº {{ $carton->numero_carton }}</title>
    <link rel="stylesheet" href="/css/bingo.css">
</head>
<body>

<div class="carton-container">
    <div class="carton-title">
        Serie {{ $carton->serie }} - Cartón Nº {{ $carton->numero_carton }}
    </div>

    <table class="carton-table">
        @foreach($grilla as $fila)
            <tr>
                @foreach($fila as $valor)
                    @if($valor == 0)
                        <td class="carton-vacio">
                            <img src="/images/simbolos/trebol.png" alt="Símbolo">
                        </td>
                    @else
                        <td>
                            <span class="carton-numero">{{ $valor }}</span>
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </table>
</div>

</body>
</html>
