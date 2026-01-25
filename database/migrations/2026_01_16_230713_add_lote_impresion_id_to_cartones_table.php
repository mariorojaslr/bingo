<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cartons', function (Blueprint $table) {
            // Relación con lotes_impresion
            $table->unsignedBigInteger('lote_impresion_id')->nullable()->after('id');

            $table->foreign('lote_impresion_id')
                  ->references('id')
                  ->on('lotes_impresion')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('cartons', function (Blueprint $table) {
            $table->dropForeign(['lote_impresion_id']);
            $table->dropColumn('lote_impresion_id');
        });
    }
};
