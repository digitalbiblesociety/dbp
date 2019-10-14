<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVerseEndToUserHighlights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dbp_users')->table('user_highlights', function (Blueprint $table) {
            $table->integer('verse_end')->after('verse_start')->unsigned()->nullable();
        });
        \DB::connection('dbp_users')->statement('UPDATE user_highlights SET verse_end = verse_start');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('dbp_users')->table('user_highlights', function (Blueprint $table) {
            $table->dropColumn('verse_end');
        });
    }
}
