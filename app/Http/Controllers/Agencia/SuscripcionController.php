<?php

namespace App\Http\Controllers\Agencia;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Suscripcion;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuscripcionController extends Controller
{
    public function __construct(private StripeService $stripe) {}

    /**
     * Mostrar planes disponibles para suscribirse.
     */
    public function index(): View
    {
        $planes      = Plan::where('activo', true)->orderBy('precio_mensual')->get();
        $agencia     = auth()->user()->agencia;
        $suscripcion = $agencia?->suscripcionActiva;

        return view('agencia.suscripcion.index', compact('planes', 'suscripcion', 'agencia'));
    }

    /**
     * Crear la Checkout Session y redirigir a Stripe.
     */
    public function checkout(Request $request): RedirectResponse
    {
        $request->validate(['plan_id' => ['required', 'exists:planes,id']]);

        $agencia = auth()->user()->agencia;
        $plan    = Plan::findOrFail($request->plan_id);

        try {
            $customerId = $this->stripe->obtenerOCrearCustomer($agencia);
            $session    = $this->stripe->crearCheckoutSession($agencia, $plan, $customerId);

            // Registrar la suscripción como pendiente mientras se paga
            Suscripcion::create([
                'agencia_id'        => $agencia->id,
                'plan_id'           => $plan->id,
                'status'            => 'prueba',
                'fecha_inicio'      => now(),
                'fecha_vencimiento' => now()->addMonth(),
                'precio_pagado'     => 0,
                'stripe_session_id' => $session->id,
                'stripe_customer_id'=> $customerId,
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo iniciar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Callback de éxito — Stripe redirige aquí tras pago exitoso.
     */
    public function exito(Request $request): View|RedirectResponse
    {
        $sessionId = $request->get('session_id');

        if (! $sessionId) {
            return redirect()->route('agencia.suscripcion.index');
        }

        try {
            $session = $this->stripe->obtenerSession($sessionId);

            // Buscar la suscripción pendiente por session_id
            $suscripcion = Suscripcion::where('stripe_session_id', $sessionId)->first();

            if ($suscripcion && $session->payment_status === 'paid') {
                $agencia = $suscripcion->agencia;

                // Cancelar suscripciones activas previas
                $agencia->suscripciones()
                    ->where('id', '!=', $suscripcion->id)
                    ->where('status', 'activa')
                    ->update(['status' => 'cancelada']);

                // Activar esta suscripción
                $suscripcion->update([
                    'status'                 => 'activa',
                    'fecha_inicio'           => now(),
                    'fecha_vencimiento'      => now()->addMonth(),
                    'precio_pagado'          => $session->amount_total / 100,
                    'stripe_subscription_id' => $session->subscription?->id,
                ]);
            }
        } catch (\Exception $e) {
            // El webhook es el mecanismo principal; esto es solo UX
        }

        return view('agencia.suscripcion.exito');
    }
}
