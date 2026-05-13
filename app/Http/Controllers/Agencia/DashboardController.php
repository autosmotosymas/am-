<?php

namespace App\Http\Controllers\Agencia;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $agencia = auth()->user()->agencia;

        abort_unless($agencia, 403, 'No tienes una agencia asociada.');

        $agencia->load('suscripcionActiva.plan');

        $stats = [
            'activos'   => $agencia->vehiculos()->where('status', 'disponible')->count(),
            'pausados'  => $agencia->vehiculos()->where('status', 'inactivo')->count(),
            'vendidos'  => $agencia->vehiculos()->where('status', 'vendido')->count(),
            'vistas'    => $agencia->vehiculos()->sum('vistas'),
            'leads_new' => Lead::whereIn('vehiculo_id', $agencia->vehiculos()->pluck('id'))
                               ->where('status', 'nuevo')->count(),
            'leads_total' => Lead::whereIn('vehiculo_id', $agencia->vehiculos()->pluck('id'))->count(),
        ];

        $leadsRecientes = Lead::with('vehiculo')
            ->whereIn('vehiculo_id', $agencia->vehiculos()->pluck('id'))
            ->latest()
            ->limit(5)
            ->get();

        $vehiculosDestacados = $agencia->vehiculos()
            ->with('fotoPrincipal')
            ->where('status', 'disponible')
            ->orderByDesc('vistas')
            ->limit(5)
            ->get();

        return view('agencia.dashboard.index', compact(
            'agencia', 'stats', 'leadsRecientes', 'vehiculosDestacados'
        ));
    }
}
