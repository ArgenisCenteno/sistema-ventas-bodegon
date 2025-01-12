<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pagos</title>
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
    <h1>Reporte de Pagos</h1>
    <p>Desde: {{ $pagos->first()->created_at->format('d/m/Y') ?? '-' }} hasta: {{ $pagos->last()->created_at->format('d/m/Y') ?? '-' }}</p>
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Status</th>
               
                <th>Fecha de Pago</th>
                <th>Monto Total</th>
                <th>Monto Neto</th>
                <th>Descuento</th>
              
                
                <th>Creado por</th>
                
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pagos as $pago)
                <tr>
                    <td>{{ $pago->tipo }}</td>
                    <td>{{ $pago->status }}</td>
                   
                    <td>{{ $pago->fecha_pago}}</td>
                    <td>{{ number_format($pago->monto_total, 2) }}</td>
                    <td>{{ number_format($pago->monto_neto, 2) }}</td>
                    <td>{{ number_format($pago->descuento, 2) }}</td>
                 
                    <td>{{ $pago->user->name ?? 'N/A' }}</td>
                     <td>{{ $pago->user->name ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
