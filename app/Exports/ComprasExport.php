<?php

namespace App\Exports;

use App\Models\Compra;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ComprasExport implements FromView, ShouldAutoSize
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
        $compras = Compra::with(['proveedor', 'user'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();

        return view('exports.compras', compact('compras'));
    }
}
