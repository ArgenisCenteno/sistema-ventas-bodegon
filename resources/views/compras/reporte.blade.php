@extends('layout.app')

@section('content')
<main class="app-main"> <!--begin::App Content Header-->
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-0 my-5">
                        <div class="px-2 row">
                            <div class="col-lg-12">
                                @include('flash::message')
                            </div>
                            <div class="col-md-6 col-6">
                                <h3 class="p-2 bold">Reporte de Compras</h3>
                            </div>
                            <div class="d-flex justify-content-end mt-3">

                            </div>
                        </div>
                        <div class="card-body">
                        <div id="sales-charts" style="height: 400px;"></div>
                            <form action="{{ route('compras.export') }}" method="GET">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="start_date">Fecha Inicio</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control"
                                            required>
                                    </div>
                                    <div class="col">
                                        <label for="end_date">Fecha Fin</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                                    </div>
                                    <div class="col">
                                        <label for="end_date">Tipo</label>
                                        <select name="type" id="type" class="form-control" readonly>
                                                <option value="EXCEL">EXCEL</option>
                                                <option value="PDF">PDF</option>
                                            </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Exportar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main> <!--end::App Main--> <!--begin::Footer-->
@endsection
@include('layout.script')
<script src="{{ asset('js/adminlte.js') }}"></script>
<script src="{{asset('js/sweetalert2.js')}}"></script>
<link
      rel="stylesheet"
      href="{{asset('css/aperChart.css')}}"
      
    />
 
    <script src="{{asset('js/aperCharts.js')}}"></script>

      <script>
  document.addEventListener("DOMContentLoaded", function () {
    const meses = @json($meses);
    const ventasData = @json($ventasData);

    const sales_chart_options = {
        series: [
            {
                name: "Ventas",
                data: ventasData,
            }
        ],
        chart: {
            height: 180,
            type: "area",
            toolbar: {
                show: false,
            },
        },
        legend: {
            show: false,
        },
        colors: ["#0d6efd"],
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: "smooth",
            width: 2,  // Ajusta el grosor de la línea
        },
        fill: {
            opacity: 0.3,  // Relleno con opacidad (0 es transparente, 1 es completamente opaco)
            color: "#0d6efd",  // Color de relleno (puedes usar el mismo color que la línea o diferente)
        },
        xaxis: {
            categories: meses,
        },
        tooltip: {
            x: {
                format: "MMMM",
            },
        },
    };

    const sales_chart = new ApexCharts(
        document.querySelector("#sales-charts"),
        sales_chart_options
    );
    sales_chart.render();
});

</script>



