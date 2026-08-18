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
        Schema::create('blackjack_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name')->default('Mesa 1');
            $table->string('status')->default('waiting_bets'); // waiting_bets, playing, dealer_turn, finished
            $table->json('deck')->nullable();
            $table->json('dealer_hand')->nullable();
            $table->integer('current_turn_seat')->nullable(); // Which seat's turn is it? (1-5)
            $table->dateTime('action_deadline')->nullable(); // For timeout
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blackjack_tables');
    }
};
