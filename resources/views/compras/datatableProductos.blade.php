<div class="table-responsive">
    <table class="table table-hover" id="productos-table2">
        <thead class="bg-light">
            <tr>

                <th>Nombre</th>
                <th>Precio</th>
                <th> IVA</th>
                <th>Stock</th>
                <th>Subcategoría</th>

                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<h4 class="mt-4 mb-4 p-3">CARRITO</h4>
<div class="table-responsive">
    <table class="table table-hover" id="productos-ventas">
        <thead class="bg-light">
            <tr>

                <th>Nombre</th>
                <th>Precio</th>
                <th> IVA</th>
                <th>Subcategoría</th>
                <th>Cantidad</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@section('js')
@include('layout.script')
<script src="{{ asset('js/adminlte.js') }}"></script>
<script src="{{asset('js/sweetalert2.js')}}"></script>
<script type="text/javascript">

    $(document).ready(function () {
        let productosEnCarrito = [];


        $('#productos-table2').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('ventas.datatableProductoVenta') }}",
            dataType: 'json',
            type: "POST",
            columns: [
                { data: 'nombre', name: 'nombre' },
                { data: 'precio_compra', name: 'precio_compra' },
                {
                    data: 'aplica_iva', name: 'aplica_iva', render: function (data) {
                        return data ? 'Sí' : 'No';
                    }
                },
                { data: 'cantidad', name: 'cantidad' },
                { data: 'subCategoria', name: 'subCategoria' },
                {
                    data: 'id',
                    name: 'actions',
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {

                        return `<button type="button" class="btn btn-primary" onClick="modificarTabla('${data}')"><span class="material-icons">shopping_cart</span></button>`;
                    }
                }
            ],
            order: [[0, 'desc']],
            "language": {
                "lengthMenu": "Mostrar _MENU_ Registros por Página",
                "zeroRecords": "Sin resultados",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay Registros Disponibles",
                "infoFiltered": "Filtrado de _TOTAL_ de _MAX_ Registros Totales",
                "search": "Buscar",
                "paginate": {
                    "next": ">",
                    "previous": "<"
                }
            }
        });

    })
</script>


<script>
    var productos = [];
    var totalDolar = 0;
    var totalBS = 0;

    var tasaCambio = document.getElementById("dollar-tasa").value;
    function modificarTabla(id) {
        const url = `{{ route('productos.obtener', ':id') }}`.replace(':id', id); // Reemplaza :id con el valor de ID dinámico

        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al consultar el producto.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log(data)
                    agregarProducto(
                        data.producto.nombre,
                        data.producto.precio_compra,
                        data.producto.aplica_iva,
                        data.producto.cantidad,
                        data.producto.sub_categoria.nombre
                    );
                } else {
                    console.error(data);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Función para agregar un producto al carrito
    function agregarProducto(nombre, precio, iva, stock, subcategoria) {
        const productoExistente = productos.find((p) => p.nombre === nombre);
        if (productoExistente) {
            
                productoExistente.cantidad++;
                productoExistente.subtotal = calcularSubtotal(productoExistente);
            
        } else {
            const nuevoProducto = {
                nombre: nombre,
                precio: parseFloat(precio),
                iva: parseFloat(iva),
                stock: parseInt(stock),
                subcategoria: subcategoria,
                cantidad: 1,
                subtotal: calcularSubtotal({ precio, iva, cantidad: 1 }),
            };
            productos.push(nuevoProducto);
        }
        actualizarTabla();
        pagado()
    }

    // Función para calcular el subtotal de un producto con IVA
    function calcularSubtotal(producto) {
        if (!producto.iva) {
            return producto.precio * producto.cantidad;
        } else {
            return producto.precio * 1.16 * producto.cantidad;
        }

    }

    // Función para aumentar la cantidad de un producto en el carrito
    function aumentarCantidad(nombre, inputElement) {
        const nuevaCantidad = parseFloat(inputElement.value); // Captura el valor ingresado
        const producto = productos.find((p) => p.nombre === nombre);

        if (producto) {
            // Si la cantidad es mayor al stock, ajustarla al máximo disponible
           
                producto.cantidad = nuevaCantidad; // Si la cantidad es válida, la asigna
            

            // Calcular el subtotal con la nueva cantidad
            producto.subtotal = calcularSubtotal(producto);

            // Actualizar el pago y la tabla

            actualizarTabla();
        } else {
            Swal.fire({
                title: 'Producto no encontrado',
                text: 'No se ha encontrado el producto en el carrito.',
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
        }

        pagado();
    }



    // Función para eliminar un producto del carrito
    function eliminarProducto(nombre) {
        productos = productos.filter((p) => p.nombre !== nombre);
        actualizarTabla();
        pagado();
    }

    // Función para actualizar la tabla del carrito y calcular totales
    function actualizarTabla() {
        const tbody = document.querySelector('#productos-ventas tbody');

        tbody.innerHTML = '';

        totalDolar = 0;
        totalBS = 0;

        productos.forEach((producto) => {
            totalDolar += producto.subtotal;
            totalBS += producto.subtotal * tasaCambio;

            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${producto.nombre}</td>
                <td>$${producto.precio.toFixed(2)}</td>
                <td>${producto.iva ? 'SÍ' : 'NO'}</td>
                 
                <td>${producto.subcategoria}</td>
               
               <td>
                <input type="number" class="form-control" step="any" value="${producto.cantidad}" 
                    onChange="aumentarCantidad('${producto.nombre}', this)">
            </td>
                     
                    <button class="btn btn-danger m-2" onclick="eliminarProducto('${producto.nombre}')"><span class="material-icons">delete</span></button>
                </td>
            `;
            tbody.appendChild(fila);
        });

        // Actualizar los totales
        document.getElementById('total-dolar').innerText = `${totalDolar.toFixed(2)}`;
        document.getElementById('total-bs').innerText = `${totalBS.toFixed(2)}`;
        document.getElementById('totalBolivares').value = `${totalBS.toFixed(2)}`;
    }


    function pagado() {
        // Obtener los valores de los métodos de pago
        var efectivo = parseFloat(document.querySelector('input[name="Efectivo"]').value) || 0;
        var punto = parseFloat(document.querySelector('input[name="Punto de Venta"]').value) || 0;
        var transferencia = parseFloat(document.querySelector('input[name="Transferencia"]').value) || 0;
        var pagoMovil = parseFloat(document.querySelector('input[name="Pago Movil"]').value) || 0;
        var biopago = parseFloat(document.querySelector('input[name="Biopago"]').value) || 0;
        var divisa = parseFloat(document.querySelector('input[name="Divisa"]').value) || 0;
        var totalBS = parseFloat(document.getElementById("totalBolivares").value);
        // Obtener la tasa de cambio (en dólares)
        var tasaDollar = document.getElementById("dollar-tasa").value;

        
        var totalDolar = divisa;
        var totalDolar2 = divisa * tasaDollar;


        var totalBs = efectivo + punto + transferencia + pagoMovil + biopago + totalDolar2

        var restante = totalBS.toFixed(2) - totalBs.toFixed(2);

        

        // Habilitar o deshabilitar el botón de "Generar Venta"
        if (totalBS.toFixed(2) == totalBs.toFixed(2) && totalBS > 0) {
            document.getElementById('submitBtn').disabled = false;
        } else {
            document.getElementById('submitBtn').disabled = true;
        }
        document.getElementById('restante').innerText = `${restante.toFixed(2)}`;

        // Asignar la función de actualización a los inputs
        document.querySelectorAll('input[name="Efectivo"], input[name="Punto de Venta"], input[name="Transferencia"], input[name="Pago Movil"], input[name="Biopago"], input[name="Divisa"]').forEach(input => {
            input.addEventListener('input', pagado);
        });

    }
    pagado();

</script>

<script>
    function enviarProductosFormulario() {
        const productosHiddenFieldsContainer = document.getElementById('productos-hidden-fields');
        productosHiddenFieldsContainer.innerHTML = ''; // Clear previous hidden fields

        productos.forEach(producto => {
            const hiddenFields = `
            <input type="hidden" name="productos[${producto.nombre}][nombre]" value="${producto.nombre}">
            <input type="hidden" name="productos[${producto.nombre}][precio]" value="${producto.precio}">
            <input type="hidden" name="productos[${producto.nombre}][cantidad]" value="${producto.cantidad}">
            <input type="hidden" name="productos[${producto.nombre}][subtotal]" value="${producto.subtotal}">
        `;
            productosHiddenFieldsContainer.innerHTML += hiddenFields;
        });
    }
    document.getElementById('venta-form').addEventListener('submit', function (event) {
        enviarProductosFormulario(); // Populate the hidden fields with product data
    });


</script>

@endsection