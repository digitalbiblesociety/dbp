<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompleteChapterPlaylistsItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('dbp_users')->hasTable('playlist_items')) {
            Schema::connection('dbp_users')->table('playlist_items', function (Blueprint $table) {
                $table->integer('verse_start')->unsigned()->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::connection('dbp_users')->hasTable('playlist_items')) {
            Schema::connection('dbp_users')->table('playlist_items', function (Blueprint $table) {
                $table->integer('verse_start')->unsigned()->nullable(false)->change();
            });
        }
    }
}
