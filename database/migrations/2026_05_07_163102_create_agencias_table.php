<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('email')->unique();
            $table->string('telefono');
            $table->string('whatsapp')->nullable();
            $table->string('direccion')->nullable();
            $table->string('ciudad');
            $table->string('estado');
            $table->string('cp', 10)->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('calificacion', 3, 2)->nullable();
            $table->unsignedInteger('total_resenas')->default(0);
            $table->boolean('activo')->default(true);
            $table->boolean('verificada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agencias');
    }
};
