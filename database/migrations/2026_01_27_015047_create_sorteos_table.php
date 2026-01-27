<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sorteos', function (Blueprint $table) {
            $table->id();

            // Relación con la jugada
            $table->unsignedBigInteger('jugada_id');

            // Estado del sorteo
            $table->enum('estado', ['pendiente', 'en_curso', 'pausado', 'finalizado'])
                  ->default('pendiente');

            // Control de bolillas
            $table->integer('bolilla_actual')->nullable();
            $table->integer('bolillas_extraidas')->default(0);

            // Secuencia completa de bolillas (para auditoría y repetición)
            $table->json('orden_salida')->nullable();

            // Estadísticas
            $table->integer('bolilla_linea')->nullable();
            $table->integer('bolilla_bingo')->nullable();

            // Tiempos
            $table->timestamp('inicio')->nullable();
            $table->timestamp('pausa')->nullable();
            $table->timestamp('fin')->nullable();
            $table->integer('segundos_total')->nullable();

            // Quién operó el sorteo (futuro rol operador)
            $table->unsignedBigInteger('operador_id')->nullable();

            $table->timestamps();

            // Claves foráneas
            $table->foreign('jugada_id')->references('id')->on('jugadas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sorteos');
    }
};
