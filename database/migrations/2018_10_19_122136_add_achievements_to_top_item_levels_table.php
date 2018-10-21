<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAchievementsToTopItemLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('top_item_levels', function (Blueprint $table) {
             $table->integer('achievement_points')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('top_item_levels', function (Blueprint $table) {
            $table->dropColumn('achievement_points');
        });
    }
}
