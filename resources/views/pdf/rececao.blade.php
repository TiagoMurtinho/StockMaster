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
        <h1>Nota de Recepção</h1>
        <p><strong>Número do Documento:</strong> {{ $documento->numero }}</p>
        <p><strong>Cliente:</strong> {{ $cliente->nome }}</p>
        <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($documento->data)->format('d/m/Y') }}</p>
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
            @foreach($documento->linha_documento as $linha)
                @foreach($linha->palete as $palete)
                    <tr>
                        <td>{{ $palete->tipo_palete->tipo ?? 'N/A' }}</td>
                        <td>{{ $palete->localizacao }}</td>
                        <td>{{ $palete->artigo->nome ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($palete->data_entrada)->format('d/m/Y H:i') ?? 'N/A' }}</td>
                        <td>{{ $palete->armazem->nome ?? 'N/A' }}</td>
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
