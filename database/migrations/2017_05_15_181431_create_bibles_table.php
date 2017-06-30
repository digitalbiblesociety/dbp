<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bibles', function (Blueprint $table) {
            $table->string('abbr',12)->unique()->onUpdate('cascade')->onDelete('cascade');
            $table->char('glotto_id', 8)->index();
            $table->foreign('glotto_id')->references('id')->on('geo.languages');
            $table->string('date');
            $table->char('scope', 4)->nullable();
            $table->char('script', 4)->nullable();
            $table->foreign('script')->references('script')->on('geo.alphabets');
            $table->text('derived')->nullable();
            $table->string('copyright')->nullable();
            $table->string('in_progress')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::create('bible_translations', function (Blueprint $table) {
            $table->char('glotto_id', 8)->index();
            $table->foreign('glotto_id')->references('id')->on('geo.languages')->onDelete('cascade')->onUpdate('cascade');
            $table->string('abbr',12);
            $table->foreign('abbr')->references('abbr')->on('bibles')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('vernacular')->default(false);
            $table->string('name');
            $table->text('description');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::create('bible_equivalents', function (Blueprint $table) {
            $table->string('abbr', 12);
            $table->foreign('abbr')->references('abbr')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
            $table->string('equivalent_id');
            $table->integer('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->string('type');
            $table->string('site');
            $table->string('suffix')->default(NULL);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::create('bible_organization', function($table) {
            $table->string('bible_abbr',12)->index();
            $table->foreign('bible_abbr')->references('abbr')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->string('contributionType');
        });

        Schema::create('books', function (Blueprint $table) {
	        $table->char('id', 2)->primary();
            $table->tinyInteger('book_order'); // Genesis 01
            $table->Integer('chapters')->default(0);
            $table->Integer('verses')->default(0);
            $table->string('name');
            $table->text('notes');
            $table->text('description');
        });

        Schema::create('book_codes', function (Blueprint $table) {
	        $table->string('code');
	        $table->string('type');
	        $table->char('book_id', 2);
	        $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('bible_book', function (Blueprint $table) {
            $table->string('abbr', 12);
            $table->foreign('abbr')->references('abbr')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
            $table->char('book_id', 2);
            $table->foreign('book_id')->references('id')->on('books');
            $table->string('name');
            $table->string('name_short');
        });

        Schema::create('book_translations', function (Blueprint $table) {
            $table->char('glotto', 8)->index();
            $table->foreign('glotto')->references('id')->on('geo.languages')->onDelete('cascade')->onUpdate('cascade');
            $table->char('book_id', 2);
            $table->foreign('book_id')->references('id')->on('books');
            $table->string('name');
            $table->text('name_long');
	        $table->string('name_short');
	        $table->string('name_abbreviation');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::create('audio', function (Blueprint $table) {
            $table->increments('id');
            $table->char('glotto', 8)->index();
            $table->foreign('glotto')->references('id')->on('geo.languages')->onDelete('cascade')->onUpdate('cascade');
            $table->string('abbr', 12);
            $table->foreign('abbr')->references('abbr')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
            $table->char('book_id', 2);
            $table->foreign('book_id')->references('id')->on('books');
            $table->integer('chapter_start')->unsigned();
            $table->integer('chapter_end')->unsigned();
            $table->integer('verse_start')->unsigned();
            $table->integer('verse_end')->unsigned();
            $table->string('url_download');  // usually a link to a file
            $table->string('url_stream');    // With Streaming Analytics
            $table->string('url_share');     // to to a site
            $table->integer('duration');     // in milliseconds
            $table->text('embed_code');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::create('audio_translations', function (Blueprint $table) {
            $table->char('glotto', 8)->index();
            $table->foreign('glotto')->references('id')->on('geo.languages')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('audio_id')->unsigned();
            $table->foreign('audio_id')->references('id')->on('audio')->onDelete('cascade')->onUpdate('cascade');
            $table->string('title');
            $table->text('description');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::create('audio_organization', function (Blueprint $table) {
            $table->integer('audio_id')->unsigned();
            $table->foreign('audio_id')->references('id')->on('audio');
            $table->integer('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->string('contribution_type');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audio_translations');
        Schema::dropIfExists('audio_organization');
        Schema::dropIfExists('audio');
        Schema::dropIfExists('book_translations');
        Schema::dropIfExists('bible_book');
	    Schema::dropIfExists('book_codes');
        Schema::dropIfExists('books');
	    Schema::dropIfExists('bible_organization');
        Schema::dropIfExists('bible_translations');
        Schema::dropIfExists('bible_equivalents');
        Schema::dropIfExists('bibles');
    }
}
