<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropOndeletCascadeToUserCommunity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_community', function (Blueprint $table) {
            // Schema::disableForeignKeyConstraints();
            // $table->dropForeign('user_id');
            // $table->dropForeign('community_id');
            // $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('community_id')->references('id')->on('communities');
            // Schema::enableForeignKeyConstraints();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_community', function (Blueprint $table) {
            // $table->dropForeign('user_id');
            // $table->dropForeign('community_id');
        });
    }
}
