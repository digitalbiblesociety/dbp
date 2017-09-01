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
            $table->string('id',12)->unique()->onUpdate('cascade')->onDelete('cascade');
            $table->char('iso', 3)->index();
            $table->foreign('iso')->references('iso')->on('languages');
            $table->string('date');
            $table->char('scope', 4)->nullable();
            $table->char('script', 4)->nullable();
            $table->foreign('script')->references('script')->on('alphabets');
            $table->text('derived')->nullable();
            $table->string('copyright')->nullable();
            $table->string('in_progress')->nullable();
	        $table->timestamps();
        });

	    Schema::create('bible_variations', function (Blueprint $table) {
		    $table->string('variation_id',12);
		    $table->foreign('variation_id')->references('id')->on('bibles')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('id',12);
		    $table->foreign('id')->references('id')->on('bibles')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('date');
		    $table->char('scope', 4)->nullable();
		    $table->char('script', 4)->nullable();
		    $table->foreign('script')->references('script')->on('alphabets');
		    $table->text('derived')->nullable();
		    $table->string('copyright')->nullable();
		    $table->string('in_progress')->nullable();
		    $table->timestamps();
	    });

        Schema::create('bible_translations', function (Blueprint $table) {
            $table->char('iso', 3)->index();
            $table->foreign('iso')->references('iso')->on('languages')->onDelete('cascade')->onUpdate('cascade');
            $table->string('bible_id',12);
            $table->foreign('bible_id')->references('id')->on('bibles')->onUpdate('cascade')->onDelete('cascade');
	        $table->string('bible_variation_id',12)->nullable();
	        $table->foreign('bible_variation_id')->references('id')->on('bible_variations')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('vernacular')->default(false);
            $table->string('name');
	        $table->string('type')->nullable();
	        $table->string('features')->nullable();
            $table->text('description')->nullable();
	        $table->text('notes')->nullable();
	        $table->timestamps();
        });

        Schema::create('bible_equivalents', function (Blueprint $table) {
            $table->string('bible_id', 12);
            $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
	        $table->string('bible_variation_id',12)->nullable();
	        $table->foreign('bible_variation_id')->references('id')->on('bible_variations')->onUpdate('cascade')->onDelete('cascade');
            $table->string('equivalent_id');
            $table->integer('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->string('type');
            $table->string('site');
            $table->string('suffix')->default(NULL);
	        $table->timestamps();
        });

        Schema::create('bible_organizations', function($table) {
            $table->string('bible_id',12)->nullable();
            $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
	        $table->string('bible_variation_id',12)->nullable();
	        $table->foreign('bible_variation_id')->references('id')->on('bible_variations')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->string('relationship_type');
	        $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
	        $table->char('id', 3)->primary(); // Code USFM
	        $table->char('id_usfx',2);
	        $table->string('id_osis',12);
            $table->tinyInteger('book_order'); // Genesis 01
            $table->Integer('chapters')->nullable()->unsigned();
            $table->Integer('verses')->nullable()->unsigned();
            $table->string('name');
            $table->text('notes');
            $table->text('description');
	        $table->timestamps();
        });

        Schema::create('bible_books', function (Blueprint $table) {
            $table->string('bible_id', 12);
            $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
            $table->char('book_id', 3);
            $table->foreign('book_id')->references('id')->on('books');
            $table->string('name')->nullable();
            $table->string('name_short')->nullable();
            $table->string('chapters')->nullable();
	        $table->timestamps();
        });

        Schema::create('book_translations', function (Blueprint $table) {
            $table->char('iso', 3)->index();
            $table->foreign('iso')->references('iso')->on('languages')->onDelete('cascade')->onUpdate('cascade');
            $table->char('book_id', 3);
            $table->foreign('book_id')->references('id')->on('books');
            $table->string('name');
            $table->text('name_long');
	        $table->string('name_short');
	        $table->string('name_abbreviation');
	        $table->timestamps();
        });

	    Schema::create('bible_audio', function (Blueprint $table) {
	    	$table->increments('id');
		    $table->string('bible_id', 12);
		    $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
		    $table->string('bible_variation_id',12)->nullable();
		    $table->foreign('bible_variation_id')->references('id')->on('bible_variations')->onUpdate('cascade')->onDelete('cascade');
		    $table->char('book_id',3);
		    $table->foreign('book_id')->references('id')->on('books');
		    $table->tinyInteger('chapter_start')->unsigned()->nullable();
		    $table->tinyInteger('chapter_end')->unsigned()->nullable();
		    $table->tinyInteger('verse_start')->unsigned()->nullable();
		    $table->tinyInteger('verse_end')->unsigned()->nullable();
		    $table->string('filename');
		    $table->timestamps();
	    });

	    Schema::create('bible_audio_references', function (Blueprint $table) {
		    $table->integer('audio_id')->unsigned();
		    $table->foreign('audio_id')->references('id')->on('bible_audio');
		    $table->char('book_id',3);
		    $table->foreign('book_id')->references('id')->on('books');
		    $table->tinyInteger('chapter_start')->unsigned()->nullable();
		    $table->tinyInteger('chapter_end')->unsigned()->nullable();
		    $table->tinyInteger('verse_start')->unsigned()->nullable();
		    $table->tinyInteger('verse_end')->unsigned()->nullable();
		    $table->float('timestamp');
		    $table->timestamps();
	    });

        Schema::create('bible_audio_organization', function (Blueprint $table) {
	        $table->string('bible_variation_id',12);
	        $table->foreign('bible_variation_id')->references('id')->on('bible_variations')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->string('contribution_type');
	        $table->timestamps();
        });

	    Schema::create('bible_text', function (Blueprint $table) {
		    $table->string('id', 32)->primary()->unique()->index();
		    $table->string('bible_id', 12);
		    $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
		    $table->string('bible_variation_id',12)->nullable();
		    $table->foreign('bible_variation_id')->references('id')->on('bible_variations')->onUpdate('cascade')->onDelete('cascade');
		    $table->char('book_id',3);
		    $table->foreign('book_id')->references('id')->on('books');
		    $table->tinyInteger('chapter_number')->unsigned();
		    $table->tinyInteger('verse_start')->unsigned();
		    $table->tinyInteger('verse_end')->unsigned()->nullable();
		    $table->text('verse_text');
		    $table->timestamps();
	    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('bible_text');
        Schema::dropIfExists('bible_audio_organization');
	    Schema::dropIfExists('bible_audio_references');
        Schema::dropIfExists('bible_audio');
        Schema::dropIfExists('book_translations');
	    Schema::dropIfExists('bible_books');
        Schema::dropIfExists('book_codes');
        Schema::dropIfExists('books');
	    Schema::dropIfExists('bible_organizations');
        Schema::dropIfExists('bible_translations');
        Schema::dropIfExists('bible_equivalents');
	    Schema::dropIfExists('bible_variations');
        Schema::dropIfExists('bibles');
    }
}
