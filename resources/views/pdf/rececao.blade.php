<!DOCTYPE html>
<html>
<head>
    <title>Nota de Recepção</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: auto;
        }
        .header, .footer {
            text-align: center;
        }
        .details {
            margin-top: 20px;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details table, .details th, .details td {
            border: 1px solid black;
        }
        .details th, .details td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Documento: {{ $documento->tipo_documento->nome }}</h1>
        <p>Numero Documento: {{$documento->numero}}</p>
        <p>Cliente: {{$documento->cliente->nome}}</p>
        <p>Data: {{ $documento->data }}</p>
    </div>

    <div class="details">
        <h2>Detalhes das Paletes</h2>
        <table>
            <thead>
            <tr>
                <th>Tipo de Palete</th>
                <th>Localização</th>
                <th>Artigo</th>
                <th>Data de Entrada</th>
                <th>Armazém</th>
            </tr>
            </thead>
            <tbody>
                @foreach($documento->tipo_palete as $tipoPalete)
                    <tr>
                        <td>{{ $tipoPalete->tipo }}</td>
                        <td>{{ $tipoPalete->pivot->localizacao }}</td>
                        <td>
                            @php
                                $artigoId = $tipoPalete->pivot->artigo_id;
                            @endphp
                            @if($artigoId && isset($artigos[$artigoId]))
                                {{ $artigos[$artigoId]->nome }}
                            @endif
                        </td>
                        <td>{{ $documento->data_entrada}}</td>
                        <td>
                            @php
                                $armazemId = $tipoPalete->pivot->armazem_id;
                            @endphp
                            @if($armazemId && isset($armazens[$armazemId]))
                                {{ $armazens[$armazemId]->nome }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Obrigado por utilizar o nosso sistema!</p>
    </div>
</div>
</body>
</html>
