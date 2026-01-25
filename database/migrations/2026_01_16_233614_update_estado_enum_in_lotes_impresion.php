<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE lotes_impresion
            MODIFY estado ENUM(
                'pedido',
                'generado',
                'en_impresion',
                'impreso',
                'entregado'
            ) NOT NULL DEFAULT 'pedido'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE lotes_impresion
            MODIFY estado ENUM(
                'pedido'
            ) NOT NULL DEFAULT 'pedido'
        ");
    }
};
