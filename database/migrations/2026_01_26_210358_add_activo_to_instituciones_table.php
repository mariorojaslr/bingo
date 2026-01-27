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
        Schema::table('instituciones', function (Blueprint $table) {
            // Agrega la columna 'activo' con valor por defecto 1 (activa)
            $table->boolean('activo')->default(1)->after('activa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instituciones', function (Blueprint $table) {
            // Elimina la columna 'activo' si se hace rollback
            $table->dropColumn('activo');
        });
    }
};
