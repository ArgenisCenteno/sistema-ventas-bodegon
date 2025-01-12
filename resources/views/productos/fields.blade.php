<div class="row">
    <!-- Nombre Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('nombre', 'Nombre:', ['class' => 'bold']) !!}
        {!! Form::text('nombre', null, ['class' => 'form-control round', 'required']) !!}
        @error('nombre')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    <!-- Descripción Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('descripcion', 'Descripción:', ['class' => 'bold']) !!}
        {!! Form::textarea('descripcion', null, ['class' => 'form-control round', 'rows' => 3, 'required']) !!}
        @error('descripcion')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    <!-- Precio Venta Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('precio_venta', 'Precio Venta:', ['class' => 'bold']) !!}
        {!! Form::number('precio_venta', null, ['class' => 'form-control round', 'step' => '0.01', 'id' => 'precio_venta', 'required']) !!}
        @error('precio_venta')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    <!-- Precio Compra Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('precio_compra', 'Precio Compra:', ['class' => 'bold']) !!}
        {!! Form::number('precio_compra', null, ['class' => 'form-control round', 'step' => '0.01', 'id' => 'precio_compra', 'required']) !!}
        @error('precio_compra')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    <!-- Ganancia Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('ganancia', 'Ganancia:', ['class' => 'bold']) !!}
        {!! Form::text('ganancia', null, ['class' => 'form-control round', 'id' => 'ganancia', 'readonly']) !!}
    </div>

    <!-- Precio Final con IVA Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('precio_final', 'Precio Final con IVA:', ['class' => 'bold']) !!}
        {!! Form::text('precio_final', null, ['class' => 'form-control round', 'id' => 'precio_final', 'readonly']) !!}
    </div>

    <!-- Aplica IVA Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('aplica_iva', 'Aplica IVA:', ['class' => 'bold']) !!}
        {!! Form::select('aplica_iva', ['1' => 'Sí', '0' => 'No'], null, ['class' => 'form-control round', 'required']) !!}
        @error('aplica_iva')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    <!-- Cantidad Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('cantidad', 'Cantidad:', ['class' => 'bold']) !!}
        {!! Form::number('cantidad', null, ['class' => 'form-control round', 'step' => '1', 'required', 'id' => 'cantidad']) !!}
        @error('cantidad')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    <!-- Subcategoría Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('sub_categoria_id', 'Subcategoría:', ['class' => 'bold']) !!}
        {!! Form::select('sub_categoria_id', $subcategorias, null, ['class' => 'form-control round', 'placeholder' => 'Selecciona una subcategoría', 'required']) !!}
        @error('sub_categoria_id')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    <!-- Disponible Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('disponible', 'Disponible:', ['class' => 'bold']) !!}
        {!! Form::select('disponible', ['1' => 'Disponible', '0' => 'No Disponible'], null, ['class' => 'form-control round', 'required']) !!}
        @error('disponible')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Botones de acción -->
<div class="float-end">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary round', 'id' => 'submit_btn']) !!}
    <a href="{{ route('almacen') }}" class="btn btn-danger round">Cancelar</a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const precioVentaInput = document.getElementById('precio_venta');
        const precioCompraInput = document.getElementById('precio_compra');
        const aplicaIVASelect = document.getElementById('aplica_iva');
        const gananciaInput = document.getElementById('ganancia');
        const precioFinalInput = document.getElementById('precio_final');
        const cantidad = document.getElementById('cantidad');
        const submitBtn = document.getElementById('submit_btn');

        function calcularResultados() {
            const precioVenta = parseFloat(precioVentaInput.value) || 0;
            const precioCompra = parseFloat(precioCompraInput.value) || 0;
            const aplicaIVA = aplicaIVASelect.value === '1';

            if (precioCompra > precioVenta) {
                precioCompraInput.classList.add('is-invalid');
                submitBtn.setAttribute('disabled', 'true');
                gananciaInput.value = '';
                precioFinalInput.value = '';
            } else {
                precioCompraInput.classList.remove('is-invalid');
                submitBtn.removeAttribute('disabled');

                const ganancia = precioVenta - precioCompra;
                const precioFinal = aplicaIVA ? precioVenta * 1.16 : precioVenta;

                gananciaInput.value = ganancia.toFixed(2);
                precioFinalInput.value = precioFinal.toFixed(2);
            }
        }

        function validarCantidad() {
            const cantidadValue = parseInt(cantidad.value, 10) || 0;
            if (cantidadValue <= 0) {
                cantidad.classList.add('is-invalid');
                submitBtn.setAttribute('disabled', 'true');
            } else {
                cantidad.classList.remove('is-invalid');
                submitBtn.removeAttribute('disabled');
            }
        }


        precioVentaInput.addEventListener('input', calcularResultados);
        precioCompraInput.addEventListener('input', calcularResultados);
        aplicaIVASelect.addEventListener('change', calcularResultados);
        cantidad.addEventListener('input', validarCantidad);
    });
</script>