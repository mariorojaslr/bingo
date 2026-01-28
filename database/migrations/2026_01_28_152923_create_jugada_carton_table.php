<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jugada_carton', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('jugada_id');
            $table->unsignedBigInteger('carton_id');

            $table->string('lote_impresion')->nullable();
            $table->integer('orden')->nullable();

            $table->timestamps();

            $table->foreign('jugada_id')
                  ->references('id')
                  ->on('jugadas')
                  ->onDelete('cascade');

            $table->foreign('carton_id')
                  ->references('id')
                  ->on('cartons')
                  ->onDelete('cascade');

            $table->unique(['jugada_id', 'carton_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jugada_carton');
    }
};
