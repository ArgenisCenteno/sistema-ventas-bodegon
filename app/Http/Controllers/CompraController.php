<?php
namespace App\Http\Controllers;

use App\Exports\ComprasExport;
use App\Models\Caja;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Recibo;
use App\Models\Tasa;
use App\Models\Transaccion;
use App\Models\User; 
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Alert;
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        
        if ($request->ajax()) {
            $data = Compra::with(['user', 'proveedor', 'pago'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user', function($row) {
                    return $row->user->name;
                })
                ->addColumn('proveedor', function($row) {
                    return $row->proveedor->razon_social;
                })
                ->addColumn('monto_neto', function($row) {
                    return number_format($row->pago->monto_neto, 2);
                })
                ->addColumn('impuestos', function($row) {
                    return number_format($row->pago->impuestos, 2);
                })
                ->addColumn('monto_total', function($row) {
                    return number_format($row->pago->monto_total, 2);
                })
                ->addColumn('fecha', function($row) {
                    return $row->created_at->format('Y-m-d'); // Ajusta el formato de fecha aquí
                })
                ->addColumn('status', function($row) {
                    $status = $row->pago->status;
                    $class = $status == 'Pagado' ? 'success' : 'danger'; // Clase basada en el estado
                    return '<span class="badge bg-' . $class . '">' . $status . '</span>';
                })
                ->addColumn('actions', function ($row) {
                    $viewUrl = route('compras.show', $row->id);
                    $deleteUrl = route('compras.destroy', $row->id);
                    $pdfUrl = route('compras.pdf', $row->id); // Asegúrate de que la ruta esté correcta
                
                    return view('compras.actions', compact('viewUrl', 'deleteUrl', 'pdfUrl'))->render();
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }
    
        return view('compras.index');
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

   

    public function comprar(Request $request)
    {
        $caja = Caja::where('activa', '1')->first();
        $users = Proveedor::pluck('razon_social', 'id');
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
        return view('compras.comprar')->with('caja', $caja)->with('dollar', $dollar)->with('users', $users);
    }

    public function datatableProductoCompra(Request $request)
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
        if ($request->ajax()) {
            $producto = Producto::with('subCategoria')->find($id);


            if (!$producto) {
                return response()->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
            }

            return response()->json(['success' => true, 'producto' => $producto]);
        } else {
            return response()->json(['success' => false, 'message' => 'Solicitud no válida'], 400);
        }
    }

    public function generarCompra(Request $request)
    {

        if ($request->productos == [] || $request->productos == null) {
            Alert::error('¡Error!', 'Para realizar una venta es necesario agregar productos')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');
            return redirect()->back();
        }
        //obtener datos
        $productos = $request->productos;

        $paymentMethods = [
            "efectivo" => $request->input('efectivo'),
            "punto" => $request->input('punto'),
            "Transferencia" => $request->input('Transferencia'),
            "Pago_Movil" => $request->input('Pago_Movil'),
            "Biopago" => $request->input('Biopago'),
            "Divisa" => $request->input('Divisa')
        ];
    
        // Filtrar los métodos de pago que sean distintos de null y mayores a cero
        $filteredPayments = array_filter($paymentMethods, function($value) {
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
           


            $productoModel = Producto::where('nombre',$nombre)->first();
            

            $totalNeto += $productoModel->precio_compra * $cantidad;

            if ($productoModel->aplica_iva == 1) {
                $montoTotal += $cantidad * $productoModel->precio_compra * 1.16;
                $impuestosTotal += ($productoModel->precio_compra * 0.16) * $cantidad;
            } else {
                $montoTotal += $productoModel->precio_compra * $cantidad;
            }
        }



        $userId = Auth::id();

        //registrar pago

        $pago = new Pago();
        $pago->status = 'Pagado';
        $pago->tipo = 'Compra Regular';
        $pago->forma_pago = json_encode($metodos);
        $pago->monto_total = $montoTotal;
        $pago->monto_neto = $totalNeto;
        $pago->tasa_dolar = $request->tasa;
        $pago->creado_id = $userId;
        $pago->fecha_pago = Carbon::now()->format('Y-m-d');
        $pago->impuestos = $impuestosTotal;
        $pago->save();

        //registrar Compra
        $Compra = new Compra();
        $Compra->user_id = $request->user_id;
        $Compra->proveedor_id = $userId;
        $Compra->monto_total = $montoTotal;
        $Compra->status = 'Pagado';
        $Compra->pago_id = $pago->id;
        $Compra->save();

        // Registrar detalles Compras
        foreach ($productos as $producto) {
            $nombre = $producto['nombre'];
           
            $cantidad = $producto['cantidad'];
           


            $productoModel = Producto::where('nombre',$nombre)->first();


            $detalleCompra = new DetalleCompra();
            $detalleCompra->id_producto = $productoModel->id;
            $detalleCompra->precio_producto = $productoModel->precio_venta;
            $detalleCompra->cantidad = $cantidad;
            $detalleCompra->neto = $productoModel->precio_venta * $cantidad;
            $detalleCompra->impuesto = ($productoModel->aplica_iva == 1) ? ($productoModel->precio_venta * 0.16) * $cantidad : 0;
            $detalleCompra->id_Compra = $Compra->id;
            $detalleCompra->save();

            // Actualizar stock
            
            $productoModel->cantidad += $producto['cantidad'];
            $productoModel->save();
        }

        $recibo = new Recibo();
        $recibo->tipo = 'Compra';
        $recibo->monto = $montoTotal;
        $recibo->estatus = 'Pagado';
        $recibo->pago_id = $pago->id;
        $recibo->user_id = $request->user_id;
        $recibo->activo = 1;
        $recibo->creado_id = $userId;
        $recibo->descuento = $request->descuento;
        $recibo->save();


        Alert::success('¡Exito!', 'Compra generada exitosamente')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');
        return redirect()->route('compras.show', $Compra->id);
    }

    public function destroy($id)
    {
        // Encuentra la Compra por su ID
        $Compra = Compra::find($id);
        
        if (!$Compra) {
            Alert::error('¡Error!', 'Compra no encontrada')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');
            return redirect()->route('compras.index');
        }
    
        // Elimina los detalles de la Compra
        $Compra->detalleCompras()->delete();
    
        // Elimina el pago asociado a la Compra
        if ($Compra->pago) {
            $Compra->pago->delete();
        }
    
        // Elimina la Compra
        $Compra->delete();
    
        Alert::success('¡Éxito!', 'Compra y pago eliminados exitosamente')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');
        return redirect()->route('compras.index');
    }
    

    public function show($id)
    {
        $compra = Compra::with(['user', 'proveedor', 'pago', 'detalleCompras'])->find($id);
        return view('compras.show', compact('compra'));
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
            return Excel::download(new ComprasExport($startDate, $endDate), 'compras.xlsx');
        } elseif ($type == 'PDF') {
            $compras = Compra::with(['proveedor', 'user'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
            
                if(count($compras) == 0){
                    Alert::warning('¡Advertencia!', 'Sin registros encontrados')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');
                    return redirect()->back();
                }
                
            $pdf = \PDF::loadView('exports.compras_pdf', compact('compras'));
    
            // Abre el PDF en el navegador
            return $pdf->stream('compras.pdf');
        }
    }
    

    public function reporte(){
        $ventas = Compra::selectRaw('MONTH(created_at) as month, SUM(monto_total
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
        return view('compras.reporte' , compact('meses', 'ventasData'));
    }
}
