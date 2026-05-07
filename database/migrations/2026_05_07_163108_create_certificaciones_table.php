<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete();
            $table->foreignId('verificador_id')->constrained('verificadores');
            $table->date('fecha_inspeccion');
            $table->enum('resultado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->unsignedTinyInteger('puntaje')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('reporte_pdf')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificaciones');
    }
};
