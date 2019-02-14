<?php



use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersBiblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp_users')->hasTable('projects')) {
            Schema::connection('dbp_users')->create('projects', function (Blueprint $table) {
                $table->smallInteger('id')->unsigned()->primary();
                $table->string('name');
                $table->string('url_avatar')->nullable();
                $table->string('url_avatar_icon')->nullable();
                $table->string('url_site')->nullable();
                $table->text('description')->nullable();
                $table->boolean('sensitive')->default(false);
                $table->timestamp('deleted_at')->nullable()->default(NULL);
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }
        if (!Schema::connection('dbp_users')->hasTable('project_oauth_providers')) {
            Schema::connection('dbp_users')->create('project_oauth_providers', function (Blueprint $table) {
                $table->increments('id');
                $table->smallInteger('project_id')->unsigned();
                $table->foreign('project_id', 'FK_projects_project_oauth_providers')->references('id')->on(config('database.connections.dbp_users.database').'.projects')->onDelete('cascade')->onUpdate('cascade');
                $table->string('name');
                $table->string('client_id');
                $table->string('client_secret');
                $table->string('callback_url');
                $table->text('description');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }
        if (!Schema::connection('dbp_users')->hasTable('project_members')) {
            Schema::connection('dbp_users')->create('project_members', function (Blueprint $table) {
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id', 'FK_users_project_members')->references('id')->on(config('database.connections.dbp_users.database').'.users')->onDelete('cascade')->onUpdate('cascade');
                $table->smallInteger('project_id')->unsigned();
                $table->foreign('project_id', 'FK_projects_project_members')->references('id')->on(config('database.connections.dbp_users.database').'.projects')->onDelete('cascade')->onUpdate('cascade');
                $table->tinyInteger('role_id')->unsigned();
                $table->foreign('role_id', 'FK_roles_project_members')->references('id')->on(config('database.connections.dbp_users.database').'.roles')->onDelete('cascade')->onUpdate('cascade');
                $table->boolean('subscribed')->default(false)->nullable();
                $table->string('token', 16);
                $table->timestamp('created_at')->useCurrent();
            });
        }
        if (!Schema::connection('dbp_users')->hasTable('user_accounts')) {
            Schema::connection('dbp_users')->create('user_accounts', function (Blueprint $table) {
                $table->increments('id');
                $table->index(['user_id','project_id','provider_id']);
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id', 'FK_users_user_accounts')->references('id')->on(config('database.connections.dbp_users.database').'.users')->onUpdate('cascade');
                $table->smallInteger('project_id')->unsigned();
                $table->foreign('project_id', 'FK_projects_user_accounts')->references('id')->on(config('database.connections.dbp_users.database').'.projects')->onDelete('cascade')->onUpdate('cascade');
                $table->string('provider_id', 12);
                $table->string('provider_user_id');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }
        if (!Schema::connection('dbp_users')->hasTable('user_notes')) {
            Schema::connection('dbp_users')->create('user_notes', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('v2_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id', 'FK_users_user_notes')->references('id')->on(config('database.connections.dbp_users.database').'.users')->onUpdate('cascade')->onDelete('cascade');
                $table->string('bible_id', 12);
                $table->foreign('bible_id', 'FK_bibles_user_notes')->references('id')->on(config('database.connections.dbp.database').'.bibles')->onDelete('cascade')->onUpdate('cascade');
                $table->char('book_id', 3);
                $table->foreign('book_id', 'FK_books_user_notes')->references('id')->on(config('database.connections.dbp.database').'.books')->onUpdate('cascade')->onDelete('cascade');
                $table->tinyInteger('chapter')->unsigned();
                $table->tinyInteger('verse_start')->unsigned();
                $table->tinyInteger('verse_end')->unsigned()->nullable();
                $table->text('notes')->nullable();
                $table->boolean('bookmark')->default(false);
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp_users')->hasTable('user_bookmarks')) {
            Schema::connection('dbp_users')->create('user_bookmarks', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('v2_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id', 'FK_users_user_bookmarks')->references('id')->on(config('database.connections.dbp_users.database').'.users')->onUpdate('cascade')->onDelete('cascade');
                $table->string('bible_id', 12);
                $table->foreign('bible_id', 'FK_bibles_user_bookmarks')->references('id')->on(config('database.connections.dbp.database').'.bibles')->onDelete('cascade')->onUpdate('cascade');
                $table->char('book_id', 3);
                $table->foreign('book_id', 'FK_books_user_bookmarks')->references('id')->on(config('database.connections.dbp.database').'.books')->onUpdate('cascade')->onDelete('cascade');
                $table->tinyInteger('chapter')->unsigned();
                $table->tinyInteger('verse_start')->unsigned();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp_users')->hasTable('user_highlight_colors')) {
            Schema::connection('dbp_users')->create('user_highlight_colors', function (Blueprint $table) {
                $table->increments('id');
                $table->string('color');
                $table->char('hex', 6);
                $table->integer('red')->unsigned();
                $table->integer('green')->unsigned();
                $table->integer('blue')->unsigned();
                $table->float('opacity', 2, 1);
            });
        }

        if (!Schema::connection('dbp_users')->hasTable('user_highlights')) {
            Schema::connection('dbp_users')->create('user_highlights', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('v2_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id', 'FK_users_user_highlights')->references('id')->on(config('database.connections.dbp_users.database').'.users')->onUpdate('cascade')->onDelete('cascade');
                $table->string('bible_id', 12);
                $table->foreign('bible_id', 'FK_bibles_user_highlights')->references('id')->on(config('database.connections.dbp.database').'.bibles')->onDelete('cascade')->onUpdate('cascade');
                $table->char('book_id', 3);
                $table->foreign('book_id', 'FK_books_user_highlights')->references('id')->on(config('database.connections.dbp.database').'.books');
                $table->tinyInteger('chapter')->unsigned();
                $table->tinyInteger('verse_start')->unsigned();
                $table->integer('highlight_start')->unsigned();
                $table->integer('highlighted_words')->unsigned();
                $table->integer('highlighted_color')->unsigned();
                $table->foreign('highlighted_color', 'FK_user_highlight_colors_user_highlights')->references('id')->on(config('database.connections.dbp_users.database').'.user_highlight_colors')->onDelete('cascade')->onUpdate('cascade');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }
        if (!Schema::connection('dbp_users')->hasTable('user_annotation_tags')) {
            Schema::connection('dbp_users')->create('user_annotation_tags', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('note_id')->unsigned()->nullable();
                $table->foreign('note_id', 'FK_user_notes_user_annotation_tags')->references('id')->on(config('database.connections.dbp_users.database').'.user_notes')->onUpdate('cascade')->onDelete('cascade');
                $table->integer('bookmark_id')->unsigned()->nullable();
                $table->foreign('bookmark_id', 'FK_user_bookmarks_user_annotation_tags')->references('id')->on(config('database.connections.dbp_users.database').'.user_bookmarks')->onUpdate('cascade')->onDelete('cascade');
                $table->integer('highlight_id')->unsigned()->nullable();
                $table->foreign('highlight_id', 'FK_user_highlights_user_annotation_tags')->references('id')->on(config('database.connections.dbp_users.database').'.user_highlights')->onUpdate('cascade')->onDelete('cascade');
                $table->string('type', 64)->index();
                $table->string('value', 64);
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
            DB::connection('dbp_users')->statement('ALTER TABLE user_annotation_tags ADD CONSTRAINT CHECK (note_id IS NOT NULL OR bookmark_id IS NOT NULL OR highlight_id IS NOT NULL)');
        }
        if (!Schema::connection('dbp')->hasTable('access_groups')) {
            Schema::connection('dbp')->create('access_groups', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 64);
                $table->text('description');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }
        if (!Schema::connection('dbp')->hasTable('access_types')) {
            Schema::connection('dbp')->create('access_types', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 24);
                $table->char('country_id', 2)->nullable();
                $table->foreign('country_id', 'FK_countries_access_types_id')->references('id')->on(config('database.connections.dbp.database').'.countries')->onUpdate('cascade');
                $table->char('continent_id', 2)->nullable();
                $table->foreign('continent_id', 'FK_countries_access_types_continent')->references('continent')->on(config('database.connections.dbp.database').'.countries')->onUpdate('cascade');
                $table->boolean('allowed');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }
        if (!Schema::connection('dbp')->hasTable('access_group_types')) {
            Schema::connection('dbp')->create('access_group_types', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('access_group_id')->unsigned();
                $table->foreign('access_group_id', 'FK_access_groups_access_group_types')->references('id')->on(config('database.connections.dbp.database').'.access_groups')->onUpdate('cascade')->onDelete('cascade');
                $table->integer('access_type_id')->unsigned();
                $table->foreign('access_type_id', 'FK_access_types_access_group_types')->references('id')->on(config('database.connections.dbp.database').'.access_types')->onUpdate('cascade')->onDelete('cascade');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }
        if (!Schema::connection('dbp')->hasTable('access_group_filesets')) {
            Schema::connection('dbp')->create('access_group_filesets', function (Blueprint $table) {
                $table->integer('access_group_id')->unsigned();
                $table->foreign('access_group_id', 'FK_access_groups_access_group_filesets')->references('id')->on(config('database.connections.dbp.database').'.access_groups')->onUpdate('cascade')->onDelete('cascade');
                $table->char('hash_id', 12)->index();
                $table->foreign('hash_id', 'FK_bible_filesets_access_group_filesets')->references('hash_id')->on(config('database.connections.dbp.database').'.bible_filesets')->onUpdate('cascade')->onDelete('cascade');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }
        if (!Schema::connection('dbp_users')->hasTable('access_group_api_keys')) {
            Schema::connection('dbp_users')->create('access_group_api_keys', function (Blueprint $table) {
                $table->integer('access_group_id')->unsigned();
                $table->foreign('access_group_id', 'FK_access_groups_access_group_api_keys')->references('id')->on(config('database.connections.dbp.database').'.access_groups')->onUpdate('cascade')->onDelete('cascade');
                $table->integer('key_id')->unsigned();
                $table->foreign('key_id', 'FK_user_keys_access_group_api_keys')->references('id')->on(config('database.connections.dbp_users.database').'.user_keys')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::connection('dbp_users')->dropIfExists('user_annotation_tags');
        Schema::connection('dbp_users')->dropIfExists('user_accounts');
        Schema::connection('dbp_users')->dropIfExists('user_notes');
        Schema::connection('dbp_users')->dropIfExists('user_highlights');
        Schema::connection('dbp_users')->dropIfExists('project_oauth_providers');
        Schema::connection('dbp_users')->dropIfExists('project_members');
        Schema::connection('dbp_users')->dropIfExists('projects');
        Schema::connection('dbp')->dropIfExists('access_group_types');
        Schema::connection('dbp')->dropIfExists('access_group_filesets');
        Schema::connection('dbp_users')->dropIfExists('access_group_keys');
        Schema::connection('dbp')->dropIfExists('access_type_translations');
        Schema::connection('dbp')->dropIfExists('access_types');
        Schema::connection('dbp')->dropIfExists('access_groups');
    }
}
