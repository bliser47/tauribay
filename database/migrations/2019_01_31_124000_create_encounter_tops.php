<?php

use Illuminate\Database\Migrations\Migration;

class CreateEncounterTops extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encounter_tops', function ($table) {
            $table->increments('id');
            $table->integer('realm_id');
            $table->integer('encounter_id');
            $table->integer('difficulty_id');
            $table->integer('guild_id');
            $table->integer('faction');
            $table->integer('fastest_encounter_id');
            $table->integer('fastest_encounter_time');
            $table->unique(['encounter_id','difficulty_id','guild_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('encounter_tops');
    }
}
