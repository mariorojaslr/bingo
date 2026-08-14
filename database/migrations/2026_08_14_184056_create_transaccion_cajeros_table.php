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
        Schema::create('transaccion_cajeros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participante_id')->constrained('prueba_participantes')->onDelete('cascade');
            $table->string('metodo_pago'); // mp, prex_ar, prex_uy, airtm, arq
            $table->integer('fichas');
            $table->decimal('monto_fiat', 10, 2);
            $table->string('estado')->default('pendiente'); // pendiente, aprobado, rechazado
            $table->string('comprobante_externo')->nullable();
            $table->text('detalles_adicionales')->nullable(); // Para guardar el preference_id o info extra
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaccion_cajeros');
    }
};
