<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agencia;
use App\Models\Lead;
use App\Models\Suscripcion;
use App\Models\Vehiculo;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'agencias'           => Agencia::count(),
            'agencias_activas'   => Agencia::where('activo', true)->count(),
            'vehiculos'          => Vehiculo::count(),
            'vehiculos_publicos' => Vehiculo::where('status', 'disponible')->count(),
            'leads_hoy'          => Lead::whereDate('created_at', today())->count(),
            'suscripciones'      => Suscripcion::where('status', 'activa')->count(),
            'usuarios'           => User::count(),
        ];

        $agenciasRecientes = Agencia::with('suscripcionActiva.plan')
            ->latest()->limit(6)->get();

        $suscripcionesPorVencer = Suscripcion::with(['agencia', 'plan'])
            ->where('status', 'activa')
            ->where('fecha_vencimiento', '<=', now()->addDays(7))
            ->orderBy('fecha_vencimiento')
            ->limit(5)
            ->get();

        $leadsHoy = Lead::with(['vehiculo.agencia'])
            ->whereDate('created_at', today())
            ->latest()->limit(8)->get();

        return view('admin.dashboard.index', compact(
            'stats', 'agenciasRecientes', 'suscripcionesPorVencer', 'leadsHoy'
        ));
    }
}
