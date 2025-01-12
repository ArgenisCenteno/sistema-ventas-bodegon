<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UserExport implements FromView, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = User::query();

        if (isset($this->filters['fecha_inicio']) && isset($this->filters['fecha_fin'])) {
            $query->whereBetween('created_at', [$this->filters['fecha_inicio'], $this->filters['fecha_fin']]);
        }

        $usuarios = $query->get();

        return view('exports.usuarios', compact('usuarios'));
    }
}
