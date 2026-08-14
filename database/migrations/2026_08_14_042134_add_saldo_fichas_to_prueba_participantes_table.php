<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('prueba_participantes', function (Blueprint $table) {
            $table->decimal('saldo_fichas', 12, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prueba_participantes', function (Blueprint $table) {
            $table->dropColumn('saldo_fichas');
        });
    }
};
