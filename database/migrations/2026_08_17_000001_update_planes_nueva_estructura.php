<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planes', function (Blueprint $table) {
            // Agregar nuevas columnas
            $table->enum('duracion', ['trimestral', 'semestral', 'anual'])
                  ->after('nombre')
                  ->default('trimestral');
            $table->unsignedTinyInteger('meses')->after('duracion')->default(3);
            $table->unsignedTinyInteger('videos')->after('vehiculos_destacados')->default(5);

            // Renombrar precio_mensual → precio (ahora es precio total del período)
            $table->renameColumn('precio_mensual', 'precio');
        });
    }

    public function down(): void
    {
        Schema::table('planes', function (Blueprint $table) {
            $table->dropColumn(['duracion', 'meses', 'videos']);
            $table->renameColumn('precio', 'precio_mensual');
        });
    }
};
