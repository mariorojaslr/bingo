<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lotes_impresion', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('jugada_id');

            $table->string('codigo_lote')->unique(); // JUG-0001-L01
            $table->integer('cantidad_hojas');
            $table->integer('cantidad_cartones');

            $table->decimal('precio_hoja', 10, 2);
            $table->decimal('costo_generacion', 10, 2)->default(0);
            $table->decimal('total_impresion', 12, 2);
            $table->decimal('total_general', 12, 2);

            $table->enum('estado', [
                'pedido',
                'en_produccion',
                'impreso',
                'entregado',
                'anulado'
            ])->default('pedido');

            $table->timestamp('fecha_pedido')->useCurrent();
            $table->timestamp('fecha_impresion')->nullable();
            $table->timestamp('fecha_entrega')->nullable();

            $table->timestamps();

            $table->foreign('jugada_id')->references('id')->on('jugadas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lotes_impresion');
    }
};
