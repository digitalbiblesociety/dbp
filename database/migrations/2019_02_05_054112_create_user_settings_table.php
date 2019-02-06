<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp_users')->hasTable('user_settings')) {
            Schema::connection('dbp_users')->create('user_settings', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->index();
                $table->foreign('user_id', 'FK_users_user_settings')->references('id')->on(config('database.connections.dbp_users.database') . '.users')->onDelete('cascade');
                $table->smallInteger('project_id')->unsigned();
                $table->foreign('project_id', 'FK_projects_user_settings')->references('id')->on(config('database.connections.dbp_users.database').'.projects')->onDelete('cascade')->onUpdate('cascade');
                $table->integer('language_id')->unsigned()->nullable();
                $table->foreign('language_id', 'FK_languages_user_settings')->references('id')->on(config('database.connections.dbp.database').'.languages')->onUpdate('cascade');
                $table->string('theme', 12)->nullable();
                $table->string('font_type')->nullable();
                $table->tinyInteger('font_size')->unsigned()->nullable();
                $table->string('bible_id', 12)->nullable();
                $table->foreign('bible_id', 'FK_bibles_user_settings')->references('id')->on(config('database.connections.dbp.database').'.bibles')->onDelete('cascade')->onUpdate('cascade');
                $table->char('book_id', 3)->nullable();
                $table->foreign('book_id', 'FK_books_user_settings')->references('id')->on(config('database.connections.dbp.database').'.books');
                $table->tinyInteger('chapter')->unsigned()->nullable();
                $table->boolean('readers_mode')->nullable();
                $table->boolean('justified_text')->nullable();
                $table->boolean('cross_references')->nullable();
                $table->boolean('unformatted')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('user_settings');
    }
}
