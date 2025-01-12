<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Recibos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Reporte de Recibos</h1>
    <p>Desde: {{ $recibos->first()->created_at->format('d/m/Y') ?? '-' }} hasta: {{ $recibos->last()->created_at->format('d/m/Y') ?? '-' }}</p>
    <table>
        <thead>
            <tr>
                <th>ID Recibo</th>
                <th>Pago</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recibos as $recibo)
                <tr>
                    <td>{{ $recibo->id }}</td>
                    <td>{{ $recibo->pago->tipo ?? 'N/A' }}</td>
                    <td>{{ $recibo->user->name ?? 'N/A' }}</td>
                    <td>{{ $recibo->created_at->format('d/m/Y') }}</td>
                    <td>{{ number_format($recibo->monto, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
