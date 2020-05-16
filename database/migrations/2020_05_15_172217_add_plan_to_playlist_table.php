<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlanToPlaylistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dbp_users')->table('user_playlists', function (Blueprint $table) {
            $table->bigInteger('plan_id')->unsigned();
        });
        \DB::connection('dbp_users')
            ->statement('UPDATE user_playlists left join plan_days on plan_days.playlist_id = user_playlists.id set user_playlists.plan_id = IFNULL(plan_days.plan_id,0);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('dbp_users')->table('user_playlists', function (Blueprint $table) {
            $table->dropColumn('plan_id');
        });
    }
}
