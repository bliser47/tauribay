<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyGuildProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guild_progresses', function($table) {
            $table->dropColumn('encounter_id');
            $table->dropColumn('kill_count');
            $table->integer('progress');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guild_progresses', function($table) {
            $table->integer('encounter_id');
            $table->integer('kill_count');
            $table->dropColumn('progress');
        });
    }
}
