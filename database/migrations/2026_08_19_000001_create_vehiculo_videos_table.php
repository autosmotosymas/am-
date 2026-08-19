<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculo_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained()->cascadeOnDelete();
            $table->string('youtube_url');
            $table->unsignedTinyInteger('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculo_videos');
    }
};
