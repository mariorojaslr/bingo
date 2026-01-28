<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prueba_participantes', function (Blueprint $table) {
            $table->string('apellido')->nullable()->after('nombre');
            $table->string('dni')->nullable()->after('apellido');
            $table->string('email')->nullable()->after('telefono');
            $table->string('avatar')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('prueba_participantes', function (Blueprint $table) {
            $table->dropColumn(['apellido','dni','email','avatar']);
        });
    }
};
