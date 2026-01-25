<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('organizadores', function (Blueprint $table) {
            $table->id();

            // Relación con users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Tipo de organizador
            $table->enum('tipo', ['persona_fisica', 'persona_juridica']);

            // Datos comunes
            $table->string('nombre_fantasia')->nullable(); // Ej: Bingo San Martín
            $table->string('email_contacto')->nullable();
            $table->string('telefono')->nullable();

            // Datos fiscales / legales
            $table->string('razon_social')->nullable();
            $table->string('cuit')->nullable();
            $table->string('tipo_documento')->nullable(); // DNI, CUIT, CUIL, PASAPORTE
            $table->string('numero_documento')->nullable();

            // Dirección
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('provincia')->nullable();
            $table->string('pais')->nullable();

            // Estado
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('organizadores');
    }
};


