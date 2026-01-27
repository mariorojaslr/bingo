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

            if (!Schema::hasColumn('sorteos', 'carton_linea_id')) {
                $table->unsignedBigInteger('carton_linea_id')->nullable();
            }

            if (!Schema::hasColumn('sorteos', 'carton_bingo_id')) {
                $table->unsignedBigInteger('carton_bingo_id')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('sorteos', function (Blueprint $table) {
            if (Schema::hasColumn('sorteos', 'tiempo_linea')) {
                $table->dropColumn('tiempo_linea');
            }
            if (Schema::hasColumn('sorteos', 'tiempo_bingo')) {
                $table->dropColumn('tiempo_bingo');
            }
            if (Schema::hasColumn('sorteos', 'carton_linea_id')) {
                $table->dropColumn('carton_linea_id');
            }
            if (Schema::hasColumn('sorteos', 'carton_bingo_id')) {
                $table->dropColumn('carton_bingo_id');
            }
        });
    }
};
