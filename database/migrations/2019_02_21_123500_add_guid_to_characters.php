<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGuidToCharacters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('characters', function($table) {
            $table->integer("guid");
            $table->unique(['guid','realm','name']);
        });

        Schema::table('encounter_members', function($table) {
            $table->integer("guid");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('characters', function($table) {
            $table->dropColumn("guid");
            $table->dropUnique(['guid','realm','name']);
        });

        Schema::table('encounter_members', function($table) {
            $table->dropColumn("guid");
        });
    }
}
