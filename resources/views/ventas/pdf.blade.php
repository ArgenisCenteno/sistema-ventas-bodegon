<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
</head>

<body
    style="font-family: Arial, sans-serif; margin: 0; padding: 10px; line-height: 1.6; border: none; background-color: #f9f9f9;">
    <div
        style="max-width: 800px; margin: auto; padding: 10px; border-radius: 8px; background: #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <!-- Encabezado -->
        <div
            style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 10px; border-bottom: 2px solid #ddd;">
            <div style="width: 20%; flex: 1;">
            </div>
            <div style="text-align: center; flex: 1;">

                <h1 style="margin: 0;  color: #333;"></h1>
            </div>

        </div>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr>
                    <!-- Columna para el Logo -->
                    <th
                        style="border-bottom: 2px solid #ddd; padding: 8px; text-align: center; font-size: 18px; width: 15%;">
                        <img src="{{ public_path('iconos/logo-empresa.png') }}" alt="Logo"
                            style="max-width: 80px; height: auto;">
                    </th>
                    <!-- Columna para el Nombre de la Empresa -->
                    <th colspan="2"
                        style="border-bottom: 2px solid #ddd; padding: 8px; text-align: center; font-size: 22px; font-weight: bold; width: 70%;">
                        EL BODEGÓN DE PELUCHE
                    </th>
                    <!-- Columna para el Número de Venta -->
                    @php
                        $id = str_pad($venta->id, 8, "0", STR_PAD_LEFT);
                    @endphp
                    <th
                        style="border-bottom: 2px solid #ddd; padding: 8px; text-align: center; font-size: 22px; width: 15%;">
                        {{$id}}
                    </th>
                </tr>
            </thead>
        </table>


        <!-- Título -->
        <h3 style="text-align: center; color: #333; font-size: 24px; margin: 20px 0;">COMPROBANTE DE VENTA</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="border-bottom: 2px solid #ddd; padding: 8px; text-align: left;">DIRECCIÓN</th>
                    <th style="border-bottom: 2px solid #ddd; padding: 8px; text-align: left;">CIUDAD.</th>
                    <th style="border-bottom: 2px solid #ddd; padding: 8px; text-align: left;">ESTADO.</th>
                    <th style="border-bottom: 2px solid #ddd; padding: 8px; text-align: left;">FECHA.</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">CALLE NUEVA SECTOR CENTRO</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">PUNTA DE MATA</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">MONAGAS</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{$fechaVenta}}</td>
                </tr>
            </tbody>
        </table>
        <!-- Detalles del cliente y vendedor -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="border-bottom: 2px solid #ddd; padding: 8px; text-align: left;">CLIENTE</th>
                    <th style="border-bottom: 2px solid #ddd; padding: 8px; text-align: left;">VENDEDOR.</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{$userArray['name']}}</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{$vendedorArray['name']}}</td>


                </tr>
            </tbody>
        </table>


        <!-- Tabla de productos -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="border-bottom: 2px solid #ddd; padding: 8px; text-align: left;">DESCRIPCION</th>
                    <th style="border-bottom: 2px solid #ddd; padding: 8px; text-align: left;">CANT.</th>
                    <th style="border-bottom: 2px solid #ddd; padding: 8px; text-align: left;">PRECIO UNIT.</th>
                    <th style="border-bottom: 2px solid #ddd; padding: 8px; text-align: left;">IVA</th>
                    <th style="border-bottom: 2px solid #ddd; padding: 8px; text-align: left;">NETO</th>
                    <th style="border-bottom: 2px solid #ddd; padding: 8px; text-align: left;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($venta->detalleVentas as $detalle)
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{$detalle->producto->nombre}}</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{$detalle->cantidad}}</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{$detalle->precio_producto}}</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{$detalle->impuesto}}</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{$detalle->neto}}</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                            {{number_format($detalle->impuesto + $detalle->neto, 2)}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Resumen de totales -->
        <div style="text-align: right; margin-bottom: 20px;">
            <p><strong>SUBTOTAL:</strong> {{$venta->pago->monto_neto}}</p>
            <p><strong>IVA (16%):</strong> {{$venta->pago->impuestos}}</p>
            <p><strong>MONTO TOTAL:</strong> {{$venta->pago->monto_total}}</p>
        </div>
    </div>
</body>

</html>