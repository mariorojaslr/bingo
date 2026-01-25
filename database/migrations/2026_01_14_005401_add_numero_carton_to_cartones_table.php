<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('cartons', function (Blueprint $table) {
            $table->string('numero_carton', 6)->unique()->change();
        });
    }

    public function down()
    {
        Schema::table('cartons', function (Blueprint $table) {
            $table->integer('numero_carton')->change();
        });
    }
};
