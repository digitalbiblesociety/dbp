<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp_users')->hasTable('plans')) {
            Schema::connection('dbp_users')->create('plans', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->boolean('featured')->default(false);
                $table->integer('user_id')->unsigned();
                $table->date('suggested_start_date');
                $table->foreign('user_id', 'FK_plans')->references('id')->on(config('database.connections.dbp_users.database') . '.users')->onDelete('cascade')->onUpdate('cascade');
                $table->softDeletes();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp_users')->hasTable('plan_days')) {
            Schema::connection('dbp_users')->create('plan_days', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('plan_id')->unsigned();
                $table->foreign('plan_id', 'FK_plan_days')->references('id')->on(config('database.connections.dbp_users.database') . '.plans')->onDelete('cascade')->onUpdate('cascade');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                $table->integer('order_column');
            });
        }

        if (!Schema::connection('dbp_users')->hasTable('plan_playlist_items')) {
            Schema::connection('dbp_users')->create('plan_playlist_items', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('day_id')->unsigned()->nullable();
                $table->foreign('day_id', 'FK_plan_days_items')->references('id')->on(config('database.connections.dbp_users.database') . '.plan_days')->onDelete('cascade')->onUpdate('cascade');
                $table->bigInteger('playlist_id')->unsigned()->nullable();
                $table->foreign('playlist_id', 'FK_playlists_items')->references('id')->on(config('database.connections.dbp_users.database') . '.user_playlists')->onDelete('cascade')->onUpdate('cascade');
                $table->string('fileset_id', 16);
                $table->foreign('fileset_id', 'FK_bible_filesets_plan_days_items')->references('id')->on(config('database.connections.dbp.database') . '.bible_filesets')->onUpdate('cascade')->onDelete('cascade');
                $table->char('book_id', 3);
                $table->foreign('book_id', 'FK_books_plan_days_items')->references('id')->on(config('database.connections.dbp.database') . '.books');
                $table->integer('chapter_start')->unsigned();
                $table->integer('chapter_end')->unsigned()->nullable();
                $table->integer('verse_start')->unsigned();
                $table->integer('verse_end')->unsigned()->nullable();
                $table->integer('duration');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                $table->integer('order_column');
            });
        }

        if (!Schema::connection('dbp_users')->hasTable('user_plans')) {
            Schema::connection('dbp_users')->create('user_plans', function (Blueprint $table) {
                $table->index(['user_id', 'plan_id']);
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id', 'FK_users_user_plans')->references('id')->on(config('database.connections.dbp_users.database') . '.users')->onUpdate('cascade');
                $table->bigInteger('plan_id')->unsigned();
                $table->date('start_date')->nullable();
                $table->integer('percentage_completed');
                $table->text('days_completed');
                $table->text('items_completed');
                $table->foreign('plan_id', 'FK_plans_user_plans')->references('id')->on(config('database.connections.dbp_users.database') . '.plans')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::connection('dbp_users')->dropIfExists('user_plans');
        Schema::connection('dbp_users')->dropIfExists('plan_playlist_items');
        Schema::connection('dbp_users')->dropIfExists('plan_days');
        Schema::connection('dbp_users')->dropIfExists('plans');
    }
}
