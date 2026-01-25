<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE jugadas
            MODIFY estado ENUM(
                'creada',
                'pedido',
                'en_produccion',
                'en_impresion',
                'impresa',
                'entregada',
                'jugada',
                'cerrada',
                'anulada'
            ) NOT NULL DEFAULT 'creada'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement("
            ALTER TABLE jugadas
            MODIFY estado ENUM(
                'creada',
                'en_impresion',
                'impresa',
                'jugada',
                'cerrada'
            ) NOT NULL DEFAULT 'creada'
        ");
    }
};
