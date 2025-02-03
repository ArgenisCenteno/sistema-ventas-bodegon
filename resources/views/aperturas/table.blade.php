<table class="table" id="aperturasTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Caja</th>
            <th>Usuario</th>
            <th>Monto Inicial (Bs)</th>
            <th>Monto Inicial ($)</th>
            <th>Estatus</th> <!-- Nueva columna para el estatus -->
            <th>Apertura</th>
            <th>Cierre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($aperturas as $apertura)
            <tr>
                <td>{{ $apertura->id }}</td>
                <td>{{ $apertura->caja->nombre }}</td>
                <td>{{ $apertura->usuario->name }}</td>
                <td>{{ number_format($apertura->monto_inicial_bolivares, 2) }}</td>
                <td>{{ number_format($apertura->monto_inicial_dolares, 2) }}</td>
                <td>
                    @if($apertura->estatus === 'Operando')
                        <span class="badge badge-success">Abierta</span> <!-- Texto en verde para "Abierta" -->
                    @else
                        <span class="badge badge-danger">Cerrado</span> <!-- Texto en rojo para "Cerrado" -->
                    @endif
                </td> <!-- Estatus -->
                <td>
                    {{$apertura->apertura}}
                </td>
                <td>
                    @if($apertura->cierre)
                        {{$apertura->cierre->cierre}}
                    @else
                        <span class="badge badge-success">Abierta</span>
                    @endif
                </td>
                <td>
                    @if($apertura->estatus == 'Finalizado')
                        <a href="{{ route('aperturas.edit', $apertura) }}" class="btn btn-info">
                            <span class="material-icons">visibility</span> 
                        </a>
                    @else
                        <a href="{{ route('aperturas.edit', $apertura) }}" class="btn btn-success">
                            <span class="material-icons">lock</span>
                        </a>
                    @endif
                    @if(Auth::user()->hasRole('superAdmin'))


                    <form action="{{ route('aperturas.destroy', $apertura) }}" method="POST" class="btn-delete" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <span class="material-icons">delete</span>
                        </button>
                    </form>
                    @endif
                </td>


            </tr>
        @endforeach
    </tbody>
</table>
<script src="{{asset('js/sweetalert2.js')}}"></script>
@section('js')
@include('layout.script')
<script src="{{ asset('js/adminlte.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#aperturasTable').DataTable({
            // Configuraciones opcionales
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.3/dataRender/number.js' // Para formatear números en español
            },
            paging: true,
            searching: true,
            ordering: true,
            // Puedes agregar más configuraciones según sea necesario
        });
    });

    
<script>
    $(document).ready(function() {
        $('.btn-delete').submit(function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Está seguro?',
                text: "El registro se eliminará permanentemente.",
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
</script>
@endsection