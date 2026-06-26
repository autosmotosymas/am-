<?php

namespace App\Services;

use App\Models\Agencia;
use App\Models\Plan;
use Stripe\StripeClient;

class StripeService
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /**
     * Obtener o crear un Customer de Stripe para la agencia.
     */
    public function obtenerOCrearCustomer(Agencia $agencia): string
    {
        // Si ya tiene customer_id en la suscripción activa, reutilizarlo
        $sus = $agencia->suscripciones()->whereNotNull('stripe_customer_id')->latest()->first();

        if ($sus?->stripe_customer_id) {
            return $sus->stripe_customer_id;
        }

        $customer = $this->stripe->customers->create([
            'name'     => $agencia->nombre,
            'email'    => $agencia->email,
            'metadata' => ['agencia_id' => $agencia->id],
        ]);

        return $customer->id;
    }

    /**
     * Crear una Checkout Session de Stripe para una suscripción recurrente.
     * Si el plan tiene stripe_price_id → modo subscription (recurrente).
     * Si no → modo payment (único, manual).
     */
    public function crearCheckoutSession(Agencia $agencia, Plan $plan, string $customerId, ?string $successUrl = null, ?string $cancelUrl = null): \Stripe\Checkout\Session
    {
        $params = [
            'customer'           => $customerId,
            'success_url'        => ($successUrl ?? route('agencia.suscripcion.exito')) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'         => $cancelUrl ?? route('agencia.suscripcion.index'),
            'allow_promotion_codes' => true,
            'metadata'           => [
                'agencia_id' => $agencia->id,
                'plan_id'    => $plan->id,
            ],
        ];

        if ($plan->tieneStripe()) {
            // Suscripción recurrente
            $params['mode']        = 'subscription';
            $params['line_items']  = [[
                'price'    => $plan->stripe_price_id,
                'quantity' => 1,
            ]];
            $params['subscription_data'] = [
                'metadata' => [
                    'agencia_id' => $agencia->id,
                    'plan_id'    => $plan->id,
                ],
            ];
        } else {
            // Pago único (cuando no hay Price de Stripe configurado aún)
            $params['mode']       = 'payment';
            $params['line_items'] = [[
                'quantity'   => 1,
                'price_data' => [
                    'currency'     => 'mxn',
                    'unit_amount'  => (int) ($plan->precio_mensual * 100),
                    'product_data' => [
                        'name'        => "AMM — {$plan->nombre}",
                        'description' => "Suscripción mensual al plan {$plan->nombre} de AutosMotosYMás",
                    ],
                ],
            ]];
        }

        return $this->stripe->checkout->sessions->create($params);
    }

    /**
     * Recuperar una Checkout Session por su ID.
     */
    public function obtenerSession(string $sessionId): \Stripe\Checkout\Session
    {
        return $this->stripe->checkout->sessions->retrieve($sessionId, [
            'expand' => ['subscription', 'payment_intent'],
        ]);
    }

    /**
     * Verificar y construir un evento de webhook.
     */
    public function construirEvento(string $payload, string $sigHeader): \Stripe\Event
    {
        return \Stripe\Webhook::constructEvent(
            $payload,
            $sigHeader,
            config('services.stripe.webhook_secret')
        );
    }

    /**
     * Cancelar una suscripción en Stripe al final del período.
     */
    public function cancelarSuscripcion(string $stripeSubscriptionId): void
    {
        $this->stripe->subscriptions->update($stripeSubscriptionId, [
            'cancel_at_period_end' => true,
        ]);
    }
}
