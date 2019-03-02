<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRealmAndFactionToLadderCaches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ladder_caches', function (Blueprint $table) {
            $table->integer('realm_id');
            $table->integer('faction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ladder_caches', function (Blueprint $table) {
            $table->dropColumn('realm_id');
            $table->dropColumn('faction_id');
        });
    }
}
