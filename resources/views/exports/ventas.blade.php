<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Vendedor</th>
            <th>Monto Total</th>
            <th>Descuento (%)</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ventas as $venta)
            <tr>
                <td>{{ $venta->id }}</td>
                <td>{{ $venta->created_at->format('Y-m-d') }}</td>
                <td>{{ $venta->user->name ?? 'N/A' }}</td>
                <td>{{ $venta->vendedor->name ?? 'N/A' }}</td>
                <td>{{ $venta->monto_total }}</td>
                <td>{{ $venta->porcentaje_descuento }}</td>
                <td>{{ $venta->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
