<?php

namespace App\Exports;

use App\Models\Pago;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PagosExport implements FromView, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $pagos = Pago::with(['ventas', 'compras', 'recibos', 'user'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();

        return view('exports.pagos', compact('pagos'));
    }
}
