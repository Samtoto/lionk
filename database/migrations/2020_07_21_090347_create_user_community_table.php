<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCommunityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_community', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('community_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('community_id')->references('id')->on('communities');
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
        Schema::table('user_community', function (Blueprint $table) {
            $table->dropForeign('user_id');
            $table->dropForeign('community_id');
        });
        Schema::dropIfExists('user_community');
    }
}
