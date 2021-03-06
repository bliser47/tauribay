<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EncounterMembersAddScores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('encounter_members', function($table) {
            $table->integer('dps_score');
            $table->integer('hps_score');
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
            $table->dropColumn('dps_score');
            $table->dropColumn('hps_score');
        });
    }
}
