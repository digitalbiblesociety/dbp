<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLanguagePlansPlaylists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dbp_users')->table('plans', function (Blueprint $table) {
            $table->integer('language_id')->unsigned()->nullable()->after('draft');
            $table->foreign('language_id', 'FK_languages_plans')->references('id')->on(config('database.connections.dbp.database') . '.languages')->onUpdate('cascade');
        });
        Schema::connection('dbp_users')->table('user_playlists', function (Blueprint $table) {
            $table->integer('language_id')->unsigned()->nullable()->after('draft');
            $table->foreign('language_id', 'FK_languages_playlists')->references('id')->on(config('database.connections.dbp.database') . '.languages')->onUpdate('cascade');
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
            $table->dropColumn('language_id');
        });
        Schema::connection('dbp_users')->table('user_playlists', function (Blueprint $table) {
            $table->dropColumn('language_id');
        });
    }
}
