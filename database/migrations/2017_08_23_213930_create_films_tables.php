<?php



use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilmsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp')->hasTable('videos')) {
            Schema::connection('dbp')->create('videos', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('language_id')->unsigned()->nullable();
                $table->foreign('language_id', 'FK_languages_videos')->references('id')->on(config('database.connections.dbp.database').'.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->string('bible_id', 12)->nullable();
                $table->foreign('bible_id', 'FK_bibles_videos')->references('id')->on(config('database.connections.dbp.database').'.bibles')->onDelete('cascade')->onUpdate('cascade');
                $table->string('series')->nullable();
                $table->string('episode')->nullable();
                $table->string('section')->nullable();
                $table->string('picture')->nullable();
                $table->integer('duration');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('video_sources')) {
            Schema::connection('dbp')->create('video_sources', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('video_id')->unsigned()->nullable();
                $table->foreign('video_id', 'FK_videos_video_sources')->references('id')->on(config('database.connections.dbp.database').'.videos')->onDelete('cascade')->onUpdate('cascade');
                $table->string('url');
                $table->string('encoding')->nullable();
                $table->string('resolution');
                $table->integer('size');
                $table->string('url_type');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('video_tags')) {
            Schema::connection('dbp')->create('video_tags', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('video_id')->unsigned()->nullable();
                $table->foreign('video_id', 'FK_videos_video_tags')->references('id')->on(config('database.connections.dbp.database').'.videos')->onDelete('cascade')->onUpdate('cascade');
                $table->string('category');
                $table->string('tag_type');
                $table->string('tag');
                $table->integer('language_id')->unsigned()->nullable();
                $table->foreign('language_id', 'FK_languages_video_tags')->references('id')->on(config('database.connections.dbp.database').'.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->integer('organization_id')->unsigned()->nullable();
                $table->foreign('organization_id', 'FK_organizations_video_tags')->references('id')->on(config('database.connections.dbp.database').'.organizations');
                $table->char('book_id', 3)->nullable();
                $table->foreign('book_id', 'FK_books_video_tags')->references('id')->on(config('database.connections.dbp.database').'.books');
                $table->integer('chapter_start')->unsigned()->nullable();
                $table->integer('chapter_end')->unsigned()->nullable();
                $table->integer('verse_start')->unsigned()->nullable();
                $table->integer('verse_end')->unsigned()->nullable();
                $table->float('time_begin')->unsigned()->nullable();
                $table->float('time_end')->unsigned()->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('video_translations')) {
            Schema::connection('dbp')->create('video_translations', function (Blueprint $table) {
                $table->integer('language_id', 8)->unsigned();
                $table->foreign('language_id', 'FK_languages_video_translations')->references('id')->on(config('database.connections.dbp.database').'.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->integer('video_id')->unsigned();
                $table->foreign('video_id', 'FK_videos_video_translations')->references('id')->on(config('database.connections.dbp.database').'.videos')->onDelete('cascade')->onUpdate('cascade');
                $table->string('title');
                $table->text('description');
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
        Schema::connection('dbp')->dropIfExists('video_tags');
        Schema::connection('dbp')->dropIfExists('video_organization');
        Schema::connection('dbp')->dropIfExists('video_translations');
        Schema::connection('dbp')->dropIfExists('video_sources');
        Schema::connection('dbp')->dropIfExists('videos');
    }
}
