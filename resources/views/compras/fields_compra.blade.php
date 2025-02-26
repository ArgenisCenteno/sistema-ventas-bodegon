<form action="{{ route('ventas.generarVenta') }}" id="venta-form" method="POST">
    @csrf <!-- Agrega el token CSRF para seguridad -->
    <section>


    <h4 class="text-center p-2 mt-3 text-white" style="background-color:rgb(0, 92, 179) !important;">MONTOS</h4>

        <div class="row">
            <div class="info-box">
                <span class="info-box-icon text-bg-success shadow-sm">
                    <i class="fa fa-dollar"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">MONTO TOTAL ($)</span>
                    <span class="info-box-number" id="total-dolar">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <div class="info-box">
                <span class="info-box-icon text-bg-warning shadow-sm">
                    BS
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">MONTO TOTAL (BS)</span>
                    <span class="info-box-number totalBS" id="total-bs">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <div class="info-box">
                <span class="info-box-icon text-bg-danger shadow-sm">
                    BS
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Restante (BS)</span>
                    <span class="info-box-number resntante" id="restante">0</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <div class="col-md-12">
            <h4 class="text-center p-2 mt-3 text-white" style="background-color:rgb(0, 92, 179) !important;">CLIENTE</h4>

                <select name="user_id" id="user_id" class="form-control select2 mb-2 mt-2" required>
                    <option value="">Seleccione una opción</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12">
                <h4 class="text-center p-2 mt-3 text-white" style="background-color:rgb(0, 92, 179) !important;">MÉTODOS DE PAGO</h4>
                <div class="row">
                    <div class="col-md-6">
                        <label for="efectivo"><i class="bi bi-cash-coin me-2"></i><strong>Efectivo</strong></label>
                        <input type="number" step="any" value="0" class="form-control mb-3" min="0"
                            placeholder="Efectivo" name="Efectivo" id="efectivo" oninput="validarValor(this)"
                            onblur="establecerCeroSiVacio(this)">
                    </div>
                    <div class="col-md-6">
                        <label for="punto"><i class="bi bi-credit-card me-2"></i><strong>Punto</strong></label>
                        <input type="number" step="any" value="0" class="form-control mb-3" min="0" placeholder="Punto"
                            name="Punto-de-Venta" id="punto" oninput="validarValor(this)"
                            onblur="establecerCeroSiVacio(this)">
                    </div>

                    <div class="col-md-6">
                        <label for="transferencia"><i class="bi bi-bank me-2"></i><strong>Transferencia</strong></label>
                        <input type="number" step="any" value="0" class="form-control mb-3" min="0"
                            placeholder="Transferencia" name="Transferencia" id="transferencia"
                            oninput="validarValor(this)" onblur="establecerCeroSiVacio(this)">
                    </div>
                    <div class="col-md-6">
                        <label for="pago-movil"><i class="bi bi-phone me-2"></i><strong>Pago móvil</strong></label>
                        <input type="number" step="any" value="0" class="form-control mb-3" min="0"
                            placeholder="Pago móvil" name="Pago-Movil" id="pago-movil" oninput="validarValor(this)"
                            onblur="establecerCeroSiVacio(this)">
                    </div>

                    <div class="col-md-6">
                        <label for="biopago"><i class="bi bi-fingerprint me-2"></i><strong>Biopago</strong></label>
                        <input type="number" step="any" value="0" class="form-control mb-3" min="0"
                            placeholder="Biopago" name="Biopago" id="biopago" oninput="validarValor(this)"
                            onblur="establecerCeroSiVacio(this)">
                    </div>
                    <div class="col-md-6">
                        <label for="divisa"><i class="bi bi-currency-exchange me-2"></i><strong>Divisa</strong></label>
                        <input type="number" step="any" value="0" class="form-control mb-3" min="0" placeholder="Divisa"
                            name="Divisa" id="divisa" oninput="validarValor(this)" onblur="establecerCeroSiVacio(this)">
                    </div>
                </div>

                <!-- Campos ocultos -->
                <input type="hidden" readonly step="any" class="form-control" name="dollar" value="{{ $dollar }}"
                    id="dollar-tasa">
                <input type="hidden" readonly step="any" class="form-control" name="pagado" value="0" id="pagado">
                <input type="hidden" readonly step="any" class="form-control" name="totalBolivares" value="0"
                    id="totalBolivares">
                <div id="productos-hidden-fields"></div> <!-- Hidden fields for products -->

            </div>
            <hr />

            <button type="submit" id="submitBtn" class="btn btn-success" style="width: 100%" disabled>Finalizar</button>
        </div>

    </section>

</form>


<script>
    function validarValor(input) {
        if (parseFloat(input.value) < 0) {
            input.value = 0;
        }
    }

    function establecerCeroSiVacio(input) {
        if (input.value.trim() === '') {
            input.value = 0;
        }
    }
</script>