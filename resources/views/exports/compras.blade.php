<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Proveedor</th>
            <th>Usuario</th>
            <th>Monto Total</th>
            <th>Descuento (%)</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($compras as $compra)
            <tr>
                <td>{{ $compra->id }}</td>
                <td>{{ $compra->created_at->format('Y-m-d') }}</td>
                <td>{{ $compra->proveedor->name ?? 'N/A' }}</td>
                <td>{{ $compra->user->name ?? 'N/A' }}</td>
                <td>{{ $compra->monto_total }}</td>
                <td>{{ $compra->porcentaje_descuento }}</td>
                <td>{{ $compra->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
