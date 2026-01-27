<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('sorteos', function (Blueprint $table) {

            if (!Schema::hasColumn('sorteos', 'tiempo_linea')) {
                $table->integer('tiempo_linea')->nullable();
            }

            if (!Schema::hasColumn('sorteos', 'tiempo_bingo')) {
                $table->integer('tiempo_bingo')->nullable();
            }

            if (!Schema::hasColumn('sorteos', 'fin_sorteo')) {
                $table->timestamp('fin_sorteo')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('sorteos', function (Blueprint $table) {
            $table->dropColumn(['tiempo_linea', 'tiempo_bingo', 'fin_sorteo']);
        });
    }
};
