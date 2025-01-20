<?php

namespace App\Http\Controllers;

use App\Exports\VentasExport;
use App\Models\AperturaCaja;
use App\Models\Caja;
use App\Models\DetalleVenta;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\Recibo;
use App\Models\Tasa;
use App\Models\Transaccion;
use App\Models\User;
use App\Models\Venta;
use Carbon\Carbon;
use Http;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Alert;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->hasRole('superAdmin') || Auth::user()->hasRole('superAdmin')) {
                $data = Venta::with(['user', 'vendedor', 'pago'])->get();
 
            } else {
                $data = Venta::with(['user', 'vendedor', 'pago'])->where('vendedor_id', Auth::user()->id)->get();

            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('vendedor', function ($row) {
                    return $row->vendedor->name ?? 'S/D';
                })
                ->addColumn('monto_neto', function ($row) {
                    return number_format($row->pago->monto_neto, 2);
                })
                ->addColumn('impuestos', function ($row) {
                    return number_format($row->pago->impuestos, 2);
                })
                ->addColumn('monto_total', function ($row) {
                    return number_format($row->pago->monto_total, 2);
                })
                ->addColumn('fecha', function ($row) {
                    return $row->created_at->format('Y-m-d'); // Ajusta el formato de fecha aquí
                })
                ->addColumn('status', function ($row) {
                    $status = $row->pago->status;
                    $class = $status == 'Pagado' ? 'success' : 'danger'; // Clase basada en el estado
                    return '<span class="badge bg-' . $class . '">' . $status . '</span>';
                })
                ->addColumn('actions', function ($row) {
                    $viewUrl = route('ventas.show', $row->id);
                    $deleteUrl = route('ventas.destroy', $row->id);
                    $pdfUrl = route('ventas.pdf', $row->id); // Asegúrate de que la ruta esté correcta
    
                    return view('ventas.actions', compact('viewUrl', 'deleteUrl', 'pdfUrl'))->render();
                })

                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('ventas.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }



    public function vender(Request $request)
    {


        $users = User::pluck('name', 'id');

        function isConnected()
        {
            $connected = @fsockopen("www.google.com", 80); // Intenta conectar al puerto 80 de Google
            if ($connected) {
                fclose($connected);
                return true; // Hay conexión
            }
            return false; // No hay conexión
        }

        if (isConnected()) {
            $response = file_get_contents("https://ve.dolarapi.com/v1/dolares/oficial");

        } else {

            $response = false;
        }



        // dd();
        if ($response) {
            $dato = json_decode($response);
            $dollar = $dato->promedio;
        } else {
            $consulta = Tasa::where('name', 'DOLLAR')->where('status', 'Activo')->first();
            $dollar = $consulta->valor;

        }

        return view('ventas.vender')->with('dollar', $dollar)->with('users', $users);
    }

    public function datatableProductoVenta(Request $request)
    {
        if ($request->ajax()) {
            $productos = Producto::with('subCategoria')->get(); // Cargar la relación subCategoria

            return DataTables::of($productos)
                ->addColumn('fecha_vencimiento', function ($producto) {
                    $date = Carbon::now();
                    if ($producto->fecha_vencimiento <= $date) {
                        return '<span class="badge bg-danger">Vencido</span>';
                    } else {
                        return $producto->fecha_vencimiento;
                    }
                })
                ->editColumn('created_at', function ($producto) {
                    return $producto->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('subCategoria', function ($producto) {
                    return $producto->subCategoria ? $producto->subCategoria->nombre : '';
                })
                ->addColumn('actions', function ($producto) {
                    return '<button type="button" id="agregarCarrito" class="btn btn-info"><span class="material-icons">shopping_cart</span></button>';
                })
                ->rawColumns(['fecha_vencimiento', 'actions']) // Especifica las columnas que contienen HTML sin escape
                ->make(true);
        }
    }


    public function obtenerProducto(Request $request, $id)
    {

        $producto = Producto::with('subCategoria')->find($id);


        if (!$producto) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
        }

        return response()->json(['success' => true, 'producto' => $producto]);


    }

    public function generarVenta(Request $request)
    {

        $caja = Caja::find(1);

        if (!$caja) {
            Alert::error('¡Error!', 'No hay caja disponible')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');
            return redirect()->back();

        }

        $apertura = AperturaCaja::where('caja_id', $caja->id)->where('estatus', 'Operando')->first();
        if (!$apertura) {
            Alert::error('¡Error!', 'No existe una caja abierta')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');
            return redirect()->back();
        }
        if ($request->productos == [] || $request->productos == null) {
            Alert::error('¡Error!', 'Para realizar una venta es necesario agregar productos')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');
            return redirect()->back();
        }
        //obtener datos
        $productos = $request->productos;

        $paymentMethods = [
            [
                "id" => 1,
                "metodo" => "Efectivo",
                "monto" => $request->input('Efectivo') // Monto ingresado para "Efectivo"
            ],
            [
                "id" => 2,
                "metodo" => "Punto de Venta",
                "monto" => $request->input('Punto-de-Venta')
            ],
            [
                "id" => 3,
                "metodo" => "Transferencia",
                "monto" => $request->input('Transferencia')
            ],
            [
                "id" => 4,
                "metodo" => "Pago Movil",
                "monto" => $request->input('Pago-Movil')
            ],
            [
                "id" => 5,
                "metodo" => "Biopago",
                "monto" => $request->input('Biopago')
            ],
            [
                "id" => 6,
                "metodo" => "Divisa",
                "monto" => $request->input('Divisa')
            ]
        ];


       
        // Filtrar los métodos de pago que sean distintos de null y mayores a cero
        $filteredPayments = array_filter($paymentMethods, function ($value) {
            return $value !== null && $value > 0;
        });

        // Convertir el array filtrado a un objeto JSON
        $jsonObject = json_encode($filteredPayments);

        $metodos = json_decode($jsonObject, true);


        //calcular el monto total, monto neto e impuestos

        $totalNeto = 0;
        $montoTotal = 0;
        $impuestosTotal = 0;

        foreach ($productos as $producto) {
            $nombre = $producto['nombre'];

            $cantidad = $producto['cantidad'];



            $productoModel = Producto::where('nombre', $nombre)->first();
            if ($productoModel->cantidad <= $cantidad) {


                // Mostrar un mensaje de error con el nombre del producto
                Alert::error('¡Error!', "Stock insuficiente para el producto: $nombre")
                    ->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');

                return redirect()->back();
            }

            $totalNeto += $productoModel->precio_venta * $cantidad;

            if ($productoModel->aplica_iva == 1) {
                $montoTotal += $cantidad * $productoModel->precio_venta * 1.16;
                $impuestosTotal += ($productoModel->precio_venta * 0.16) * $cantidad;
            } else {
                $montoTotal += $productoModel->precio_venta * $cantidad;
            }
        }


        $userId = Auth::id();

        //registrar pago

        $pago = new Pago();
        $pago->status = 'Pagado';
        $pago->tipo = 'Venta Regular';
        $pago->forma_pago = json_encode($metodos);
        $pago->monto_total = $montoTotal;
        $pago->monto_neto = $totalNeto;
        $pago->tasa_dolar = $request->tasa;
        $pago->creado_id = $userId;
        $pago->fecha_pago = Carbon::now()->format('Y-m-d');
        $pago->impuestos = $impuestosTotal;
        $pago->save();

        //registrar venta
        $venta = new Venta();
        $venta->user_id = $request->user_id;
        $venta->vendedor_id = $userId;
        $venta->monto_total = $montoTotal;
        $venta->status = 'Pagado';
        $venta->pago_id = $pago->id;
        $venta->save();

        // Registrar detalles ventas
        foreach ($productos as $producto) {
            $nombre = $producto['nombre'];

            $cantidad = $producto['cantidad'];



            $productoModel = Producto::where('nombre', $nombre)->first();


            $detalleVenta = new DetalleVenta();
            $detalleVenta->id_producto = $productoModel->id;
            $detalleVenta->precio_producto = $productoModel->precio_venta;
            $detalleVenta->cantidad = $cantidad;
            $detalleVenta->neto = $productoModel->precio_venta * $cantidad;
            $detalleVenta->impuesto = ($productoModel->aplica_iva == 1) ? ($productoModel->precio_venta * 0.16) * $cantidad : 0;
            $detalleVenta->id_venta = $venta->id;
            $detalleVenta->save();

            // Actualizar stock

            $productoModel->cantidad -= $producto['cantidad'];
            $productoModel->save();

        }

        $recibo = new Recibo();
        $recibo->tipo = 'Venta';
        $recibo->monto = $montoTotal;
        $recibo->estatus = 'Pagado';
        $recibo->pago_id = $pago->id;
        $recibo->user_id = $request->user_id;
        $recibo->activo = 1;
        $recibo->creado_id = $userId;
        $recibo->descuento = $request->descuento;
        $recibo->save();

        //caja
       

        
        foreach ($metodos as $metodo) {
            // Verificar si el monto es mayor a 0 para cada metodo
            
            if (($metodo['monto'] > 0) && $metodo['metodo']) {
                // Asignar monto y datos del método de pago según el caso
                $monto = $metodo['monto'];
                $forma_pago = $metodo['metodo'];
               
                $moneda = ($metodo['metodo'] === 'Divisa') ? 'Dollar' : 'Bolívar';  // Cambiar moneda según el método
           
           
            // Crear la transacción
            $transaccion = new Transaccion();
            $transaccion->caja_id = 1; // Asume que tienes un valor fijo o el ID de la caja
            $transaccion->usuario_id = $userId; // Asume que tienes el ID del usuario
            $transaccion->tipo = 'VENTA'; // Tipo de transacción
            $transaccion->apertura_id = $apertura->id;
            $transaccion->venta_id = $venta->id;
            $transaccion->metodo_pago = $forma_pago;

            // Verificar si el pago es en divisa y asignar los valores correspondientes
            if ($moneda === 'Dollar') {
                $transaccion->moneda = $moneda;
                $transaccion->monto_total_dolares = $monto ?? 0;
                $transaccion->monto_total_bolivares = 0;
            } else {
                $transaccion->moneda = $moneda;
                $transaccion->monto_total_bolivares = $monto ?? 0;
                $transaccion->monto_total_dolares = 0;
            }

            $transaccion->fecha = Carbon::now(); // Fecha actual de la transacción
            $transaccion->save(); // Guardar la transacción en la base de datos
            }
            
           
        }

        Alert::success('¡Exito!', 'Venta generada exitosamente')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');
        return redirect()->route('ventas.show', $venta->id);
    }

    public function destroy($id)
    {
        // Encuentra la venta por su ID
        $venta = Venta::find($id);

        if (!$venta) {
            Alert::error('¡Error!', 'Venta no encontrada')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');
            return redirect()->route('ventas');
        }

        // Elimina los detalles de la venta
        $venta->detalleVentas()->delete();

        // Elimina el pago asociado a la venta
        if ($venta->pago) {
            $venta->pago->delete();
        }

        // Elimina la venta
        $venta->delete();

        Alert::success('¡Éxito!', 'Venta y pago eliminados exitosamente')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');
        return redirect()->route('ventas.index');
    }


    public function show($id)
    {
        $venta = Venta::with(['user', 'vendedor', 'pago', 'detalleVentas'])->find($id);
        return view('ventas.show', compact('venta'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $type = $request->type;

        if ($type == 'EXCEL') {
            return Excel::download(new VentasExport($startDate, $endDate), 'ventas.xlsx');
        } elseif ($type == 'PDF') {
            $ventas = Venta::with(['user', 'vendedor'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $pdf = \PDF::loadView('exports.ventas_pdf', compact('ventas'));

            // Abre el PDF en el navegador
            return $pdf->stream('ventas.pdf');
        }
    }


    public function reporte()
    {
        // Query the sales and group by month
        $ventas = Venta::selectRaw('MONTH(created_at) as month, SUM(monto_total
        ) as total_sales')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Prepare data for chart
        $meses = [];
        $ventasData = [];

        // Map the data into arrays
        foreach ($ventas as $venta) {
            // Carbon to get the full month name (January, February, etc.)
            $meses[] = Carbon::createFromFormat('m', $venta->month)->format('F');
            $ventasData[] = $venta->total_sales;
        }
        return view('ventas.reporte', compact('meses', 'ventasData'));
    }
}
