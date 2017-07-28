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
	        $table->char('id', 3)->primary();
            $table->tinyInteger('book_order'); // Genesis 01
            $table->Integer('chapters')->nullable()->unsigned();
            $table->Integer('verses')->nullable()->unsigned();
            $table->string('name');
            $table->text('notes');
            $table->text('description');
        });

        Schema::create('book_codes', function (Blueprint $table) {
	        $table->string('code', 16);
	        $table->string('type', 8);
	        $table->char('book_id', 3);
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
            $table->char('book_id', 3);
            $table->foreign('book_id')->references('id')->on('books');
            $table->string('name');
            $table->text('name_long');
	        $table->string('name_short');
	        $table->string('name_abbreviation');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

	    Schema::create('bible_audio', function (Blueprint $table) {
	    	$table->increments('id');
		    $table->string('bible_id', 12);
		    $table->foreign('bible_id')->references('abbr')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
		    $table->char('book_id',3);
		    $table->foreign('book_id')->references('id')->on('books');
		    $table->tinyInteger('chapter_start')->unsigned()->nullable();
		    $table->tinyInteger('chapter_end')->unsigned()->nullable();
		    $table->tinyInteger('verse_start')->unsigned()->nullable();
		    $table->tinyInteger('verse_end')->unsigned()->nullable();
		    $table->string('order');
		    $table->string('filename');
		    $table->timestamp('created_at')->useCurrent();
		    $table->timestamp('updated_at')->useCurrent();
	    });

        Schema::create('bible_audio_organization', function (Blueprint $table) {
            $table->integer('audio_id')->unsigned();
            $table->foreign('audio_id')->references('id')->on('audio');
            $table->integer('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->string('contribution_type');
        });

	    Schema::create('bible_text', function (Blueprint $table) {
		    $table->string('verse_id');
		    $table->string('bible_id', 12);
		    $table->foreign('bible_id')->references('abbr')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
		    $table->char('book_id',3);
		    $table->foreign('book_id')->references('id')->on('books');
		    $table->tinyInteger('chapter_number')->unsigned();
		    $table->tinyInteger('verse_number')->unsigned();
		    $table->text('verse_text');
		    $table->timestamp('created_at')->useCurrent();
		    $table->timestamp('updated_at')->useCurrent();
	    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('bible_texts');
        Schema::dropIfExists('bible_audio_organization');
        Schema::dropIfExists('bible_audio');
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
