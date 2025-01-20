<div class="row">
    <!-- Name Field -->
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('nombre', 'Nombre:', ['class' => 'bold']) !!}
        {!! Form::text('nombre', $subcategoria->nombre, ['class' => 'form-control round']) !!}
    </div>
    <div class="form-group col-sm-12 col-md-6">
    {!! Form::label('categoria_id', 'Categoría:', ['class' => 'bold']) !!}
    {!! Form::select('categoria_id', $categorias, $subcategoria->categoria_id  , ['class' => 'form-control round', 'placeholder' => 'Selecciona una categoría', 'required']) !!}

    </div>
    <div class="form-group col-sm-12 col-md-6">
        {!! Form::label('status', 'Estatus:', ['class' => 'bold']) !!}
        {!! Form::select('status', [
           '1' => 'Activo',
           '0' => 'Inactivo',
            ], $subcategoria->status, ['class' => 'form-control round']) !!}

    </div>
</div>

<!-- Submit Field -->
<div class="float-end">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary round']) !!}
    <a href="{{ route('subcategorias.index') }}" class="btn btn-danger">Cancelar</a>
</div>