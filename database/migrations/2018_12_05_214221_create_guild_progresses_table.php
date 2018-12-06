<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuildProgressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guild_progresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('guild_id')->references('id')->on('guilds');
            $table->integer('map_id');
            $table->integer('difficulty_id');
            $table->integer('encounter_id');
            $table->integer('kill_count');
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
        Schema::dropIfExists('guild_progresses');
    }
}
