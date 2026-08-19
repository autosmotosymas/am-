<?php

namespace App\Http\Controllers\Agencia;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EstadisticasController extends Controller
{
    public function index(Request $request): View
    {
        $agencia = auth()->user()->agencia;
        abort_unless($agencia, 403);

        $periodos = ['30d' => '30 días', '90d' => '90 días', '6m' => '6 meses', '12m' => '12 meses'];
        $periodo  = $request->get('periodo', '6m');
        if (! array_key_exists($periodo, $periodos)) {
            $periodo = '6m';
        }

        $desde = match($periodo) {
            '30d' => now()->subDays(30),
            '90d' => now()->subDays(90),
            '12m' => now()->subMonths(12),
            default => now()->subMonths(6),
        };

        $vehiculoIds = $agencia->vehiculos()->pluck('id');

        // Top 5 vehículos por vistas
        $topVistas = $agencia->vehiculos()
            ->with('fotoPrincipal')
            ->orderByDesc('vistas')
            ->limit(5)
            ->get();

        // Top 5 vehículos por leads
        $topLeads = $agencia->vehiculos()
            ->withCount(['leads' => fn($q) => $q->where('created_at', '>=', $desde)])
            ->orderByDesc('leads_count')
            ->limit(5)
            ->get();

        // Leads por tipo de contacto (en el período)
        $leadsPorTipo = Lead::whereIn('vehiculo_id', $vehiculoIds)
            ->where('created_at', '>=', $desde)
            ->selectRaw('tipo, count(*) as total')
            ->groupBy('tipo')
            ->pluck('total', 'tipo');

        // Leads por mes (en el período)
        $leadsPorMes = Lead::whereIn('vehiculo_id', $vehiculoIds)
            ->where('created_at', '>=', $desde)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes, count(*) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        $stats = [
            'vistas_total'      => $agencia->vehiculos()->sum('vistas'),
            'leads_total'       => Lead::whereIn('vehiculo_id', $vehiculoIds)->where('created_at', '>=', $desde)->count(),
            'leads_ganados'     => Lead::whereIn('vehiculo_id', $vehiculoIds)->where('created_at', '>=', $desde)->where('status', 'cerrado_ganado')->count(),
            'vehiculos_activos' => $agencia->vehiculos()->where('status', 'disponible')->count(),
        ];

        // Historial de suscripciones
        $suscripciones = $agencia->suscripciones()->with('plan')->latest()->get();

        return view('agencia.estadisticas.index', compact(
            'agencia', 'stats', 'topVistas', 'topLeads', 'leadsPorTipo', 'leadsPorMes',
            'periodos', 'periodo', 'suscripciones'
        ));
    }
}
