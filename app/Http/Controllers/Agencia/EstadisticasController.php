<?php

namespace App\Http\Controllers\Agencia;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\View\View;

class EstadisticasController extends Controller
{
    public function index(): View
    {
        $agencia = auth()->user()->agencia;
        abort_unless($agencia, 403);

        $vehiculoIds = $agencia->vehiculos()->pluck('id');

        // Top 5 vehículos por vistas
        $topVistas = $agencia->vehiculos()
            ->with('fotoPrincipal')
            ->orderByDesc('vistas')
            ->limit(5)
            ->get();

        // Top 5 vehículos por leads
        $topLeads = $agencia->vehiculos()
            ->withCount('leads')
            ->orderByDesc('leads_count')
            ->limit(5)
            ->get();

        // Leads por tipo de contacto
        $leadsPorTipo = Lead::whereIn('vehiculo_id', $vehiculoIds)
            ->selectRaw('tipo, count(*) as total')
            ->groupBy('tipo')
            ->pluck('total', 'tipo');

        // Leads por mes (últimos 6 meses)
        $leadsPorMes = Lead::whereIn('vehiculo_id', $vehiculoIds)
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes, count(*) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        $stats = [
            'vistas_total'    => $agencia->vehiculos()->sum('vistas'),
            'leads_total'     => Lead::whereIn('vehiculo_id', $vehiculoIds)->count(),
            'leads_ganados'   => Lead::whereIn('vehiculo_id', $vehiculoIds)->where('status', 'cerrado_ganado')->count(),
            'vehiculos_activos' => $agencia->vehiculos()->where('status', 'disponible')->count(),
        ];

        return view('agencia.estadisticas.index', compact(
            'agencia', 'stats', 'topVistas', 'topLeads', 'leadsPorTipo', 'leadsPorMes'
        ));
    }
}
