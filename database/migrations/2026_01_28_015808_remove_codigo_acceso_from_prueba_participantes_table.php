<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prueba_participantes', function (Blueprint $table) {
            $table->dropColumn('codigo_acceso');
        });
    }

    public function down(): void
    {
        Schema::table('prueba_participantes', function (Blueprint $table) {
            $table->string('codigo_acceso')->unique();
        });
    }
};
