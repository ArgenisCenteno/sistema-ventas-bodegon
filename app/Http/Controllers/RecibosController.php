<?php

namespace App\Http\Controllers;

use App\Exports\RecibosExport;
use App\Models\Recibo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RecibosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
            return Excel::download(new RecibosExport($startDate, $endDate), 'recibos.xlsx');
        } elseif ($type == 'PDF') {
            $recibos = Recibo::with(['pago', 'user'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
            if (count($recibos) == 0) {
                Alert::warning('¡Advertencia!', 'Sin registros encontrados')->showConfirmButton('Aceptar', 'rgba(79, 59, 228, 1)');
                return redirect()->back();
            }
            $pdf = \PDF::loadView('exports.recibos_pdf', compact('recibos'));

            // Abre el PDF en el navegador
            return $pdf->stream('recibos.pdf');
        }
    }


    public function reporte()
    {
        return view('recibos.reporte');
    }
}
