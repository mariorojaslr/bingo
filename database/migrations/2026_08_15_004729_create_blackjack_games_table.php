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
        Schema::create('blackjack_games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->unsignedBigInteger('participante_id');
            $table->string('estado')->default('betting'); // betting, playing, dealer_turn, finished
            $table->decimal('bet_amount', 12, 2)->default(0);
            $table->json('deck')->nullable();
            $table->json('player_hand')->nullable();
            $table->json('dealer_hand')->nullable();
            $table->string('result')->nullable(); // win, loss, push, blackjack
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('participante_id')->references('id')->on('prueba_participantes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blackjack_games');
    }
};
