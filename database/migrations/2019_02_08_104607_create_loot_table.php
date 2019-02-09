<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLootTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('encounter_id')->references('id')->on('encounters');
            $table->integer('item_id')->references('id')->on('items');
            $table->integer('count');
            $table->integer('random_prop');
            $table->integer('random_suffix');
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
        Schema::dropIfExists('loot');
    }
}
