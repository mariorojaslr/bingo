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
        Schema::create('blackjack_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blackjack_table_id')->constrained()->onDelete('cascade');
            $table->integer('seat_number'); // 1 to 5
            $table->foreignId('participante_id')->nullable()->constrained('prueba_participantes')->onDelete('set null');
            $table->string('status')->default('empty'); // empty, waiting, betting, playing, stood, busted
            $table->decimal('bet_amount', 10, 2)->default(0);
            $table->json('hand')->nullable();
            $table->string('result')->nullable(); // win, loss, push, blackjack
            $table->decimal('payout', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['blackjack_table_id', 'seat_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blackjack_seats');
    }
};
