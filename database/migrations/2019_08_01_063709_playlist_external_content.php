<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PlaylistExternalContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('dbp_users')->hasTable('user_playlists')) {
            Schema::connection('dbp_users')->table('user_playlists', function (Blueprint $table) {
                if (!Schema::connection('dbp_users')->hasColumn('user_playlists', 'external_content')) {
                    $table->string('external_content', 200)->default('');
                }
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
        Schema::connection('dbp_users')->table('user_playlists', function (Blueprint $table) {
            $table->dropColumn('external_content');
        });
    }
}
