<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nombre');
            $table->decimal('precio_mensual', 10, 2);
            $table->unsignedSmallInteger('max_vehiculos');
            $table->unsignedTinyInteger('max_fotos_por_vehiculo')->default(10);
            $table->boolean('incluye_certificacion')->default(false);
            $table->unsignedTinyInteger('vehiculos_destacados')->default(0);
            $table->boolean('badge_premium')->default(false);
            $table->json('features')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planes');
    }
};
