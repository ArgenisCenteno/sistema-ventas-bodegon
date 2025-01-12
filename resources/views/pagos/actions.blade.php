<div class="actions">
    <a href="{{ $viewUrl }}" class="btn btn-info btn-sm">
        <span class="material-icons">visibility</span>
    </a>
    @if(Auth::user()->hasRole('superAdmin'))
    <form action="{{ $deleteUrl }}" method="POST" style="display:inline;" class="btn-delete">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
            <span class="material-icons">delete</span>
        </button>
    </form>
    @endif
</div>
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
