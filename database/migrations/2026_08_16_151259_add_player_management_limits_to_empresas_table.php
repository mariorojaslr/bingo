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
        Schema::table('empresas', function (Blueprint $table) {
            $table->integer('default_play_time_limit_minutes')->default(240)->after('modo_prueba')->comment('Límite de tiempo por defecto (240 mins = 4 horas)');
            $table->decimal('default_daily_spend_limit', 12, 2)->nullable()->after('default_play_time_limit_minutes')->comment('Límite de gasto diario por defecto (nulo = sin límite)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['default_play_time_limit_minutes', 'default_daily_spend_limit']);
        });
    }
};
