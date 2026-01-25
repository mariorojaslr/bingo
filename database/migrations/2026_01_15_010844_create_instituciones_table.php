<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('instituciones', function (Blueprint $table) {
            $table->id();

            // Datos principales
            $table->string('nombre');              // Club San Martín
            $table->string('tipo')->nullable();    // club, escuela, parroquia, ONG, etc.
            $table->string('logo')->nullable();    // path del logo para impresión
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();

            // Ubicación
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('provincia')->nullable();
            $table->string('pais')->default('Argentina');

            // Datos para encabezado de cartón
            $table->string('texto_encabezado')->nullable(); // "Bingo Club San Martín"
            $table->string('texto_pie')->nullable();        // auspiciantes, etc.

            // Estado
            $table->boolean('activa')->default(true);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('instituciones');
    }
};
