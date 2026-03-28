<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Actualizamos el ENUM para permitir estados transitorios de premios
        DB::statement("ALTER TABLE sorteos MODIFY COLUMN estado ENUM('pendiente', 'en_curso', 'pausado', 'linea', 'bingo', 'finalizado') DEFAULT 'pendiente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE sorteos MODIFY COLUMN estado ENUM('pendiente', 'en_curso', 'pausado', 'finalizado') DEFAULT 'pendiente'");
    }
};
