<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('participantes_prueba', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('dni')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('avatar')->nullable(); // foto o imagen de perfil
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('participantes_prueba');
    }
};
