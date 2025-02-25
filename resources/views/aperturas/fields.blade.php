 
    <div class="card shadow-lg p-4">
     

        <form action="{{ route('aperturas.store') }}" method="POST" id="aperturaForm">
            @csrf

            <!-- Selección de Caja -->
            <div class="card p-3 mb-3 bg-primary shadow-sm">
                <h5 class="text-white">
                    <span class="material-icons">point_of_sale</span> Seleccionar Caja
                </h5>
                <select class="form-select mt-2" id="caja_id" name="caja_id" required>
                    <option value="">Seleccione una caja</option>
                    @foreach($cajas as $caja)
                        <option value="{{ $caja->id }}">{{ $caja->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Información del Usuario -->
            <div class="card p-3 mb-3 bg-success text-white shadow-sm">
                <h5 class="text-white">
                    <span class="material-icons">person</span> Usuario Responsable
                </h5>
                <input type="text" class="form-control mt-2" value="{{ auth()->user()->name }}" readonly>
                <input type="hidden" id="usuario_id" name="usuario_id" value="{{ auth()->user()->id }}">
            </div>

            <!-- Montos Iniciales -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card p-3 mb-3 bg-warning shadow-sm">
                        <h5 class="text-black">
                            <span class="material-icons">attach_money</span> Monto Inicial (Bs.)
                        </h5>
                        <input type="number" class="form-control mt-2" id="monto_inicial_bolivares"
                            name="monto_inicial_bolivares" step="0.01" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card p-3 mb-3 bg-info shadow-sm">
                        <h5 class="text-black">
                            <span class="material-icons">monetization_on</span> Monto Inicial ($)
                        </h5>
                        <input type="number" class="form-control mt-2" id="monto_inicial_dolares"
                            name="monto_inicial_dolares" step="0.01" required>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-lg btn-primary">
                    <span class="material-icons">save</span> Guardar
                </button>
                <a href="{{ route('aperturas.index') }}" class="btn btn-lg btn-danger">
                    <span class="material-icons">cancel</span> Cancelar
                </a>
            </div>
        </form>
    </div>
 

<script>
    document.getElementById('aperturaForm').addEventListener('submit', function (e) {
        const montoBolivares = parseFloat(document.getElementById('monto_inicial_bolivares').value);
        const montoDolares = parseFloat(document.getElementById('monto_inicial_dolares').value);

        if (montoBolivares < 0 || montoDolares < 0) {
            e.preventDefault();
            alert('❌ Los montos iniciales no pueden ser menores a 0.');
        }
    });
</script>