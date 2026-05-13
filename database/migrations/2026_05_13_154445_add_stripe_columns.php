<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Planes: ID del precio en Stripe (price_XXXX)
        Schema::table('planes', function (Blueprint $table) {
            $table->string('stripe_price_id')->nullable()->after('activo');
        });

        // Suscripciones: IDs de Stripe para vincular subscription y customer
        Schema::table('suscripciones', function (Blueprint $table) {
            $table->string('stripe_subscription_id')->nullable()->after('precio_pagado');
            $table->string('stripe_customer_id')->nullable()->after('stripe_subscription_id');
            $table->string('stripe_session_id')->nullable()->after('stripe_customer_id');
        });

        // Pagos: ID del PaymentIntent de Stripe
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('stripe_payment_intent_id')->nullable()->after('referencia');
        });
    }

    public function down(): void
    {
        Schema::table('planes', fn ($t) => $t->dropColumn('stripe_price_id'));
        Schema::table('suscripciones', fn ($t) => $t->dropColumn(['stripe_subscription_id', 'stripe_customer_id', 'stripe_session_id']));
        Schema::table('pagos', fn ($t) => $t->dropColumn('stripe_payment_intent_id'));
    }
};
