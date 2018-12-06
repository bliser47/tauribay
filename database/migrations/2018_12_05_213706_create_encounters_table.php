<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEncountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encounters', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_bin';
            $table->increments('id');
            $table->integer('log_id');
            $table->integer('guild_id')->references('id')->on('guilds');
            $table->integer('map_id');
            $table->integer('encounter_id');
            $table->integer('encounter_difficulty_id');
            $table->integer('difficulty_id');
            $table->integer('killtime');
            $table->integer('wipes');
            $table->integer('deaths_total');
            $table->integer('fight_time');
            $table->integer('deaths_fight');
            $table->integer('resurrects_fight');
            $table->integer('member_count');
            $table->integer('item_count');
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
        Schema::dropIfExists('encounters');
    }
}
