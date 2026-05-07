<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suscripcion_id')->constrained('suscripciones')->cascadeOnDelete();
            $table->decimal('monto', 10, 2);
            $table->string('metodo'); // stripe, oxxo, transferencia, etc.
            $table->string('referencia')->nullable();
            $table->enum('status', ['pendiente', 'completado', 'fallido', 'reembolsado'])->default('pendiente');
            $table->timestamp('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
