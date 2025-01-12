<?php

namespace App\Http\Controllers;

use App\Models\AperturaCaja;
use App\Models\Caja;
use App\Models\CierreCaja;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Alert;
class AperturaCajaController extends Controller
{
    public function index()
    {
        $aperturas = AperturaCaja::with(['caja', 'usuario'])->get();
        return view('aperturas.index', compact('aperturas'));
    }

    public function create()
    {
        $cajas = Caja::all();
        return view('aperturas.create', compact('cajas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'caja_id' => 'required|exists:cajas,id',
            'usuario_id' => 'required|exists:users,id',
            'monto_inicial_bolivares' => 'required|numeric',
            'monto_inicial_dolares' => 'required|numeric',
        ]);
    
        // Verificar si ya hay una apertura de caja finalizada
        $consultar = AperturaCaja::where('estatus', 'Operando')->first(); // Cambié 'status' a 'estatus'
        if ($consultar) {
            Alert::error('¡Error!', 'Esta caja ya fue abierta')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');
            return redirect()->back(); // Agregar un redireccionamiento en caso de error
        }
    
        // Instanciar el modelo AperturaCaja
        $aperturaCaja = new AperturaCaja();
        $aperturaCaja->caja_id = $request->caja_id;
        $aperturaCaja->usuario_id = $request->usuario_id;
        $aperturaCaja->monto_inicial_bolivares = $request->monto_inicial_bolivares;
        $aperturaCaja->monto_inicial_dolares = $request->monto_inicial_dolares;
        $aperturaCaja->estatus = 'Operando'; // Establecer estatus por defecto
    
        // Guardar el modelo en la base de datos
        $aperturaCaja->save();
    
        Alert::success('¡Éxito!', 'Registro actualizado correctamente')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');
        
        return redirect()->route('aperturas.index')->with('success', 'Apertura de caja creada exitosamente.');
    }
    
    public function show(AperturaCaja $apertura)
    {
        return view('aperturas.show', compact('apertura'));
    }

    public function edit($id)
    {
        $apertura = AperturaCaja::findOrFail($id);
        $caja = Caja::findOrFail($apertura->caja_id);
    
        // Obtén las transacciones agrupadas por venta_id y suma bolívares y dólares
        $transacciones = Transaccion::where('apertura_id', $id)
            ->where('caja_id', $caja->id)
           
            ->get();
    
        // Calcular los totales generales
       
        $montoBs = 0;
        $montoDolar = 0;
        $movimientos = [];  
        $transaferencia = 0;
        $pagoMovil = 0;
        $efectivo = 0;
        $divisa = 0;
        $punto = 0;
        
        foreach ($transacciones as $transaccion) {
            $ventaId = $transaccion->venta_id;
        
            // Si la venta ya existe en el arreglo, actualiza los totales
            if (isset($movimientos[$ventaId])) {
                $movimientos[$ventaId]['total_bolivares'] += $transaccion->monto_total_bolivares;
                $movimientos[$ventaId]['total_dolares'] += $transaccion->monto_total_dolares;
            } else {
                // Si la venta no existe, crea una nueva entrada en el arreglo
                $movimientos[$ventaId] = [
                    'venta_id' => $ventaId,
                    'total_bolivares' => $transaccion->monto_total_bolivares,
                    'total_dolares' => $transaccion->monto_total_dolares,
                ];
            }
        
            // Suma a los totales generales
            $montoBs += $transaccion->monto_total_bolivares;
            $montoDolar += $transaccion->monto_total_dolares;
            switch ($transaccion->metodo_pago) {
                case 'Efectivo':
                    $efectivo += $transaccion->monto_total_bolivares;
                    break;
                case 'Transferencia':
                    $transaferencia += $transaccion->monto_total_bolivares;
                    break;
                case 'Pago Movil':
                    $pagoMovil += $transaccion->monto_total_bolivares;
                    break;
                case 'Divisa':
                    $divisa += $transaccion->monto_total_dolares; // Asume que es en dólares
                    break;
                case 'Punto de Venta':
                    $punto += $transaccion->monto_total_bolivares;
                    break;
            }
        }
        
       
        // Convierte el arreglo asociativo en un arreglo indexado
        $movimientos = array_values($movimientos);
        //dd($montoBs, $montoDolar,  $movimientos);
    
        return view('aperturas.edit', compact(
            'apertura', 
            'caja', 
            'transacciones', 
            'montoBs', 
            'montoDolar', 
            'movimientos', 
            'transaferencia', 
            'pagoMovil', 
            'efectivo', 
            'divisa', 
            'punto'
        ));
        
    }
    

    public function update(Request $request, $id)
    {
        $apertura = AperturaCaja::find($id);
        // Verificar si la caja ya está cerrada
        $cierreExistente = CierreCaja::where('apertura_caja', $apertura->id)->first();
        if ($cierreExistente) {
            Alert::error('¡Error!', 'Ya la caja fue cerrada')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');

            return redirect()->route('aperturas.index')->with('error', 'Esta apertura de caja ya ha sido cerrada.');
        }

        $transacciones = Transaccion::where('apertura_id', $id)
        ->where('caja_id', $apertura->caja_id)
       
        ->get();

    // Calcular los totales generales
   
    $montoBs = 0;
    $montoDolar = 0;
   
    
    foreach ($transacciones as $transaccion) {
       
        $montoBs += $transaccion->monto_total_bolivares;
        $montoDolar += $transaccion->monto_total_dolares;
       
    }
    
    
        // Aquí puedes crear una nueva instancia de CierraCaja
        $cierre = new CierreCaja();
        $cierre->caja_id = $apertura->caja_id; // Suponiendo que el modelo CierraCaja tiene un campo caja_id
        $cierre->usuario_id = auth()->id(); // Si deseas registrar quién cerró la caja
        $cierre->monto_final_bolivares = $montoBs; // Asegúrate de que estos campos existan en tu request
        $cierre->monto_final_dolares = $montoDolar;
      //  $cierre->discrepancia_bolivares = $request->input('discrepancia_bolivares');
       // $cierre->discrepancia_dolares = $request->input('discrepancia_dolares');
        $cierre->apertura_caja = $apertura->id; // Relacionando con la apertura de caja
      
        $cierre->save(); // Guardar el cierre
    
        $apertura->estatus = 'Finalizado';
        $apertura->save();
        Alert::success('¡Exito!', 'Caja cerrada')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');

        return redirect()->route('aperturas.index')->with('success', 'Apertura de caja cerrada exitosamente.');
    }

    public function destroy(AperturaCaja $apertura)
    {
        $apertura->delete();
        Alert::success('¡Éxito!', 'Registro eliminado correctamente')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');

        return redirect()->route('aperturas.index')->with('success', 'Apertura de caja eliminada exitosamente.');
    }
}
