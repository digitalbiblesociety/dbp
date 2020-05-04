<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDraftPlaylistsPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dbp_users')->table('plans', function (Blueprint $table) {
            $table->boolean('draft')
                ->after('suggested_start_date')
                ->default(false);
        });
        Schema::connection('dbp_users')->table('user_playlists', function (Blueprint $table) {
            $table->boolean('draft')
                ->after('user_id')
                ->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('dbp_users')->table('plans', function (Blueprint $table) {
            $table->dropColumn('draft');
        });
        Schema::connection('dbp_users')->table('user_playlists', function (Blueprint $table) {
            $table->dropColumn('draft');
        });
    }
}
