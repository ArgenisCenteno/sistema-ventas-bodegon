<?php

namespace App\Exports;

use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductosExport implements FromView, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = Producto::with(['subCategoria', 'imagenes']);

        if (isset($this->filters['disponible'])) {
            $query->where('disponible', $this->filters['disponible']);
        }

        if (isset($this->filters['fecha_inicio']) && isset($this->filters['fecha_fin'])) {
            $query->whereBetween('created_at', [$this->filters['fecha_inicio'], $this->filters['fecha_fin']]);
        }

        $productos = $query->get();

        return view('exports.productos', compact('productos'));
    }
}
