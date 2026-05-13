<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agencia;
use App\Models\Plan;
use App\Models\Suscripcion;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgenciaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Agencia::with('suscripcionActiva.plan')
            ->withCount('vehiculos');

        if ($q = $request->get('q')) {
            $query->where('nombre', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
        }

        if ($request->get('solo_sin_suscripcion')) {
            $query->whereDoesntHave('suscripciones', fn ($q) =>
                $q->where('status', 'activa')->where('fecha_vencimiento', '>=', now())
            );
        }

        $agencias = $query->latest()->paginate(20)->withQueryString();

        return view('admin.agencias.index', compact('agencias'));
    }

    public function show(Agencia $agencia): View
    {
        $agencia->load([
            'suscripciones.plan',
            'vehiculos' => fn ($q) => $q->with('fotoPrincipal')->latest()->limit(10),
        ]);

        $planes = Plan::where('activo', true)->get();
        $usuarios = User::where('agencia_id', $agencia->id)->get();

        return view('admin.agencias.show', compact('agencia', 'planes', 'usuarios'));
    }

    public function update(Request $request, Agencia $agencia): RedirectResponse
    {
        $data = $request->validate([
            'activo'     => ['boolean'],
            'verificada' => ['boolean'],
        ]);

        $agencia->update($data);

        return back()->with('ok', 'Agencia actualizada.');
    }

    // Activa/desactiva rápido desde la lista
    public function toggleActivo(Agencia $agencia): RedirectResponse
    {
        $agencia->update(['activo' => ! $agencia->activo]);

        return back()->with('ok', 'Estado actualizado.');
    }

    // Marca como verificada
    public function verificar(Agencia $agencia): RedirectResponse
    {
        $agencia->update(['verificada' => true]);

        return back()->with('ok', "{$agencia->nombre} marcada como verificada.");
    }

    // Crear suscripción manual
    public function suscribir(Request $request, Agencia $agencia): RedirectResponse
    {
        $data = $request->validate([
            'plan_id'   => ['required', 'exists:planes,id'],
            'meses'     => ['required', 'integer', 'min:1', 'max:24'],
        ]);

        // Cancelar suscripción activa previa
        $agencia->suscripciones()
            ->where('status', 'activa')
            ->update(['status' => 'cancelada']);

        Suscripcion::create([
            'agencia_id'        => $agencia->id,
            'plan_id'           => $data['plan_id'],
            'status'            => 'activa',
            'fecha_inicio'      => now(),
            'fecha_vencimiento' => now()->addMonths($data['meses']),
            'precio_pagado'     => 0,
        ]);

        return back()->with('ok', "Suscripción activada por {$data['meses']} mes(es).");
    }

    public function create(): View
    {
        return view('admin.agencias.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'    => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'unique:agencias,email'],
            'telefono'  => ['required', 'string', 'max:20'],
            'ciudad'    => ['required', 'string', 'max:60'],
            'estado'    => ['required', 'string', 'max:60'],
            'whatsapp'  => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:200'],
        ]);

        $agencia = Agencia::create($data);

        return redirect()->route('admin.agencias.show', $agencia)
            ->with('ok', 'Agencia creada.');
    }

    public function edit(Agencia $agencia): View
    {
        return view('admin.agencias.edit', compact('agencia'));
    }

    public function destroy(Agencia $agencia): RedirectResponse
    {
        $agencia->delete();

        return redirect()->route('admin.agencias.index')
            ->with('ok', 'Agencia eliminada.');
    }
}
