<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FollowPlaylists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp_users')->hasTable('playlists_followers')) {
            Schema::connection('dbp_users')->create('playlists_followers', function (Blueprint $table) {
                $table->index(['user_id', 'playlist_id']);
                $table->unique(['user_id', 'playlist_id']);
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id', 'FK_users_playlists_followers')->references('id')->on(config('database.connections.dbp_users.database') . '.users')->onUpdate('cascade');
                $table->bigInteger('playlist_id')->unsigned()->nullable();
                $table->foreign('playlist_id', 'FK_playlist_playlists_followers')->references('id')->on(config('database.connections.dbp_users.database') . '.user_playlists')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::connection('dbp_users')->dropIfExists('playlists_followers');
    }
}
