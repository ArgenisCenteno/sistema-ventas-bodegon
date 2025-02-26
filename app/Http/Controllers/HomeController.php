<?php

namespace App\Http\Controllers;

use App\Models\AperturaCaja;
use App\Models\Caja;
use App\Models\Categoria;
use App\Models\Compra;
use App\Models\DetalleVenta;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Recibo;
use App\Models\SubCategoria;
use App\Models\Tasa;
use App\Models\User;
use App\Models\Venta;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


        if (Auth::user()->hasRole('superAdmin') || Auth::user()->hasRole('empleado')) {
            $ventas = Venta::count();  
            $pagos = Pago::count();
            $productos = Producto::count();
            $compras = Compra::count();
            $usuarios = User::count();
            $ventasMonto = Venta::whereDate('created_at', Carbon::today())->sum('monto_total');
            $comprasMonto = Compra::whereDate('created_at', Carbon::today())->sum('monto_total');
            $pagosMonto = Pago::whereDate('created_at', Carbon::today())->sum('monto_total');
            $recibos = Recibo::count();

            $categorias = Categoria::count();
            $subcategorias = SubCategoria::count();
            $proveedores = Proveedor::count();

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
                $dolar = $dato->promedio;
            } else {
                $consulta = Tasa::where('name', 'DOLLAR')->where('status', 'Activo')->first();
                $dolar = $consulta->valor ?? 64.32;
            }

            $notificaciones = auth()->user()->unreadNotifications;
            $ventas2 = Venta::selectRaw('MONTH(created_at) as month, SUM(monto_total
            ) as total_sales')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            // Prepare data for chart
            $meses1 = [];
            $ventasData = [];

            // Map the data into arrays
            foreach ($ventas2 as $venta) {
                // Carbon to get the full month name (January, February, etc.)
                $meses1[] = Carbon::createFromFormat('m', $venta->month)->format('F');
                $ventasData[] = $venta->total_sales;
            }

            $compras2 = Compra::selectRaw('MONTH(created_at) as month, SUM(monto_total
            ) as total_sales')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            // Prepare data for chart
            $meses2 = [];
            $comprasData = [];

            // Map the data into arrays
            foreach ($compras2 as $venta) {
                // Carbon to get the full month name (January, February, etc.)
                $meses2[] = Carbon::createFromFormat('m', $venta->month)->format('F');
                $comprasData[] = $venta->total_sales;
            }
            $caja = Caja::find(1);
            $apertura = AperturaCaja::where('caja_id', $caja->id)->where('estatus', 'Operando')->first();
            
            if (!$apertura) {
                 
                return view('home', compact('comprasMonto', 'ventasMonto', 'pagosMonto', 'recibos', 'meses1', 'ventasData', 'comprasData', 'ventas', 'dolar', 'compras', 'notificaciones', 'proveedores', 'usuarios', 'productos', 'categorias', 'subcategorias', 'pagos'))
                       ->with('cajaCerrada', true);
            }
            

            return view('home', data: compact('comprasMonto','ventasMonto','pagosMonto','recibos','meses1', 'ventasData', 'comprasData', 'ventas', 'dolar', 'compras', 'notificaciones', 'proveedores', 'usuarios', 'productos', 'categorias', 'subcategorias', 'pagos'));
        } else {
            $ventas = Venta::where('user_id', Auth::user()->id)->count();
            $pagos = Pago::where('user_id', Auth::user()->id)->count();
            $productos = DetalleVenta::join('ventas', 'detalle_ventas.id_venta', '=', 'ventas.id')
                ->where('ventas.user_id', Auth::user()->id)
                ->count();

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
                $dolar = $dato->promedio;
            } else {
                $consulta = Tasa::where('name', 'DOLLAR')->where('status', 'Activo')->first();
                $dolar = $consulta->valor ?? 64.20;
            }

            $notificaciones = auth()->user()->unreadNotifications;

            $ventas2 = Venta::selectRaw('MONTH(created_at) as month, SUM(monto_total
        ) as total_sales')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            // Prepare data for chart
            $meses1 = [];
            $ventasData = [];

            // Map the data into arrays
            foreach ($ventas2 as $venta) {
                // Carbon to get the full month name (January, February, etc.)
                $meses1[] = Carbon::createFromFormat('m', $venta->month)->format('F');
                $ventasData[] = $venta->total_sales;
            }

            $compras2 = Compra::selectRaw('MONTH(created_at) as month, SUM(monto_total
        ) as total_sales')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            // Prepare data for chart
            $meses2 = [];
            $ventasData = [];

            // Map the data into arrays
            foreach ($compras2 as $venta) {
                // Carbon to get the full month name (January, February, etc.)
                $meses2[] = Carbon::createFromFormat('m', $venta->month)->format('F');
                $comprasData[] = $venta->total_sales;
            }

            return view('home', compact('meses1', 'ventasData', 'comprasData', 'ventas', 'dolar', 'notificaciones', 'productos', 'pagos'));
        }

    }


}
