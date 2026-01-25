<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jugadas', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('organizador_id')->constrained('organizadores');
            $table->foreignId('institucion_id')->constrained('instituciones');

            // Identificación de la jugada
            $table->string('nombre');              // Ej: Bingo Club San Martín - Abril 2026
            $table->string('numero_jugada')->nullable(); // visible en cartón
            $table->string('serie')->nullable();   // serie de impresión

            // Datos del evento
            $table->date('fecha_evento');
            $table->time('hora_evento')->nullable();
            $table->string('lugar')->nullable();

            // Configuración de impresión
            $table->string('formato')->default('estandar'); // estandar, doble, especial
            $table->integer('cartones_por_hoja')->default(3);
            $table->string('logo_path')->nullable();
            $table->text('texto_encabezado')->nullable();
            $table->text('texto_pie')->nullable();

            // Estado de vida
            $table->enum('estado', [
                'creada',
                'en_impresion',
                'impresa',
                'jugada',
                'cerrada'
            ])->default('creada');

            // Control comercial e histórico
            $table->timestamp('fecha_impresion')->nullable();
            $table->integer('cantidad_cartones')->nullable();
            $table->integer('cantidad_hojas')->nullable();
            $table->decimal('precio_hoja', 10, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jugadas');
    }
};
