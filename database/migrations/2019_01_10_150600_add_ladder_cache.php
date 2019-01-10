<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLadderCache extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ladder_caches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('encounter_id');
            $table->integer('difficulty_id');
            $table->integer('fastest_encounter');
            $table->integer('top_dps_encounter_member');
            $table->integer('top_hps_encounter_member');
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
        Schema::dropIfExists('ladder_caches');

    }
}
