<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFreshchatRestoreIdToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dbp_users')->table('users', function (
      Blueprint $table
    ) {
            $table
        ->string('freshchat_restore_id')
        ->after('token')
        ->nullable()
        ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('dbp_users')->table('users', function (
      Blueprint $table
    ) {
            $table->dropColumn('freshchat_restore_id');
        });
    }
}
