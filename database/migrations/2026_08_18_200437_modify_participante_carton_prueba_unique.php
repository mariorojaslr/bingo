<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participante_carton_prueba', function (Blueprint $table) {
            // Crear constraint único global (participante, carton) para que no repita cartón nunca
            $table->unique(['participante_prueba_id', 'carton_id'], 'pp_carton_unique');
            // Eliminar el constraint anterior (participante, jugada, carton)
            $table->dropUnique('pp_jugada_carton_unique');
        });
    }

    public function down(): void
    {
        Schema::table('participante_carton_prueba', function (Blueprint $table) {
            $table->dropUnique('pp_carton_unique');
            $table->unique(['participante_prueba_id', 'jugada_id', 'carton_id'], 'pp_jugada_carton_unique');
        });
    }
};
