<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sorteos', function (Blueprint $table) {
            if (!Schema::hasColumn('sorteos', 'bolillas_sacadas')) {
                $table->json('bolillas_sacadas')->nullable()->after('bolilla_actual');
            }
        });
    }

    public function down()
    {
        Schema::table('sorteos', function (Blueprint $table) {
            if (Schema::hasColumn('sorteos', 'bolillas_sacadas')) {
                $table->dropColumn('bolillas_sacadas');
            }
        });
    }
};
