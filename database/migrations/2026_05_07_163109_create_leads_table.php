<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nombre');
            $table->string('telefono');
            $table->string('email')->nullable();
            $table->text('mensaje')->nullable();
            $table->enum('tipo', ['llamada', 'whatsapp', 'email', 'visita'])->default('whatsapp');
            $table->enum('status', ['nuevo', 'en_proceso', 'cerrado_ganado', 'cerrado_perdido'])->default('nuevo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
