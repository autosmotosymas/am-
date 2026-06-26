<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use App\Models\Agencia;
use App\Models\Plan;
use App\Models\Suscripcion;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgenciaController extends Controller
{
    public function __construct(private StripeService $stripe) {}
    public function index(): View
    {
        $agencias = auth()->user()
                          ->agenciasVendidas()
                          ->withCount('vehiculos')
                          ->latest()
                          ->paginate(20);

        return view('vendedor.agencias.index', compact('agencias'));
    }

    public function create(): View
    {
        return view('vendedor.agencias.create');
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

        $data['vendedor_id'] = auth()->id();
        $data['activo']      = false;
        $data['verificada']  = false;

        $agencia = Agencia::create($data);

        return redirect()
            ->route('vendedor.agencias.show', $agencia)
            ->with('ok', "Agencia \"{$agencia->nombre}\" registrada. Completa el perfil y elige un plan.");
    }

    public function show(Agencia $agencia): View
    {
        abort_unless($agencia->vendedor_id === auth()->id(), 403);

        $planes      = Plan::where('activo', true)->orderBy('precio_mensual')->get();
        $suscripcion = $agencia->suscripcionActiva;
        $vehiculos   = $agencia->vehiculos()->count();

        return view('vendedor.agencias.show', compact('agencia', 'planes', 'suscripcion', 'vehiculos'));
    }

    public function update(Request $request, Agencia $agencia): RedirectResponse
    {
        abort_unless($agencia->vendedor_id === auth()->id(), 403);

        $data = $request->validate([
            'whatsapp'    => ['nullable', 'string', 'max:20'],
            'direccion'   => ['nullable', 'string', 'max:200'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'logo'        => ['nullable', 'image', 'max:2048'],
            'banner'      => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('agencias/logos', 'public');
        } else {
            unset($data['logo']);
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('agencias/banners', 'public');
        } else {
            unset($data['banner']);
        }

        $agencia->update($data);

        return back()->with('ok', 'Perfil actualizado.');
    }

    public function checkout(Request $request, Agencia $agencia): RedirectResponse
    {
        abort_unless($agencia->vendedor_id === auth()->id(), 403);

        $request->validate(['plan_id' => ['required', 'exists:planes,id']]);

        $plan = Plan::findOrFail($request->plan_id);

        try {
            $customerId = $this->stripe->obtenerOCrearCustomer($agencia);
            $session    = $this->stripe->crearCheckoutSession(
                $agencia,
                $plan,
                $customerId,
                route('vendedor.agencias.exito'),
                route('vendedor.agencias.show', $agencia)
            );

            Suscripcion::create([
                'agencia_id'         => $agencia->id,
                'plan_id'            => $plan->id,
                'status'             => 'pendiente',
                'fecha_inicio'       => now(),
                'fecha_vencimiento'  => now()->addMonth(),
                'precio_pagado'      => 0,
                'stripe_session_id'  => $session->id,
                'stripe_customer_id' => $customerId,
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo iniciar el pago: ' . $e->getMessage());
        }
    }

    public function exito(Request $request): View
    {
        return view('vendedor.agencias.exito');
    }
}
