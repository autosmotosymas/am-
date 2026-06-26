<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $vendedor = auth()->user();
        $agencias = $vendedor->agenciasVendidas()
                             ->withCount('vehiculos')
                             ->latest()
                             ->get();

        $totalVehiculos = $agencias->sum('vehiculos_count');

        return view('vendedor.dashboard', compact('agencias', 'totalVehiculos'));
    }
}
