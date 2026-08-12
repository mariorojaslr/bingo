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
        // 1. Tabla de Tarifas Comerciales
        Schema::create('tarifas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Básico, Premium
            $table->decimal('canon_mensual', 10, 2)->default(0);
            $table->decimal('comision_por_carton', 8, 2)->default(0);
            $table->integer('max_cartones')->nullable();
            $table->integer('max_salas')->nullable();
            $table->boolean('streaming_incluido')->default(false);
            $table->timestamps();
        });

        // 2. Tabla de Empresas (Tenants)
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('subdominio')->nullable()->unique();
            $table->string('logo_url')->nullable();
            $table->string('color_primario')->default('#00ff88');
            $table->string('color_secundario')->default('#00a8ff');
            $table->string('color_terciario')->default('#ff0055');
            
            $table->unsignedBigInteger('tarifa_id')->nullable();
            $table->foreign('tarifa_id')->references('id')->on('tarifas')->onDelete('set null');

            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // 3. Adaptar tabla Users para multi-rol y multi-empresa
        Schema::table('users', function (Blueprint $table) {
            // master_owner, admin_empresa, operador, director_tv
            $table->string('rol')->default('admin_empresa')->after('password');
            $table->unsignedBigInteger('empresa_id')->nullable()->after('rol');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });

        // 4. Adaptar Jugadas a una empresa específica
        Schema::table('jugadas', function (Blueprint $table) {
            $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            
            // Nueva funcionalidad: Pozo Acumulado Especial si sale antes de la bolilla X
            $table->decimal('pozo_acumulado', 12, 2)->default(0);
            $table->integer('limite_bolilla_pozo')->nullable(); // Ej: Sale Bingo antes de la 40
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jugadas', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn(['empresa_id', 'pozo_acumulado', 'limite_bolilla_pozo']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn(['rol', 'empresa_id']);
        });

        Schema::dropIfExists('empresas');
        Schema::dropIfExists('tarifas');
    }
};
