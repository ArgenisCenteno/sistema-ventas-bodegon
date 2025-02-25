<?php

namespace App\Providers;

use App\Models\AperturaCaja;
use App\Models\Caja;
use App\Models\Tasa;
use Illuminate\Support\ServiceProvider;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $connected = @fsockopen("www.google.com", 80); // Intenta conectar al puerto 80 de Google
        if ($connected) {
            fclose($connected);
            $response = file_get_contents("https://ve.dolarapi.com/v1/dolares/oficial");
        } else {
            $response = false;
        }
    
        if ($response) {
            $dato = json_decode($response);
            $dolar = $dato->promedio;
        } else {
            $consulta = Tasa::where('name', 'DOLLAR')->where('status', 'Activo')->first();
            $dolar = $consulta->valor;
        }

        $caja = Caja::find(1);
        $aperturasss = null;
            $apertura = AperturaCaja::where('caja_id', $caja->id)->where('estatus', 'Operando')->first();
            
            if (!$apertura) {
                 
                $aperturasss = false;
            }else{
                $aperturasss = true;
            }
    
        // Compartir la variable $dolar con todas las vistas
        View::share('dolar', $dolar);
        View::share('aperturasss', $aperturasss);
    }
}
