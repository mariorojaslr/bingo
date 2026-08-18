<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jugadas', function (Blueprint $table) {
            $table->foreignId('plantilla_sala_virtual_id')->nullable()->constrained('plantillas_salas_virtuales')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('jugadas', function (Blueprint $table) {
            $table->dropForeign(['plantilla_sala_virtual_id']);
            $table->dropColumn('plantilla_sala_virtual_id');
        });
    }
};
