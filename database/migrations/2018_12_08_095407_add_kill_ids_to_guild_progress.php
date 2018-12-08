<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKillIdsToGuildProgress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guild_progresses', function($table) {
            $table->integer('first_kill_log_id');
            $table->integer('last_kill_log_id');
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
            $table->dropColumn('first_kill_log_id');
            $table->dropColumn('last_kill_log_id');
        });
    }
}
