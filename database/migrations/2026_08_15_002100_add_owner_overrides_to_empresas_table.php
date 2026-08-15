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
            $table->decimal('canon_personalizado', 10, 2)->nullable()->after('tarifa_id');
            $table->decimal('comision_personalizada', 8, 2)->nullable()->after('canon_personalizado');
            $table->text('notas_owner')->nullable()->after('comision_personalizada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['canon_personalizado', 'comision_personalizada', 'notas_owner']);
        });
    }
};
