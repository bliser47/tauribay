<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_trades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('realm_id');
            $table->string('name');
            $table->string('text', 500);
            $table->integer('faction');
            $table->integer('intent');
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
        Schema::dropIfExists('credit_trades');
    }
}
