<?php

namespace App\Http\Controllers\Vendedor;

use App\Http\Controllers\Controller;
use App\Models\Agencia;
use App\Models\Plan;
use App\Models\Suscripcion;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            'email'     => ['required', 'email', 'unique:agencias,email', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8'],
            'telefono'  => ['required', 'string', 'max:20'],
            'ciudad'    => ['required', 'string', 'max:60'],
            'estado'    => ['required', 'string', 'max:60'],
            'whatsapp'  => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:200'],
        ]);

        $agencia = Agencia::create([
            'nombre'     => $data['nombre'],
            'email'      => $data['email'],
            'telefono'   => $data['telefono'],
            'ciudad'     => $data['ciudad'],
            'estado'     => $data['estado'],
            'whatsapp'   => $data['whatsapp'] ?? null,
            'direccion'  => $data['direccion'] ?? null,
            'vendedor_id'=> auth()->id(),
            'activo'     => false,
            'verificada' => false,
        ]);

        // Crear usuario de acceso para la agencia
        $usuario = User::create([
            'name'       => $data['nombre'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'agencia_id' => $agencia->id,
        ]);
        $usuario->assignRole('agencia');

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

        $usuario = $agencia->usuario;

        $data = $request->validate([
            'whatsapp'    => ['nullable', 'string', 'max:20'],
            'direccion'   => ['nullable', 'string', 'max:200'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'logo'        => ['nullable', 'image', 'max:2048'],
            'banner'      => ['nullable', 'image', 'max:5120'],
            'email'       => ['nullable', 'email', 'unique:agencias,email,' . $agencia->id,
                              $usuario ? 'unique:users,email,' . $usuario->id : 'unique:users,email'],
            'password'    => ['nullable', 'string', 'min:8'],
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

        // Actualizar datos de la agencia
        $agencia->update(array_filter([
            'whatsapp'    => $data['whatsapp'] ?? $agencia->whatsapp,
            'direccion'   => $data['direccion'] ?? $agencia->direccion,
            'descripcion' => $data['descripcion'] ?? $agencia->descripcion,
            'logo'        => $data['logo'] ?? $agencia->logo,
            'banner'      => $data['banner'] ?? $agencia->banner,
            'email'       => filled($data['email'] ?? null) ? $data['email'] : $agencia->email,
        ], fn($v) => $v !== null));

        // Actualizar o crear el usuario de acceso
        $email    = filled($data['email'] ?? null)    ? $data['email']    : null;
        $password = filled($data['password'] ?? null) ? $data['password'] : null;

        if ($usuario) {
            $userUpdate = [];
            if ($email)    { $userUpdate['email']    = $email; }
            if ($password) { $userUpdate['password'] = Hash::make($password); }
            if ($userUpdate) { $usuario->update($userUpdate); }
        } elseif ($email && $password) {
            // Agencia sin usuario aún — crearlo
            $nuevo = User::create([
                'name'       => $agencia->nombre,
                'email'      => $email,
                'password'   => Hash::make($password),
                'agencia_id' => $agencia->id,
            ]);
            $nuevo->assignRole('agencia');
        }

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
                'status'             => 'prueba',
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
