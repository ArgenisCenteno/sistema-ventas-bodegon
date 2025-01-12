<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas Report</title>
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
    <h1>Reporte de Ventas</h1>
    <p>Desde: {{ $ventas->first()->created_at->format('d/m/Y') ?? '-' }} hasta: {{ $ventas->last()->created_at->format('d/m/Y') ?? '-' }}</p>
    <table>
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Fecha</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ventas as $venta)
                <tr>
                    <td>{{ $venta->id }}</td>
                    <td>{{ $venta->user->name ?? 'N/A' }}</td>
                    <td>{{ $venta->vendedor->name ?? 'N/A' }}</td>
                    <td>{{ $venta->created_at->format('d/m/Y') }}</td>
                    <td>{{ number_format($venta->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
