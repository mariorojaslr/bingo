<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jugada_carton', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('jugada_id')->constrained('jugadas')->onDelete('cascade');
            $table->foreignId('carton_id')->constrained('cartons')->onDelete('restrict');

            // Datos de impresión
            $table->string('lote_impresion')->nullable(); // A1, B3, etc
            $table->timestamp('fecha_impresion')->nullable();

            // Ubicación en el PDF
            $table->integer('numero_hoja')->nullable();
            $table->integer('posicion_en_hoja')->nullable(); // 1,2,3 (o 1..6)

            // Estado del cartón dentro de la jugada
            $table->enum('estado', [
                'asignado',
                'impreso',
                'entregado',
                'usado',
                'anulado'
            ])->default('asignado');

            $table->timestamps();

            // Regla de oro: un cartón no se repite dentro de una misma jugada
            $table->unique(['jugada_id', 'carton_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('jugada_carton');
    }
};
