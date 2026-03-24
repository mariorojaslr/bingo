<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cartons', function (Blueprint $table) {
            $table->string('hash', 64)->unique()->nullable()->after('grilla')
                  ->comment('Hash MD5 estricto de la matriz matemática para garantizar 0% repeticiones en el sistema');
                  
            $table->unsignedBigInteger('organizador_id')->nullable()->after('id')->comment('Aislamiento Multi-Tenant');
            
            // Note: Not setting foreign key constraint yet on organizante_id so existing dirty data doesn't break migration.
            // When we do full cleanup, we will enforce it.
        });
    }

    public function down(): void
    {
        Schema::table('cartons', function (Blueprint $table) {
            $table->dropColumn('hash');
            $table->dropColumn('organizador_id');
        });
    }
};
