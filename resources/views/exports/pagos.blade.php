<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Banco Origen</th>
            <th>Banco Destino</th>
            <th>Monto Total</th>
            <th>Monto Neto</th>
            <th>Descuento</th>
            <th>Impuestos</th>
            <th>Usuario Creador</th>
            <th>Ventas Asociadas</th>
            <th>Compras Asociadas</th>
            <th>Recibos Asociados</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pagos as $pago)
            <tr>
                <td>{{ $pago->id }}</td>
                <td>{{ $pago->fecha_pago }}</td>
                <td>{{ $pago->tipo }}</td>
                <td>{{ $pago->banco_origen }}</td>
                <td>{{ $pago->banco_destino }}</td>
                <td>{{ $pago->monto_total }}</td>
                <td>{{ $pago->monto_neto }}</td>
                <td>{{ $pago->descuento }}</td>
                <td>{{ $pago->impuestos }}</td>
                <td>{{ $pago->user->name ?? 'N/A' }}</td>
                <td>
                    @foreach($pago->ventas as $venta)
                        Venta ID: {{ $venta->id }}, Monto: {{ $venta->monto_total }}<br>
                    @endforeach
                </td>
                <td>
                    @foreach($pago->compras as $compra)
                        Compra ID: {{ $compra->id }}, Monto: {{ $compra->monto_total }}<br>
                    @endforeach
                </td>
                <td>
                    @foreach($pago->recibos as $recibo)
                        Recibo ID: {{ $recibo->id }}, Monto: {{ $recibo->monto }}<br>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
