<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('agencia_id')->nullable()->after('id')->constrained('agencias')->nullOnDelete();
            $table->string('avatar')->nullable()->after('name');
            $table->string('telefono', 20)->nullable()->after('avatar');
            $table->enum('tema', ['light', 'dark'])->default('dark')->after('telefono');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['agencia_id']);
            $table->dropColumn(['agencia_id', 'avatar', 'telefono', 'tema']);
        });
    }
};
