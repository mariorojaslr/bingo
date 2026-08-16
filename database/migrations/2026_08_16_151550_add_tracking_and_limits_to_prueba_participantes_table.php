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
            $table->boolean('is_banned')->default(false)->after('es_prueba');
            $table->timestamp('last_activity_at')->nullable()->after('is_banned');
            $table->string('current_game')->nullable()->after('last_activity_at');
            $table->integer('play_time_limit_minutes')->nullable()->after('current_game')->comment('Límite específico para este jugador');
            $table->decimal('daily_spend_limit', 12, 2)->nullable()->after('play_time_limit_minutes')->comment('Límite de gasto específico para este jugador');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prueba_participantes', function (Blueprint $table) {
            $table->dropColumn(['is_banned', 'last_activity_at', 'current_game', 'play_time_limit_minutes', 'daily_spend_limit']);
        });
    }
};
