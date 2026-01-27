<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Ampliar ENUM temporalmente
        DB::statement("
            ALTER TABLE lotes_impresion
            MODIFY estado ENUM('pedido','generado','materializado','en_impresion','entregado','anulado')
            NOT NULL
        ");

        // 2) Migrar valores viejos al nuevo estado técnico
        DB::statement("
            UPDATE lotes_impresion
            SET estado = 'en_impresion'
            WHERE estado = 'materializado'
        ");

        // 3) Dejar ENUM definitivo limpio
        DB::statement("
            ALTER TABLE lotes_impresion
            MODIFY estado ENUM('pedido','generado','en_impresion','entregado','anulado')
            NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE lotes_impresion
            MODIFY estado ENUM('pedido','generado','materializado','anulado')
            NOT NULL
        ");
    }
};
