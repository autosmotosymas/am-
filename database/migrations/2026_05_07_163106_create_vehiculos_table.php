<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agencia_id')->constrained('agencias')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users'); // capturista
            $table->enum('tipo', ['auto', 'moto', 'camioneta', 'camion', 'otro'])->default('auto');
            $table->string('marca');
            $table->string('modelo');
            $table->unsignedSmallInteger('anio');
            $table->string('version')->nullable();
            $table->decimal('precio', 12, 2);
            $table->boolean('precio_negociable')->default(false);
            $table->unsignedInteger('kilometraje')->default(0);
            $table->enum('transmision', ['manual', 'automatica', 'cvt'])->default('automatica');
            $table->enum('combustible', ['gasolina', 'diesel', 'electrico', 'hibrido', 'gas'])->default('gasolina');
            $table->string('color');
            $table->unsignedTinyInteger('puertas')->nullable();
            $table->unsignedTinyInteger('cilindros')->nullable();
            $table->string('motor')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('slug')->unique();
            $table->enum('status', ['disponible', 'apartado', 'vendido', 'inactivo'])->default('disponible');
            $table->boolean('destacado')->default(false);
            $table->unsignedInteger('vistas')->default(0);
            $table->string('ciudad')->nullable();
            $table->string('estado')->nullable();
            $table->string('vin')->nullable();
            $table->string('placas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
