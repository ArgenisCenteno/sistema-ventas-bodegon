<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Monto</th>
            <th>Estatus</th>
            <th>Descuento</th>
            <th>Observaciones</th>
            <th>Usuario</th>
            <th>Pago ID</th>
        </tr>
    </thead>
    <tbody>
        @foreach($recibos as $recibo)
            <tr>
                <td>{{ $recibo->id }}</td>
                <td>{{ $recibo->created_at->format('Y-m-d') }}</td>
                <td>{{ $recibo->tipo }}</td>
                <td>{{ $recibo->monto }}</td>
                <td>{{ $recibo->estatus }}</td>
                <td>{{ $recibo->descuento }}</td>
                <td>{{ $recibo->observaciones }}</td>
                <td>{{ $recibo->user->name ?? 'N/A' }}</td>
                <td>{{ $recibo->pago_id ?? 'N/A' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
