<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Mail\PagoFallidoMail;
use App\Mail\RenovacionCobradaMail;
use App\Mail\RenovacionProximaMail;
use App\Mail\SuscripcionActivadaMail;
use App\Mail\SuscripcionCanceladaMail;
use App\Models\Agencia;
use App\Models\Pago;
use App\Models\Suscripcion;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WebhookController extends Controller
{
    public function __construct(private StripeService $stripe) {}

    public function handle(Request $request): Response
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature', '');

        // Si no hay webhook secret configurado, aceptar en desarrollo
        if (! config('services.stripe.webhook_secret')) {
            Log::warning('[Stripe] Webhook recibido sin secret configurado.');
            return response('ok', 200);
        }

        try {
            $event = $this->stripe->construirEvento($payload, $sigHeader);
        } catch (\Exception $e) {
            Log::error('[Stripe] Webhook inválido: ' . $e->getMessage());
            return response('Webhook inválido.', 400);
        }

        match ($event->type) {
            'checkout.session.completed'      => $this->onCheckoutCompleted($event->data->object),
            'invoice.paid'                    => $this->onInvoicePaid($event->data->object),
            'invoice.payment_failed'          => $this->onInvoiceFailed($event->data->object),
            'invoice.upcoming'                => $this->onInvoiceUpcoming($event->data->object),
            'customer.subscription.deleted'   => $this->onSubscriptionDeleted($event->data->object),
            'customer.subscription.updated'   => $this->onSubscriptionUpdated($event->data->object),
            default                           => null,
        };

        return response('ok', 200);
    }

    // ─── Handlers ────────────────────────────────────────────────────────────────

    private function onCheckoutCompleted(object $session): void
    {
        $suscripcion = Suscripcion::where('stripe_session_id', $session->id)->first();

        if (! $suscripcion) {
            Log::warning('[Stripe] checkout.session.completed sin suscripción local.', ['session' => $session->id]);
            return;
        }

        $agencia = $suscripcion->agencia;

        // Cancelar anteriores activas
        $agencia->suscripciones()
            ->where('id', '!=', $suscripcion->id)
            ->where('status', 'activa')
            ->update(['status' => 'cancelada']);

        $suscripcion->update([
            'status'                 => 'activa',
            'fecha_inicio'           => now(),
            'fecha_vencimiento'      => now()->addMonths($suscripcion->plan->meses),
            'precio_pagado'          => $session->amount_total / 100,
            'stripe_subscription_id' => $session->subscription ?? null,
        ]);

        // Activar la agencia
        $agencia->update(['activo' => true]);

        // Registrar pago
        if ($session->payment_intent) {
            Pago::create([
                'suscripcion_id'          => $suscripcion->id,
                'monto'                   => $session->amount_total / 100,
                'metodo'                  => 'stripe',
                'stripe_payment_intent_id'=> $session->payment_intent,
                'status'                  => 'completado',
                'fecha_pago'              => now(),
            ]);
        }

        if ($email = $agencia->email) {
            Mail::to($email)->send(new SuscripcionActivadaMail($suscripcion->fresh('plan', 'agencia')));
        }

        Log::info("[Stripe] Suscripción activada para agencia #{$agencia->id}");
    }

    private function onInvoicePaid(object $invoice): void
    {
        if (! $invoice->subscription) return;

        $suscripcion = Suscripcion::where('stripe_subscription_id', $invoice->subscription)->first();

        if (! $suscripcion) return;

        // Extender según la duración del plan (3, 6 o 12 meses)
        $suscripcion->update([
            'status'            => 'activa',
            'fecha_vencimiento' => now()->addMonths($suscripcion->plan->meses),
            'precio_pagado'     => $invoice->amount_paid / 100,
        ]);

        Pago::create([
            'suscripcion_id'           => $suscripcion->id,
            'monto'                    => $invoice->amount_paid / 100,
            'metodo'                   => 'stripe',
            'stripe_payment_intent_id' => $invoice->payment_intent ?? null,
            'status'                   => 'completado',
            'fecha_pago'               => now(),
        ]);

        // Solo notificar en renovaciones (no en el primer pago, que ya lo cubre checkout.session.completed)
        if (($invoice->billing_reason ?? '') === 'subscription_cycle') {
            if ($email = $suscripcion->agencia?->email) {
                Mail::to($email)->send(new RenovacionCobradaMail($suscripcion->fresh('plan', 'agencia'), $invoice->amount_paid / 100));
            }
        }

        Log::info("[Stripe] invoice.paid — suscripción #{$suscripcion->id} extendida.");
    }

    private function onInvoiceFailed(object $invoice): void
    {
        if (! $invoice->subscription) return;

        $suscripcion = Suscripcion::where('stripe_subscription_id', $invoice->subscription)->first();

        if (! $suscripcion) return;

        Pago::create([
            'suscripcion_id'           => $suscripcion->id,
            'monto'                    => $invoice->amount_due / 100,
            'metodo'                   => 'stripe',
            'stripe_payment_intent_id' => $invoice->payment_intent ?? null,
            'status'                   => 'fallido',
            'fecha_pago'               => now(),
        ]);

        if ($email = $suscripcion->agencia?->email) {
            Mail::to($email)->send(new PagoFallidoMail($suscripcion->load('plan', 'agencia'), $invoice->amount_due / 100));
        }

        Log::warning("[Stripe] invoice.payment_failed — suscripción #{$suscripcion->id}");
    }

    private function onInvoiceUpcoming(object $invoice): void
    {
        if (! $invoice->subscription) return;

        $suscripcion = Suscripcion::with('plan', 'agencia.users')
            ->where('stripe_subscription_id', $invoice->subscription)
            ->where('status', 'activa')
            ->first();

        if (! $suscripcion) return;

        $monto          = $invoice->amount_due / 100;
        $fechaRenovacion = now()->setTimestamp($invoice->period_end)->format('d/m/Y');
        $email          = $suscripcion->agencia?->email;

        if (! $email) return;

        Mail::to($email)->send(new RenovacionProximaMail($suscripcion, $monto, $fechaRenovacion));

        Log::info("[Stripe] invoice.upcoming — aviso de renovación enviado a {$email} para suscripción #{$suscripcion->id}");
    }

    private function onSubscriptionDeleted(object $subscription): void
    {
        $suscripcion = Suscripcion::where('stripe_subscription_id', $subscription->id)->first();

        if (! $suscripcion) return;

        $suscripcion->update(['status' => 'cancelada']);
        $suscripcion->agencia->update(['activo' => false]);

        if ($email = $suscripcion->agencia?->email) {
            Mail::to($email)->send(new SuscripcionCanceladaMail($suscripcion->load('plan', 'agencia')));
        }

        Log::info("[Stripe] Suscripción #{$suscripcion->id} cancelada desde Stripe.");
    }

    private function onSubscriptionUpdated(object $subscription): void
    {
        $suscripcion = Suscripcion::where('stripe_subscription_id', $subscription->id)->first();

        if (! $suscripcion) return;

        // Sincronizar status si Stripe la pausó o canceló
        if (in_array($subscription->status, ['canceled', 'unpaid', 'past_due'])) {
            $suscripcion->update(['status' => 'vencida']);
        }
    }
}
