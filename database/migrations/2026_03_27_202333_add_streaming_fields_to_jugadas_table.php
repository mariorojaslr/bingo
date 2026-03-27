<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('jugadas', function (Blueprint $table) {
            $table->string('streaming_url')->nullable()->after('lugar');
            $table->string('streaming_server')->nullable()->after('streaming_url');
            $table->string('streaming_key')->nullable()->after('streaming_server');
            $table->string('bunny_library_id')->nullable()->after('streaming_key');
            $table->string('bunny_live_stream_id')->nullable()->after('bunny_library_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('jugadas', function (Blueprint $table) {
            $table->dropColumn([
                'streaming_url',
                'streaming_server',
                'streaming_key',
                'bunny_library_id',
                'bunny_live_stream_id'
            ]);
        });
    }
};
