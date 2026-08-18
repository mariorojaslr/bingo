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
        Schema::create('mega_sorteo_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mega_sorteo_id')->constrained()->onDelete('cascade');
            $table->foreignId('participante_id')->constrained('prueba_participantes')->onDelete('cascade');
            $table->json('numbers'); // e.g. [5, 12, 23, 34, 40, 45]
            $table->integer('hits')->default(0);
            $table->decimal('won_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mega_sorteo_tickets');
    }
};
