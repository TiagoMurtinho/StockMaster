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
        <h1>Documento: {{ $documento->numero }}</h1>
        <p>Cliente: {{$documento->cliente->nome}}</p>
        <p>Data: {{ $documento->data }}</p>
    </div>

    <div class="details">
        <h2>Linhas do Documento</h2>
        <table>
            <thead>
            <tr>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Valor</th>
                <th>Data de Entrega</th>
                <th>Tipo de Palete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($documento->linha_documento as $linha)
                <tr>
                    <td>{{ $linha->descricao }}</td>
                    <td>{{ $linha->quantidade }}</td>
                    <td>{{ $linha->valor }}</td>
                    <td>{{ $linha->data_entrega }}</td>
                    <td>{{ $linha->tipo_palete->tipo }}</td>
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
