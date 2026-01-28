<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    public function up()
{
    Schema::table('prueba_participantes', function (Blueprint $table) {
        $table->string('codigo_acceso', 10)->after('token');
    });
}

public function down()
{
    Schema::table('prueba_participantes', function (Blueprint $table) {
        $table->dropColumn('codigo_acceso');
    });
}


};
