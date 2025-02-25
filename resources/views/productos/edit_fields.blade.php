<div class="row">
    <!-- Nombre Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('nombre', 'Nombre:', ['class' => 'bold']) !!}
        {!! Form::text('nombre', $producto->nombre, ['class' => 'form-control round', 'required']) !!}
    </div>

    <!-- Descripción Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('descripcion', 'Descripción:', ['class' => 'bold']) !!}
        {!! Form::textarea('descripcion', $producto->descripcion, ['class' => 'form-control round', 'rows' => 3, 'required']) !!}
    </div>

    <!-- Precio Compra Field -->
   
    <!-- Precio Venta Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('precio_venta', 'Precio Venta:', ['class' => 'bold']) !!}
        {!! Form::number('precio_venta', $producto->precio_venta, ['class' => 'form-control round', 'step' => '0.01', 'id' => 'precio_venta', 'required']) !!}
        <p id="precio_venta_error" style="color: red; display: none;">El precio de venta no puede ser negativo.</p>
    </div>
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('precio_compra', 'Precio Compra:', ['class' => 'bold']) !!}
        {!! Form::number('precio_compra', $producto->precio_compra, ['class' => 'form-control round', 'step' => '0.01', 'id' => 'precio_compra', 'required']) !!}
        @error('precio_compra')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('ganancia', 'Ganancia:', ['class' => 'bold']) !!}
        {!! Form::text('ganancia', $producto->precio_venta - $producto->precio_compra, ['class' => 'form-control round', 'id' => 'ganancia', 'readonly']) !!}
    </div>

    <!-- Precio Final con IVA Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('precio_final', 'Precio Final con IVA:', ['class' => 'bold']) !!}
        {!! Form::text('precio_final', $producto->aplica_iva === 1 ? $producto->precio_venta * 1.16 : $producto->precio_venta, ['class' => 'form-control round', 'id' => 'precio_final', 'readonly']) !!}
    </div>
    <!-- Aplica IVA Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('aplica_iva', 'Aplica IVA:', ['class' => 'bold']) !!}
        {!! Form::select('aplica_iva', ['1' => 'Sí', '0' => 'No'], $producto->aplica_iva, ['class' => 'form-control round', 'required']) !!}
    </div>
 
 

    <!-- Cantidad Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('cantidad', 'Cantidad:', ['class' => 'bold']) !!}
        {!! Form::number('cantidad', $producto->cantidad, ['class' => 'form-control round', 'step' => 'any', 'required',  'id' => 'cantidad']) !!}
    </div>

    <!-- Subcategoría Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('sub_categoria_id', 'Subcategoría:', ['class' => 'bold']) !!}
        {!! Form::select('sub_categoria_id', $subcategorias, $producto->sub_categoria_id, ['class' => 'form-control round', 'placeholder' => 'Selecciona una subcategoría', 'required']) !!}
    </div>

    <!-- Disponible Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('disponible', 'Disponible:', ['class' => 'bold']) !!}
        {!! Form::select('disponible', ['1' => 'Disponible', '0' => 'No Disponible'], $producto->disponible, ['class' => 'form-control round', 'required']) !!}
    </div>

   

    <!-- Contenedor para previsualizar las imágenes -->
 </div>

<!-- Botones de acción -->
<div class="float-end">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary round', 'id' => 'submit_btn' ]) !!}
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