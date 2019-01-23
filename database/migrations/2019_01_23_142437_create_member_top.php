<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_tops', function ($table) {
            $table->increments('id');
            $table->integer('realm_id');
            $table->integer('spec');
            $table->integer('class');
            $table->integer('faction_id');
            $table->string('name');
            $table->string('encounter_id');
            $table->string('difficulty_id');
            $table->integer('dps');
            $table->integer('dps_encounter_id');
            $table->integer('dps_guild_id');
            $table->integer('hps');
            $table->integer('hps_encounter_id');
            $table->integer('hps_guild_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_tops');
    }
}