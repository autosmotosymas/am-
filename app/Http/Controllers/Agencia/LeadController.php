<?php

namespace App\Http\Controllers\Agencia;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $agencia = auth()->user()->agencia;
        abort_unless($agencia, 403);

        $vehiculoIds = $agencia->vehiculos()->pluck('id');

        $query = Lead::with('vehiculo')
            ->whereIn('vehiculo_id', $vehiculoIds);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $leads = $query->latest()->paginate(20)->withQueryString();

        $conteos = [
            'nuevo'          => Lead::whereIn('vehiculo_id', $vehiculoIds)->where('status', 'nuevo')->count(),
            'en_proceso'     => Lead::whereIn('vehiculo_id', $vehiculoIds)->where('status', 'en_proceso')->count(),
            'cerrado_ganado' => Lead::whereIn('vehiculo_id', $vehiculoIds)->where('status', 'cerrado_ganado')->count(),
            'cerrado_perdido'=> Lead::whereIn('vehiculo_id', $vehiculoIds)->where('status', 'cerrado_perdido')->count(),
        ];

        return view('agencia.leads.index', compact('leads', 'conteos'));
    }

    public function marcarLeido(Lead $lead): RedirectResponse
    {
        $agencia = auth()->user()->agencia;
        abort_unless(
            $agencia && $lead->vehiculo?->agencia_id === $agencia->id,
            403
        );

        $nuevoStatus = match($lead->status) {
            'nuevo'          => 'en_proceso',
            'en_proceso'     => 'cerrado_ganado',
            default          => $lead->status,
        };

        $lead->update(['status' => $nuevoStatus]);

        return back()->with('ok', 'Estado actualizado.');
    }
}
