<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantillas_salas_virtuales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('nombre'); // Ej: Sala Roja
            $table->integer('intervalo_minutos')->default(20); // Cada cuánto se genera una sala
            $table->decimal('precio_carton', 10, 2)->default(1000);
            $table->integer('duracion_minutos')->default(10);
            $table->decimal('porcentaje_pozo', 5, 2)->default(5.00); // 5% de lo recaudado
            $table->integer('limite_bolilla_pozo')->default(40); // Si canta bingo antes de la 40
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantillas_salas_virtuales');
    }
};
