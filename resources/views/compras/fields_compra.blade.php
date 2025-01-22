<form action="{{ route('compras.generarCompra') }}" id="venta-form" method="POST">
    @csrf <!-- Agrega el token CSRF para seguridad -->
    <section>
       


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
            <h4>PROVEEDOR</h4>
            <select name="user_id" id="user_id" class="form-control select2 mb-2 mt-2" required>
                <option value="">Seleccione una opción</option>
                    @foreach($users as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12">

                <h4>METODOS DE PAGO</h4>
                <label for="" class="label"> <strong>Efectivo</strong> </label>
                <input type="number" step="any" value="0" class="form-control" placeholder="Efectivo" name="Efectivo" oninput="validarValor(this)">
                
                <label for="" class="label"> <strong>Punto</strong> </label>
                <input type="number" step="any" value="0" class="form-control" placeholder="Punto" name="Punto de Venta" oninput="validarValor(this)">
                
                <label for="" class="label"> <strong>Transferencia</strong> </label>
                <input type="number" step="any" value="0" class="form-control" placeholder="Transferencia" name="Transferencia" oninput="validarValor(this)">
                <label for="" class="label"> <strong>Pago móvil</strong> </label>
                <input type="number" step="any" value="0" class="form-control" placeholder="Pago móvil" name="Pago Movil" oninput="validarValor(this)">
                <label for="" class="label"> <strong>Biopago</strong> </label>
                <input type="number" step="any" value="0" class="form-control" placeholder="Biopago" name="Biopago" oninput="validarValor(this)">
                <label for="" class="label"> <strong>Divisa</strong> </label>
                <input type="number" step="any" value="0" class="form-control" placeholder="Divisa" name="Divisa" oninput="validarValor(this)">
                <input type="hidden" readonly step="any" class="form-control" placeholder="Divisa" name="dollar" value="{{ $dollar }}" id="dollar-tasa" oninput="validarValor(this)">
                <input type="hidden" readonly step="any" class="form-control"   name="pagado" value="0" id="pagado">
                <input type="hidden" readonly step="any" class="form-control"   name="pagado" value="0" id="totalBolivares">
                <div id="productos-hidden-fields"></div> <!-- Hidden fields for products -->
            </div>
        </div>
        <hr />

        <button type="submit" id="submitBtn" class="btn btn-success" style="width: 100%" disabled>Finalizar</button>
        </div>

    </section>

</form>

<script>
function validarValor(input) {
    if (input.value === "" || parseFloat(input.value) < 0) {
        input.value = 0;
    }
}
</script>