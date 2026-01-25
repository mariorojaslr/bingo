<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla 'cartons' como entidad pura de cartones de bingo,
     * preparada para soportar múltiples formatos (argentino, americano, etc.).
     */
    public function up(): void
    {
        Schema::create('cartons', function (Blueprint $table) {

            // ID interno único
            $table->id();

            // Serie del cartón (ej: "ARG-2026-01", "USA-2026-01")
            $table->string('serie', 50);

            // Número visible del cartón dentro de la serie
            $table->integer('numero_carton');

            // Formato del bingo:
            // 'argentino' = 3x9
            // 'americano' = 5x5 (BINGO)
            $table->string('formato', 20);

            // Grilla completa del cartón en formato JSON
            // Se adapta según el formato
            $table->json('grilla');

            // Estado lógico del cartón
            // disponible | asignado | vendido | jugado | anulado
            $table->string('estado', 20)->default('disponible');

            // Fechas de control
            $table->timestamps();

            // Evita duplicados dentro de una misma serie
            $table->unique(['serie', 'numero_carton']);
        });
    }

    /**
     * Elimina la tabla 'cartons'
     */
    public function down(): void
    {
        Schema::dropIfExists('cartons');
    }
};
