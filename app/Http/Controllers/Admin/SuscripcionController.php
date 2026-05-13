<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agencia;
use App\Models\Plan;
use App\Models\Suscripcion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuscripcionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Suscripcion::with(['agencia', 'plan'])->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $suscripciones = $query->paginate(20)->withQueryString();

        $conteos = [
            'activa'   => Suscripcion::where('status', 'activa')->count(),
            'prueba'   => Suscripcion::where('status', 'prueba')->count(),
            'vencida'  => Suscripcion::where('status', 'vencida')->count(),
            'cancelada'=> Suscripcion::where('status', 'cancelada')->count(),
        ];

        return view('admin.suscripciones.index', compact('suscripciones', 'conteos'));
    }

    public function create(): View
    {
        $agencias = Agencia::where('activo', true)->orderBy('nombre')->get();
        $planes   = Plan::where('activo', true)->get();

        return view('admin.suscripciones.create', compact('agencias', 'planes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'agencia_id'        => ['required', 'exists:agencias,id'],
            'plan_id'           => ['required', 'exists:planes,id'],
            'meses'             => ['required', 'integer', 'min:1', 'max:24'],
            'precio_pagado'     => ['nullable', 'numeric', 'min:0'],
        ]);

        $agencia = Agencia::find($data['agencia_id']);

        // Cancelar activa previa
        $agencia->suscripciones()
            ->where('status', 'activa')
            ->update(['status' => 'cancelada']);

        Suscripcion::create([
            'agencia_id'        => $data['agencia_id'],
            'plan_id'           => $data['plan_id'],
            'status'            => 'activa',
            'fecha_inicio'      => now(),
            'fecha_vencimiento' => now()->addMonths($data['meses']),
            'precio_pagado'     => $data['precio_pagado'] ?? 0,
        ]);

        return redirect()->route('admin.suscripciones.index')
            ->with('ok', "Suscripción activada para {$agencia->nombre}.");
    }

    public function show(Suscripcion $suscripcione): View
    {
        $suscripcione->load(['agencia', 'plan']);

        return view('admin.suscripciones.show', ['sus' => $suscripcione]);
    }

    public function cancelar(Suscripcion $suscripcione): RedirectResponse
    {
        $suscripcione->update(['status' => 'cancelada']);

        return back()->with('ok', 'Suscripción cancelada.');
    }

    public function edit(Suscripcion $suscripcione): View
    {
        $planes = Plan::where('activo', true)->get();

        return view('admin.suscripciones.edit', [
            'sus'    => $suscripcione->load(['agencia', 'plan']),
            'planes' => $planes,
        ]);
    }

    public function update(Request $request, Suscripcion $suscripcione): RedirectResponse
    {
        $data = $request->validate([
            'plan_id'           => ['required', 'exists:planes,id'],
            'status'            => ['required', 'in:prueba,activa,vencida,cancelada'],
            'fecha_vencimiento' => ['required', 'date'],
            'precio_pagado'     => ['nullable', 'numeric', 'min:0'],
        ]);

        $suscripcione->update($data);

        return redirect()->route('admin.suscripciones.index')
            ->with('ok', 'Suscripción actualizada.');
    }

    public function destroy(Suscripcion $suscripcione): RedirectResponse
    {
        $suscripcione->delete();

        return redirect()->route('admin.suscripciones.index')
            ->with('ok', 'Suscripción eliminada.');
    }
}
