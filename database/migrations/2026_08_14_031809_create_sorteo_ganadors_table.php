<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sorteo_ganadors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sorteo_id')->nullable();
            $table->unsignedBigInteger('jugada_id')->nullable();
            $table->string('tipo_premio'); // 'linea' o 'bingo'
            $table->string('carton_numero');
            $table->string('nombre_jugador')->nullable();
            $table->integer('bolilla_ganadora');
            $table->timestamps();
            
            $table->index('sorteo_id');
            $table->index('jugada_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sorteo_ganadors');
    }
};
