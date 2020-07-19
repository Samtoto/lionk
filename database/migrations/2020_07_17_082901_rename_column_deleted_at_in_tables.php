<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnDeletedAtInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('blogs', function (Blueprint $table) {
        //     $table->renameColumn(`0`, 'deleted_at');
        // });

        // Schema::table('user_community', function (Blueprint $table) {
        //     $table->renameColumn(`0`, 'deleted_at');
        // });

        // Schema::table('comments', function (Blueprint $table) {
        //     $table->renameColumn(`0`, 'deleted_at');
        // });

        // Schema::table('communities', function (Blueprint $table) {
        //     $table->renameColumn(`0`, 'deleted_at');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('tables', function (Blueprint $table) {
        //     //
        // });
    }
}
