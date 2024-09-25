<!DOCTYPE html>
<html>
<head>
    <title>Documento PDF</title>
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
        <h2>Linhas do Documento</h2>
        <table>
            <thead>
            <tr>
                <th>Descrição</th>
                <th>Valor</th>
                <th>Data de Entrega</th>
                <th>Tipo Palete</th>
                <th>Quantidade</th>
                <th>Artigo</th>
            </tr>
            </thead>
            <tbody>
            @foreach($documento->linha_documento as $linha)
                @foreach($linha->tipo_palete as $tipoPalete)
                    <tr>
                        <td>{{ $linha->observacao }}</td>
                        <td>{{ $linha->taxa->valor }}</td>
                        <td>{{ $linha->previsao }}</td>
                        <td>{{ $tipoPalete->tipo }}</td>
                        <td>{{ $tipoPalete->pivot->quantidade }}</td>
                        <td>
                            @php
                                $artigoId = $tipoPalete->pivot->artigo_id;
                            @endphp
                            @if($artigoId && isset($artigos[$artigoId]))
                                {{ $artigos[$artigoId]->nome }}
                            @endif
                        </td>
                    </tr>
                @endforeach
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
