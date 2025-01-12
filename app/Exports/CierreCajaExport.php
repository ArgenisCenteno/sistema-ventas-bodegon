<?php

namespace App\Exports;

use App\Models\CierreCaja;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CierreCajaExport implements FromView, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = CierreCaja::with(['caja', 'usuario']);

        if (isset($this->filters['fecha_inicio']) && isset($this->filters['fecha_fin'])) {
            $query->whereBetween('created_at', [$this->filters['fecha_inicio'], $this->filters['fecha_fin']]);
        }

        $cierres = $query->get();

        return view('exports.cierres_caja', compact('cierres'));
    }
}
