<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jugada_carton', function (Blueprint $table) {
            $table->integer('numero_hoja')->after('lote_impresion');
            $table->integer('posicion_en_hoja')->after('numero_hoja');
        });
    }

    public function down(): void
    {
        Schema::table('jugada_carton', function (Blueprint $table) {
            $table->dropColumn(['numero_hoja', 'posicion_en_hoja']);
        });
    }
};
