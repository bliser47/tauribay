<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixUniqueOnGuilds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guilds', function($table) {
            $table->dropUnique('realm');
            $table->unique(['name','realm']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guilds', function($table) {
            $table->dropUnique(['name', 'realm']);
            $table->unique('name', 'realm');
        });
    }
}
