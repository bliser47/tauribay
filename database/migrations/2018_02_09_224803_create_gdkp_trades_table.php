<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGdkpTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gdkp_trades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('text');
            $table->integer('faction');
            $table->integer('intent');
            $table->integer('instance');
            $table->integer('size');
            $table->integer('difficulty');
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
        Schema::dropIfExists('gdkp_trades');
    }
}
