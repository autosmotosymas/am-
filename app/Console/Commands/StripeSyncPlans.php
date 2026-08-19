<?php

namespace App\Console\Commands;

use App\Models\Plan;
use Illuminate\Console\Command;
use Stripe\StripeClient;

class StripeSyncPlans extends Command
{
    protected $signature   = 'stripe:sync-plans {--force : Recrear precios aunque ya existan}';
    protected $description = 'Crea Products y Prices en Stripe para cada plan activo y guarda los IDs en la BD';

    public function handle(): int
    {
        $secret = config('services.stripe.secret');

        if (! $secret || str_ends_with($secret, '_')) {
            $this->error('STRIPE_SECRET no está configurado en el .env');
            return self::FAILURE;
        }

        $stripe = new StripeClient($secret);
        $planes = Plan::where('activo', true)
            ->orderByRaw("FIELD(nombre, 'Base', 'Medio', 'Premium')")
            ->orderByRaw("FIELD(duracion, 'trimestral', 'semestral', 'anual')")
            ->get();

        if ($planes->isEmpty()) {
            $this->warn('No hay planes activos. Ejecuta primero: php artisan db:seed --class=PlanesSeeder');
            return self::FAILURE;
        }

        $this->info("Sincronizando {$planes->count()} plan(es) con Stripe...");
        $this->newLine();

        foreach ($planes as $plan) {
            if ($plan->stripe_price_id && ! $this->option('force')) {
                $this->line("  → <comment>{$plan->nombre} {$plan->duracion}</comment>: ya tiene Price ID ({$plan->stripe_price_id}), omitido.");
                continue;
            }

            try {
                // 1. Crear el Product en Stripe
                $product = $stripe->products->create([
                    'name'        => "AMM — {$plan->nombre} ({$plan->duracion_label})",
                    'description' => implode(', ', is_array($plan->features) ? array_slice($plan->features, 0, 3) : []),
                    'metadata'    => [
                        'plan_id'   => $plan->id,
                        'plan_slug' => $plan->slug,
                    ],
                ]);

                // 2. Crear el Price recurrente según la duración del plan
                [$interval, $intervalCount] = match ($plan->duracion) {
                    'trimestral' => ['month', 3],
                    'semestral'  => ['month', 6],
                    'anual'      => ['year',  1],
                };

                $price = $stripe->prices->create([
                    'product'      => $product->id,
                    'currency'     => 'mxn',
                    'unit_amount'  => (int) ($plan->precio * 100),
                    'recurring'    => [
                        'interval'       => $interval,
                        'interval_count' => $intervalCount,
                    ],
                    'nickname'     => "{$plan->nombre} — {$plan->duracion}",
                    'metadata'     => [
                        'plan_id'   => $plan->id,
                        'plan_slug' => $plan->slug,
                        'duracion'  => $plan->duracion,
                        'meses'     => $plan->meses,
                    ],
                ]);

                // 3. Guardar en BD
                $plan->update(['stripe_price_id' => $price->id]);

                $this->line("  ✓ <info>{$plan->nombre} — {$plan->duracion}</info>");
                $this->line("      Product: {$product->id}");
                $this->line("      Price:   {$price->id}  (MXN \${$plan->precio} / {$plan->duracion_label})");

            } catch (\Exception $e) {
                $this->error("  ✗ {$plan->nombre} {$plan->duracion}: " . $e->getMessage());
            }

            $this->newLine();
        }

        $this->info('Listo. Los Price IDs quedaron guardados en la tabla planes.');
        $this->line('Puedes verificarlos en: <href=https://dashboard.stripe.com/test/products>dashboard.stripe.com/test/products</>');

        return self::SUCCESS;
    }
}
