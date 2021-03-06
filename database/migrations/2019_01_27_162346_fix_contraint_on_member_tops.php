<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixContraintOnMemberTops extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_tops', function($table) {
            $table->dropUnique(['name','realm_id','encounter_id','class','spec']);
            $table->unique(['name','realm_id','encounter_id','difficulty_id','spec']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_tops', function($table) {
            $table->unique(['name','realm_id','encounter_id','class','spec']);
            $table->dropUnique(['name','realm_id','encounter_id','difficulty_id','spec']);
        });
    }
}
