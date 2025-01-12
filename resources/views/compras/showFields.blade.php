<div class="card">
  <div class="card-body">
    <div class="container mb-5 mt-3">
      <div class="row d-flex align-items-baseline">
        <div class="col-xl-9">
        <p style="color: #7e8d9f;font-size: 20px;">Compra <strong># {{ str_pad($compra->id, 4, '0', STR_PAD_LEFT) }} </strong></p>

        </div>
        <div class="col-xl-3 float-end">
          
        </div>
        <hr>
      </div>

      <div class="container">
        <div class="col-md-12">
          <div class="text-center">
           
            <h3 class="pt-0">El Bodegón de Peluche</h3>
          </div>

        </div>


        <div class="row">
          <div class="col-xl-8">
            <ul class="list-unstyled">
              <li class="text-muted">Proveedor: <span style="color:#5d9fc5 ;"> {{$compra->proveedor->razon_socail}} </span></li>
              <li class="text-muted">Calle Nueva Sector Centor, Punta de Mata</li>
              <li class="text-muted">Monagas, Venezuela</li>
               
            </ul>
          </div>
          <div class="col-xl-4">
            <p class="text-muted">Comprobante de Compra</p>
            <ul class="list-unstyled">
              <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                  class="fw-bold">ID:</span> #{{ str_pad($compra->id, 4, '0', STR_PAD_LEFT) }}</li>
              <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                  class="fw-bold">Creation Date: </span> {{ $compra->created_at }}</li>
              <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                  class="me-1 fw-bold">Estado:</span><span class="badge bg-success text-white fw-bold">
                  {{ $compra->status }}</span></li>
            </ul>
          </div>
        </div>

        <div class="row my-2 mx-1 justify-content-center">
    <table class="table table-striped table-borderless">
        <thead style="background-color:#84B0CA;" class="text-white text-center">
            <tr>
                <th scope="col">#</th>
                <th scope="col">NOMBRE</th>
                <th scope="col">CANTIDAD</th>
                <th scope="col">PRECIO UNIT.</th>
                <th scope="col">IMPUESTOS</th>
                <th scope="col">SUBTOTAL</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($compra->detalleCompras as $index => $producto)
            <tr>
                <th scope="row">{{ $index + 1 }}</th>
                <td>{{ $producto->producto->nombre }}</td>
                <td>{{ $producto->cantidad }}</td>
                <td>{{ $producto->precio_producto }}</td>
                <td>{{ $producto->impuesto }}</td>
                <td>{{ $producto->cantidad * $producto->precio_producto }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

        <div class="row">
          <div class="col-xl-8">
            

          </div>
          <div class="col-xl-3">
            <ul class="list-unstyled">
              <li class="text-muted ms-3"><span class="text-black me-4">SubTotal</span>{{$compra->pago->monto_neto}}</li>
              <li class="text-muted ms-3 mt-2"><span class="text-black me-4">IVA(16%)</span>{{$compra->pago->impuestos}}</li>
            </ul>
            <p class="text-black float-start"><span class="text-black me-3"> Monto Total</span><span
                style="font-size: 25px;">{{$compra->pago->monto_total}}</span></p>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-xl-10">
            <p></p>
          </div>
          <div class="col-xl-2">
            <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary text-capitalize"
              style="background-color:#60bdf3 ;"></button>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>