<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp_users')->hasTable('user_playlists')) {
            Schema::connection('dbp_users')->create('user_playlists', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->boolean('featured')->default(false);
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id', 'FK_user_playlists')->references('id')->on(config('database.connections.dbp_users.database').'.users')->onDelete('cascade')->onUpdate('cascade');
                $table->softDeletes();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
        Schema::connection('dbp_users')->dropIfExists('user_playlists');
    }
}
