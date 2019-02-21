<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharactersLinkToEncounterMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_encounters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('character_id')->references('id')->on('characters');
            $table->integer('encounter_member_id')->references('id')->on('encounter_members');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_encounters');
    }
}
