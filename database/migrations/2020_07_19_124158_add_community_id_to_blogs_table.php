<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommunityIdToBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->unsignedBigInteger('community_id');
        });

        Schema::table('blogs', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->foreign('community_id')->references('id')->on('communities')->onDelete('cascade');
            Schema::enableForeignKeyConstraints();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropForeign('community_id');
            $table->dropColumn('community_id');
            Schema::enableForeignKeyConstraints();
        });
    }
}
