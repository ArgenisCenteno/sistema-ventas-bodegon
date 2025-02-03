<?php

use App\Http\Controllers\AperturaCajaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RecibosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/products', [CarritoController::class, 'products'])->name('products');
Route::get('/detalles/{id}', [CarritoController::class, 'detalles'])->name('detalles');
Route::post('/agregar/{id}', [CarritoController::class, 'agregarCarrito'])->name('carrito.agregar');
Route::get('/carrito', [CarritoController::class, 'show'])->name('carrito.show');
Route::post('/carrito/actualizar', [CarritoController::class, 'actualizarCarrito'])->name('carrito.actualizar');



Route::middleware(['auth'])->group(function () {
Route::get('/checkout', [CarritoController::class, 'checkout'])->name('pagar');
Route::post('/pagarCuenta', [PagoController::class, 'pagarCuenta'])->name('pagarCuenta');

//Notificaciones

Route::get('notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
Route::get('notificaciones/{id}', [NotificacionController::class, 'show'])->name('notificaciones.show');
Route::post('notificaciones/mark-all-read', [NotificacionController::class, 'markAllAsRead'])->name('notificaciones.markAllAsRead');
Route::delete('notificaciones/{id}', [NotificacionController::class, 'destroy'])->name('notificaciones.destroy');

Route::get('/ventas/export', [VentaController::class, 'export'])->name('ventas.export');
Route::get('/ventas/reporte', [VentaController::class, 'reporte'])->name('ventas.reporte');
Route::get('/compras/export', [CompraController::class, 'export'])->name('compras.export');
Route::get('/compras/reporte', [CompraController::class, 'reporte'])->name('compras.reporte');
Route::get('/recibos/export', [RecibosController::class, 'export'])->name('recibos.export');
Route::get('/recibos/reporte', [RecibosController::class, 'reporte'])->name('recibos.reporte');
Route::get('/pagos/export', [PagoController::class, 'export'])->name('pagos.export');
Route::get('/pagos/reporte', [PagoController::class, 'reporte'])->name('pagos.reporte');
Route::get('/productos/export', [ProductoController::class, 'export'])->name('productos.export');
Route::get('/productos/reporte', [ProductoController::class, 'reporte'])->name('productos.reporte');
Route::get('/cierres-caja/export', [CajaController::class, 'export'])->name('cierres_caja.export');
Route::get('/cierres-caja/reporte', [CajaController::class, 'reporte'])->name('cierres_caja.reporte');
Route::get('/usuarios/export', [UserController::class, 'export'])->name('usuarios.export');
Route::get('/usuarios/reporte', [UserController::class, 'reporte'])->name('usuarios.reporte');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
/* ALMACEN DE PRODUCTOS */
Route::get('/almacen', [ProductoController::class, 'almacen'])->name('almacen');
Route::post('/registrar-producto', [ProductoController::class, 'store'])->name('registrar-producto');
Route::resource('productos', App\Http\Controllers\ProductoController::class);
Route::get('/imagenes/{id}', [ProductoController::class, 'imagenesProducto'])->name('imagenes-producto');
Route::delete('/removerImagen/{id}', [ProductoController::class, 'removerImagen'])->name('removerImagen');
Route::post('/agregarImagen/{id}', [ProductoController::class, 'agregarImagen'])->name('agregarImagen');
Route::resource('aperturas', AperturaCajaController::class);

/* CATEGORIAS Y SUBCATEGORIAS*/
Route::resource('categorias', App\Http\Controllers\CategoriaController::class);
Route::resource('subcategorias', App\Http\Controllers\SubCategoriaController::class);

/* CAJAS */
Route::resource('cajas', App\Http\Controllers\CajaController::class);
Route::get('/aperturar/{id}', [CajaController::class, 'aperturarCaja'])->name('cajas.aperturar');
Route::post('/registrarApertura/{id}', [CajaController::class, 'registrarApertura'])->name('cajas.registrarApertura');

/* VENTAS */
Route::resource('ventas', App\Http\Controllers\VentaController::class);
Route::get('/vender', [VentaController::class, 'vender'])->name('ventas.vender');
Route::get('/datatableProductoVenta', [VentaController::class, 'datatableProductoVenta'])->name('ventas.datatableProductoVenta');
Route::post('/generarVenta', [VentaController::class, 'generarVenta'])->name('ventas.generarVenta');
Route::get('/pdfVenta/{id}', [PdfController::class, 'pdfVenta'])->name('ventas.pdf');

// Ruta para obtener un producto por su ID
Route::get('/producto/{id}', [VentaController::class, 'obtenerProducto'])->name('productos.obtener');


/* TASAS, MONEDAS E IMPUESTOS */
Route::resource('tasas', App\Http\Controllers\TasasController::class);
});

/* COMPRAS */
Route::resource('compras', App\Http\Controllers\CompraController::class);
Route::get('/comprar', [CompraController::class, 'comprar'])->name('compras.comprar');
Route::get('/datatableProductoCompra', [CompraController::class, 'datatableProductoCompras'])->name('compras.datatableProductoCompra');
Route::post('/generarCompra', [CompraController::class, 'generarCompra'])->name('compras.generarCompra');
Route::get('/pdfCompra/{id}', [PdfController::class, 'pdfCompra'])->name('compras.pdf');

/* PROVEEDORES */
Route::resource('proveedores', App\Http\Controllers\ProveedorController::class);

/* PAGOS */
Route::resource('pagos', App\Http\Controllers\PagoController::class);
Route::get('/pdfPago/{id}', [PdfController::class, 'pdfPago'])->name('pagos.pdf');
Route::get('/editCliente/{id}', [UserController::class, 'editarCliente'])->name('clientes.edit');
Route::put('/updateCliente/{id}', [UserController::class, 'updateCliente'])->name('clientes.update');

/* PAGOS */
Route::resource('usuarios', App\Http\Controllers\UserController::class);
Route::get('/clientes', [UserController::class, 'clientes'])->name('usuarios.clientes');
Route::get('/crear-cliente', [UserController::class, 'crearCliente'])->name('usuarios.crearClientes');

Route::get('/pdfUser/{id}', [PdfController::class, 'pdfEstadoCuenta'])->name('usuarios.pdf');
// Ruta de inicio de sesión
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Auth::routes();

