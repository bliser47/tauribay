<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncounterMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encounter_members', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_bin';
            $table->increments('id');
            $table->integer('encounter_id')->references('id')->on('encounters');
            $table->integer('character_id')->references('id')->on('characters');
            $table->integer('spec');
            $table->integer('damage_done');
            $table->integer('damage_taken');
            $table->integer('damage_absorb');
            $table->integer('heal_done');
            $table->integer('absorb_done');
            $table->integer('overheal');
            $table->integer('heal_taken');
            $table->integer('interrupts');
            $table->integer('dispells');
            $table->integer('ilvl');
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
        Schema::dropIfExists('encounter_members');
    }
}
