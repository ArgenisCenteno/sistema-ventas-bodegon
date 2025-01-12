<form action="{{ route('aperturas.store') }}" method="POST" id="aperturaForm">
    @csrf
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="caja_id" class="form-label">Caja</label>
            <select class="form-select" id="caja_id" name="caja_id" required>
                <option value="">Seleccione una caja</option>
                @foreach($cajas as $caja)
                    <option value="{{ $caja->id }}">{{ $caja->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="usuario_id" class="form-label">Usuario</label>
            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
            <input type="hidden" id="usuario_id" name="usuario_id" value="{{ auth()->user()->id }}">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="monto_inicial_bolivares" class="form-label">Monto Inicial en Bolívares</label>
            <input type="number" class="form-control" id="monto_inicial_bolivares" name="monto_inicial_bolivares"
                step="0.01" required>
        </div>
        <div class="col-md-6">
            <label for="monto_inicial_dolares" class="form-label">Monto Inicial en Dólares</label>
            <input type="number" class="form-control" id="monto_inicial_dolares" name="monto_inicial_dolares" step="0.01"
                required>
        </div>
    </div>
    <div class="text-start">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('aperturas.index') }}" class="btn btn-danger">Cancelar</a>

    </div>
</form>

<script>
    document.getElementById('aperturaForm').addEventListener('submit', function (e) {
        const montoBolivares = parseFloat(document.getElementById('monto_inicial_bolivares').value);
        const montoDolares = parseFloat(document.getElementById('monto_inicial_dolares').value);

        if (montoBolivares < 0 || montoDolares < 0) {
            e.preventDefault();
            alert('Los montos iniciales no pueden ser menores a 0.');
        }
    });
</script>
