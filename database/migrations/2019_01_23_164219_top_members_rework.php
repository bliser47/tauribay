<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TopMembersRework extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_tops', function ($table) {
            $table->integer('dps_ilvl');
            $table->integer('hps_ilvl');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_tops', function ($table) {
            $table->dropColumn('dps_ilvl');
            $table->dropColumn('hps_ilvl');
        });
    }
}
