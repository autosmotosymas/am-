<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agencia_id')->constrained('agencias')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('planes');
            $table->enum('status', ['prueba', 'activa', 'vencida', 'cancelada'])->default('prueba');
            $table->date('fecha_inicio');
            $table->date('fecha_vencimiento');
            $table->decimal('precio_pagado', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suscripciones');
    }
};
