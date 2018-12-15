<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyEncounterMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('encounter_members', function($table) {
            $table->dropForeign('character_id');
            $table->dropColumn('character_id');
            $table->integer('realm_id');
            $table->integer('class');
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('encounter_members', function($table) {
            $table->integer('character_id')->references('id')->on('characters');
            $table->dropColumn('realm_id');
            $table->dropColumn('class');
            $table->dropColumn('name');
        });
    }
}
