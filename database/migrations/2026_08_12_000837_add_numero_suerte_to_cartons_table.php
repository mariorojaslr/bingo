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
        Schema::table('cartons', function (Blueprint $table) {
            $table->string('numero_suerte', 10)->nullable()->after('numero_carton');
            $table->unique(['serie', 'numero_suerte']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cartons', function (Blueprint $table) {
            $table->dropUnique(['serie', 'numero_suerte']);
            $table->dropColumn('numero_suerte');
        });
    }
};
