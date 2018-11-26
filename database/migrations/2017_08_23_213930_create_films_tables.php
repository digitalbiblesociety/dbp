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
                $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
                $table->string('bible_id', 12)->nullable();
                $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
                $table->string('series')->nullable();
                $table->string('episode')->nullable();
                $table->string('section')->nullable();
                $table->string('picture')->nullable();
                $table->integer('duration');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
        }

        if (!Schema::connection('dbp')->hasTable('video_sources')) {
            Schema::connection('dbp')->create('video_sources', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('video_id')->unsigned()->nullable();
                $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade')->onUpdate('cascade');
                $table->string('url');
                $table->string('encoding')->nullable();
                $table->string('resolution');
                $table->integer('size');
                $table->string('url_type');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
        }

        if (!Schema::connection('dbp')->hasTable('video_tags')) {
            Schema::connection('dbp')->create('video_tags', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('video_id')->unsigned()->nullable();
                $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade')->onUpdate('cascade');

                // General Info
                $table->string('category'); // related_video
                $table->string('tag_type'); // topic
                $table->string('tag'); // "El topico"
                $table->integer('language_id')->unsigned()->nullable();
                $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
                $table->integer('organization_id')->unsigned()->nullable();
                $table->foreign('organization_id')->references('id')->on('organizations');

                // Book and Chapter Linkage
                $table->char('book_id', 3)->nullable();
                $table->foreign('book_id')->references('id')->on('books');
                $table->integer('chapter_start')->unsigned()->nullable();
                $table->integer('chapter_end')->unsigned()->nullable();
                $table->integer('verse_start')->unsigned()->nullable();
                $table->integer('verse_end')->unsigned()->nullable();

                // Video Time Markers
                $table->float('time_begin')->unsigned()->nullable();
                $table->float('time_end')->unsigned()->nullable();

                // Timestamps
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
        }

        if (!Schema::connection('dbp')->hasTable('video_translations')) {
            Schema::connection('dbp')->create('video_translations', function (Blueprint $table) {
                $table->integer('language_id', 8)->unsigned();
                $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
                $table->integer('video_id')->unsigned();
                $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade')->onUpdate('cascade');
                $table->string('title');
                $table->text('description');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
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
