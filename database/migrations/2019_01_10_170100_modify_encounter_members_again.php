<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyEncounterMembersAgain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('encounter_members', function($table) {
            $table->integer('encounter');
            $table->integer('fight_time');
            $table->integer('dps');
            $table->integer('hps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('encounter_members', function($table) {
            $table->dropColumn('encounter');
            $table->dropColumn('fight_time');
            $table->dropColumn('dps');
            $table->dropColumn('hps');
        });
    }
}
