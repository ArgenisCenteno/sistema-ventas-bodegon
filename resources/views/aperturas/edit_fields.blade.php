<!-- Mostrar datos de la apertura y caja -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon text-bg-primary shadow-sm">
                <i class="material-icons">calendar_today</i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Apertura</span>
                <span class="info-box-number">{{ $apertura->apertura }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon text-bg-secondary shadow-sm">
                <i class="material-icons">store</i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Caja</span>
                <span class="info-box-number">{{ $caja->nombre }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon text-bg-success shadow-sm">
                BS
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Monto Inicial (Bs)</span>
                <span class="info-box-number">{{ number_format($apertura->monto_inicial_bolivares, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon text-bg-info shadow-sm">
               $
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Monto Inicial ($)</span>
                <span class="info-box-number">{{ number_format($apertura->monto_inicial_dolares, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Totales Generales -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="info-box">
            <span class="info-box-icon text-bg-danger shadow-sm">
                 BS
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Bolívares</span>
                <span class="info-box-number">{{ number_format($montoBs, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="info-box">
            <span class="info-box-icon text-bg-success shadow-sm">
                $
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Dólares</span>
                <span class="info-box-number">{{ number_format($montoDolar, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Totales por Método de Pago -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon text-bg-primary shadow-sm">
                <i class="material-icons">credit_card</i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Transferencia</span>
                <span class="info-box-number">{{ number_format($transaferencia, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon text-bg-warning shadow-sm">
                <i class="material-icons">payment</i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Pago Móvil</span>
                <span class="info-box-number">{{ number_format($pagoMovil, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon text-bg-success shadow-sm">
                <i class="material-icons">money</i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Efectivo</span>
                <span class="info-box-number">{{ number_format($efectivo, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Otros Totales -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon text-bg-info shadow-sm">
                <i class="material-icons">currency_exchange</i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Dólares</span>
                <span class="info-box-number">{{ number_format($divisa, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box">
            <span class="info-box-icon text-bg-success shadow-sm">
                <i class="material-icons">point_of_sale</i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Punto de Venta</span>
                <span class="info-box-number">{{ number_format($punto, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Movimientos -->
<h4>Movimientos</h4>
<table class="table" id="movimientosTable">
    <thead>
        <tr>
            <th>Venta ID</th>
            <th>Total en Bolívares</th>
            <th>Total en Dólares</th>
        </tr>
    </thead>
    <tbody>
        @foreach($movimientos as $movimiento)
        <tr>
            <td>{{ $movimiento['venta_id'] }}</td>
            <td>{{ number_format($movimiento['total_bolivares'], 2) }}</td>
            <td>{{ number_format($movimiento['total_dolares'], 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@if($apertura->estatus !== 'Finalizado')

<!-- Botón para cerrar caja -->
<form class="btn-apertura" action="{{ route('aperturas.update', $apertura->id) }}" method="POST">
    @csrf
    @method('PUT')
    <button type="submit" class="btn btn-success mt-3" style="width: 50%">Cerrar Caja</button>
</form>
@endif

@section('js') 
<script>
    $(document).ready(function() {
        $('#movimientosTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.3/i18n/es-ES.json'
            }
        });
    });
</script>
@endsection
<script>
    $(document).ready(function() {
        $('.btn-apertura').submit(function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Está seguro?',
                text: "Una vez cerrada debe abrir ora para seguir operando.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'rgba(13, 172, 85)',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Aquí se envía el formulario si se confirma la eliminación
                    $(this).off('submit').submit();
                }
            });
        });
    });
</script>
