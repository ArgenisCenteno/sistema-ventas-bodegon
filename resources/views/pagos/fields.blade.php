<div class="card border-0 shadow-sm p-4">
    

    <!-- Información general -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="fw-bold">Tipo:</label>
            <p class="form-control-plaintext border rounded bg-light px-2">{{ $pago->tipo }}</p>
        </div>
        <div class="col-md-6">
            <label class="fw-bold">Fecha de Pago:</label>
            <p class="form-control-plaintext border rounded bg-light px-2">{{ $pago->fecha_pago }}</p>
        </div>
    </div>

    <!-- Montos -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="fw-bold">Monto Total:</label>
            <p class="form-control-plaintext border rounded bg-light px-2">{{ number_format($pago->monto_total, 2, ',', '.') }} </p>
        </div>
        <div class="col-md-6">
            <label class="fw-bold">Monto Neto:</label>
            <p class="form-control-plaintext border rounded bg-light px-2">{{ number_format($pago->monto_neto, 2, ',', '.') }} </p>
        </div>
    </div>

    <!-- Detalles adicionales -->
    <div class="row mb-3">
    <div class="col-md-6">
            <label class="fw-bold">Impuestos:</label>
            <p class="form-control-plaintext border rounded bg-light px-2">{{ number_format($pago->impuestos, 2, ',', '.') }} </p>
        </div>
        <div class="col-md-6">
            <label class="fw-bold">Descuento:</label>
            <p class="form-control-plaintext border rounded bg-light px-2">{{ number_format($pago->descuento, 2, ',', '.') }} </p>
        </div>
       
    </div>

    <!-- Footer -->
    
</div>
