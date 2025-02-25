<html>
@include('layout.head')

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="bg-dark text-white p-2 d-flex justify-content-between align-items-center">
    <div class="d-flex">
        <div class="d-flex align-items-center me-3">
            <img src="{{ asset('iconos/ahorrar-dinero.png') }}" alt="Dólar" style="width: 20px; height: 20px;" class="me-2">
            <strong> Dólar:</strong>
            <span id="dollar"></span>
        </div>
        <div class="d-flex align-items-center">
            <img src="{{ asset('iconos/calido.png') }}" alt="Clima" style="width: 20px; height: 20px;" class="me-2">
            <strong> Hora:</strong>
            <span id="time"></span>
        </div>
    </div>

    <div class="status-box text-center px-3 py-1 rounded">
        @if($aperturasss)
            <span class="bg-success text-white p-2 rounded">🟢 CAJA ABIERTA</span>
        @else
            <span class="bg-danger text-white p-2 rounded">🔴 CAJA CERRADA</span>
        @endif
    </div>
</div>


    <div class="app-wrapper"> <!--begin::Header-->

        @include('layout.cabecera')
        @yield('content')
        @stack('third_party_scripts')
        @stack('page_scripts')

    </div>
    @include('components.footer')
    @yield('js')
    @include('layout.script')
    @include('sweetalert::alert')
    @include('layout.datatables_css')
    @include('layout.datatables_js')

</body>
<script>
    // Obtener la hora actual
    function actualizarHora() {
        const timeElement = document.getElementById('time');
        const now = new Date();
        timeElement.textContent = now.toLocaleTimeString();
    }

    // Pasar el valor del dólar desde PHP a JavaScript
    const dolar = @json($dolar);

    setInterval(actualizarHora, 1000); // Actualizar cada segundo

    // Mostrar el valor del dólar
    document.getElementById('dollar').textContent = `BS ${dolar}`;
</script>

</html>