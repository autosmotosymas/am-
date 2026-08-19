<?php

namespace App\Http\Controllers\Agencia;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Suscripcion;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class SuscripcionController extends Controller
{
    public function __construct(private StripeService $stripe) {}

    /**
     * Mostrar planes disponibles con toggle de duración.
     */
    public function index(): View
    {
        $planes = Plan::where('activo', true)
            ->orderByRaw("FIELD(nombre, 'Base', 'Medio', 'Premium')")
            ->orderByRaw("FIELD(duracion, 'trimestral', 'semestral', 'anual')")
            ->get();

        $agencia     = auth()->user()->agencia;
        $suscripcion = $agencia?->suscripcionActiva;

        // Agrupar para Alpine.js: { base: { trimestral: {...}, semestral: {...}, anual: {...} }, ... }
        $planesJson = [];
        foreach ($planes as $plan) {
            $grupo = strtolower($plan->nombre);
            $planesJson[$grupo][$plan->duracion] = [
                'id'            => $plan->id,
                'precio'        => (int) $plan->precio,
                'max_vehiculos' => $plan->max_vehiculos,
                'destacados'    => $plan->vehiculos_destacados,
                'videos'        => $plan->videos,
                'fotos'         => $plan->max_fotos_por_vehiculo,
                'nombre'        => $plan->nombre,
                'duracion'      => $plan->duracion,
                'meses'         => $plan->meses,
                'features'      => $plan->features,
            ];
        }

        $planPrueba = Plan::where('slug', 'prueba-10pesos')->first();

        return view('agencia.suscripcion.index', compact('planesJson', 'suscripcion', 'agencia', 'planPrueba'));
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
                'fecha_vencimiento' => now()->addMonths($plan->meses),
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
     * Cancelar la renovación automática al final del período actual.
     */
    public function cancelar(): RedirectResponse
    {
        $agencia     = auth()->user()->agencia;
        $suscripcion = $agencia?->suscripcionActiva;

        if (! $suscripcion?->stripe_subscription_id) {
            return back()->with('error', 'No tienes una suscripción activa con renovación automática.');
        }

        try {
            $this->stripe->cancelarSuscripcion($suscripcion->stripe_subscription_id);

            return back()->with('ok', 'Renovación cancelada. Conservas el acceso hasta el ' . $suscripcion->fecha_vencimiento->format('d/m/Y') . '.');
        } catch (\Exception $e) {
            Log::error('[Stripe] Error al cancelar suscripción: ' . $e->getMessage());
            return back()->with('error', 'No se pudo cancelar la renovación. Intenta más tarde.');
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
            $session     = $this->stripe->obtenerSession($sessionId);
            $suscripcion = Suscripcion::where('stripe_session_id', $sessionId)->first();

            if ($suscripcion && $session->payment_status === 'paid') {
                $agencia = $suscripcion->agencia;

                // Cancelar suscripciones activas previas
                $agencia->suscripciones()
                    ->where('id', '!=', $suscripcion->id)
                    ->where('status', 'activa')
                    ->update(['status' => 'cancelada']);

                // Activar esta suscripción con la duración correcta del plan
                $suscripcion->update([
                    'status'                 => 'activa',
                    'fecha_inicio'           => now(),
                    'fecha_vencimiento'      => now()->addMonths($suscripcion->plan->meses),
                    'precio_pagado'          => $session->amount_total / 100,
                    'stripe_subscription_id' => $session->subscription?->id,
                ]);
            }
        } catch (\Exception $e) {
            // El webhook es el mecanismo principal; esto es solo UX fallback
        }

        return view('agencia.suscripcion.exito');
    }
}
