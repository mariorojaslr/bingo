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
        Schema::create('mega_sorteos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->nullable()->constrained()->onDelete('cascade');
            $table->dateTime('draw_date');
            $table->string('status')->default('pending'); // pending, drawn, cancelled
            $table->decimal('ticket_price', 10, 2)->default(100.00);
            $table->decimal('accumulated_jackpot', 15, 2)->default(0);
            $table->json('winning_numbers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mega_sorteos');
    }
};
