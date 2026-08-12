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
        Schema::table('sorteos', function (Blueprint $table) {
            // Drop the old enum and replace with string (in raw SQL it's safer for enums to avoid doctrine/dbal issues)
        });
        
        // Use raw statement to avoid requiring doctrine/dbal for enum modifications
        \DB::statement("ALTER TABLE sorteos MODIFY estado VARCHAR(50) DEFAULT 'pendiente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert for this project, just keep it as VARCHAR
    }
};
