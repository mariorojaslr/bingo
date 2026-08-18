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
            $table->string('alias_pago_1')->nullable()->after('email');
            $table->string('alias_pago_2')->nullable()->after('alias_pago_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prueba_participantes', function (Blueprint $table) {
            $table->dropColumn(['alias_pago_1', 'alias_pago_2']);
        });
    }
};
