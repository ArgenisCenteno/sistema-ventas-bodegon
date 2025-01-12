<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Caja</th>
            <th>Usuario</th>
            <th>Monto Final Bs</th>
            <th>Monto Final $</th>
            <th>Discrepancia Bs</th>
            <th>Discrepancia $</th>
            <th>Fecha de Cierre</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cierres as $cierre)
            <tr>
                <td>{{ $cierre->id }}</td>
                <td>{{ $cierre->caja->nombre ?? 'N/A' }}</td>
                <td>{{ $cierre->usuario->name ?? 'N/A' }}</td>
                <td>{{ number_format($cierre->monto_final_bolivares, 2) }}</td>
                <td>{{ number_format($cierre->monto_final_dolares, 2) }}</td>
                <td>{{ number_format($cierre->discrepancia_bolivares, 2) }}</td>
                <td>{{ number_format($cierre->discrepancia_dolares, 2) }}</td>
                <td>{{ $cierre->cierre }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
