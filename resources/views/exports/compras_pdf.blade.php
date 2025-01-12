<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Compras</title>
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
    <h1>Reporte de Compras</h1>
    <p>Desde: {{ $compras->first()->created_at->format('d/m/Y') ?? '-' }} hasta: {{ $compras->last()->created_at->format('d/m/Y') ?? '-' }}</p>
    <table>
        <thead>
            <tr>
                <th>ID Compra</th>
                <th>Proveedor</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($compras as $compra)
                <tr>
                    <td>{{ $compra->id }}</td>
                    <td>{{ $compra->proveedor->name ?? 'N/A' }}</td>
                    <td>{{ $compra->user->name ?? 'N/A' }}</td>
                    <td>{{ $compra->created_at->format('d/m/Y') }}</td>
                    <td>{{ number_format($compra->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
