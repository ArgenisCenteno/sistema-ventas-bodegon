<!-- Info boxes -->
<div class="row">
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon text-bg-primary shadow-sm">
        <i class="bi bi-gear-fill"></i>
      </span>

      <div class="info-box-content">
        <span class="info-box-text">Ventas</span>
        <span class="info-box-number">
          {{$ventas}}

        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon text-bg-danger shadow-sm">
        <i class="bi bi-hand-thumbs-up-fill"></i>
      </span>

      <div class="info-box-content">
        <span class="info-box-text">Compras</span>
        <span class="info-box-number">{{$compras}}</span>

      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->

  <!-- fix for small devices only -->
  <!-- <div class="clearfix hidden-md-up"></div> -->

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon text-bg-success shadow-sm">
        <i class="bi bi-cart-fill"></i>
      </span>

      <div class="info-box-content">
        <span class="info-box-text">Pagos</span>
        <span class="info-box-number">{{$pagos}}</span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
      <span class="info-box-icon text-bg-warning shadow-sm">
        <i class="bi bi-people-fill"></i>
      </span>

      <div class="info-box-content">
        <span class="info-box-text">Productos</span>
        <span class="info-box-number"> {{$productos}} </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->

<!--begin::Row-->
<div class="row">
  <div class="col-md-12">
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="card-title">Ventas Mensuales</h5>


      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <!--begin::Row-->
        <div class="row">
          <div class="col-md-7">


            <!-- Contenedor para el gráfico -->
            <div id="combined-chart"></div>
          </div>
          <!-- /.col -->
          <div class="col-md-5">
            <p class="text-center">
              <strong>EL BODEGÓN DE PELUCHE</strong>
            </p>

          <div class="d-flex justify-content-center">
          <img src="{{asset('iconos/logo-empresa.png')}}" alt="imagen" width="300px">
          </div>
            <!-- /.progress-group -->
          </div>
          <!-- /.col -->
        </div>
        <!--end::Row-->
      </div>
      <!-- ./card-body -->
      <div class="card-footer">
        <!--begin::Row-->
        <div class="row">
          <div class="col-md-3 col-6">
            <div class="text-center border-end">
              <span class="text-success">
                 
              </span>
              <h5 class="fw-bold mb-0"> {{$ventasMonto}} </h5>
              <span class="text-uppercase">TOTAL VENTAS</span>
            </div>
          </div>

          <!-- /.col -->
          <div class="col-md-3 col-6">
            <div class="text-center border-end">
              <span class="text-info">
                
              </span>
              <h5 class="fw-bold mb-0"> {{$comprasMonto}} </h5>
              <span class="text-uppercase">TOTAL COMPRAS</span>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-6">
            <div class="text-center border-end">
              <span class="text-success">
                 
              </span>
              <h5 class="fw-bold mb-0"> {{$pagosMonto}} </h5>
              <span class="text-uppercase">TOTAL PAGADO</span>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-6">
            <div class="text-center">
              <span class="text-danger">
                 
              </span>
              <h5 class="fw-bold mb-0"> {{$recibos}} </h5>
              <span class="text-uppercase">RECIBOS GENERADOS</span>
            </div>
          </div>
        </div>
        <!--end::Row-->
      </div>
      <!-- /.card-footer -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
</div> 
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const meses = @json($meses1);  // Los meses obtenidos de PHP
    const comprasData = @json($comprasData);  // Datos de compras
    const ventasData = @json($ventasData);    // Datos de ventas

    // Opciones de la gráfica
    const combined_chart_options = {
      series: [
        {
          name: "Compras",
          data: comprasData,  // Datos de compras
          type: 'line',  // Tipo de gráfico para compras
          color: '#28a745'  // Color para compras (verde)
        },
        {
          name: "Ventas",
          data: ventasData,  // Datos de ventas
          type: 'line',  // Tipo de gráfico para ventas
          color: '#007bff'  // Color para ventas (azul)
        }
      ],
      chart: {
        height: 350,
        type: 'line',
        zoom: {
          enabled: false
        },
        toolbar: {
          show: true,
        }
      },
      dataLabels: {
        enabled: false,
      },
      stroke: {
        width: [3, 3],  // Grosor de las líneas
        curve: 'smooth',  // Curva suave
      },
      fill: {
            
            color: "#0d6efd",  // Color de relleno (puedes usar el mismo color que la línea o diferente)
        },
      xaxis: {
        categories: meses,  // Meses para el eje X
      },
      tooltip: {
        shared: true,
        intersect: false,
        y: {
          formatter: function (val) {
            return "$ " + val.toFixed(2);  // Formato de los valores en el tooltip
          }
        }
      },
      legend: {
        position: 'top',
        horizontalAlign: 'center',
        floating: true,
      }
    };

    // Crear el gráfico con las opciones
    const combined_chart = new ApexCharts(
      document.querySelector("#combined-chart"),
      combined_chart_options
    );
    combined_chart.render();
  });
</script>