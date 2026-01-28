<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('participante_carton_prueba', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participante_prueba_id')->constrained('participantes_prueba')->onDelete('cascade');
            $table->foreignId('jugada_id')->constrained('jugadas')->onDelete('cascade');
            $table->foreignId('carton_id')->constrained('cartons')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['participante_prueba_id', 'jugada_id', 'carton_id'], 'pp_jugada_carton_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('participante_carton_prueba');
    }
};
