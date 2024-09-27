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
        <p>Número do Documento: {{$documento->numero}}</p>
        <p>Cliente: {{$documento->cliente->nome}}</p>
        <p>Data: {{ $documento->data }}</p>
    </div>

    <div class="details">
        <h2>Detalhes das Paletes</h2>
        <table>
            <thead>
            <tr>
                <th>Tipo de Palete</th>
                <th>Artigo</th>
                <th>Data de Entrada</th>
                <th>Data de Saída</th>
            </tr>
            </thead>
            <tbody>
            @foreach($paletes as $palete)

                <tr>
                    <td>{{ $palete->tipo_palete->tipo }}</td>
                    <td>
                        @php
                            $artigoId = $palete->artigo_id;
                        @endphp
                        @if($artigoId && isset($artigos[$artigoId]))
                            {{ $artigos[$artigoId]->nome }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $palete->data_entrada }}</td>
                    <td>{{ $palete->data_saida }}</td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>

    <div class="additional-info">
        <h2>Contas</h2>
        <p><strong>Valor:</strong> {{ $documento->total }}</p>
        <p><strong>Extra:</strong> {{ $documento->extra }}</p>
        <p><strong>Total:</strong> {{ $documento->total + $documento->extra }}</p>
    </div>
    <div class="footer">
        <p>Obrigado por utilizar o nosso sistema!</p>
    </div>
</div>
</body>
</html>
