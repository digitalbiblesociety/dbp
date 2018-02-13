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
            $table->boolean('open_access')->default(1);
	        $table->boolean('connection_fab')->default(1);
	        $table->boolean('connection_dbs')->default(1);
	        $table->tinyInteger('priority')->default(0)->unsigned();
	        $table->timestamps();
        });

        Schema::create('bible_translations', function (Blueprint $table) {
        	$table->increments('id');
            $table->char('iso', 3)->index();
            $table->foreign('iso')->references('iso')->on('languages')->onDelete('cascade')->onUpdate('cascade');
            $table->string('bible_id',12);
            $table->foreign('bible_id')->references('id')->on('bibles')->onUpdate('cascade')->onDelete('cascade');
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
            $table->string('equivalent_id');
	        $table->integer('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->string('type')->nullable();
            $table->string('site')->nullable();
            $table->string('suffix')->default(NULL);
	        $table->timestamps();
        });

        Schema::create('bible_organizations', function($table) {
            $table->string('bible_id',12)->nullable();
            $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
	        $table->integer('organization_id')->unsigned()->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->string('relationship_type');
	        $table->timestamps();
        });

	    Schema::create('bible_links', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('bible_id',12);
		    $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
		    $table->string('type');
		    $table->text('url');
		    $table->string('title');
		    $table->string('provider')->nullable();
		    $table->integer('organization_id')->unsigned()->nullable();
		    $table->foreign('organization_id')->references('id')->on('organizations');
		    $table->timestamp('created_at')->useCurrent();
		    $table->timestamp('updated_at')->useCurrent();
	    });

        Schema::create('books', function (Blueprint $table) {
	        $table->char('id', 3)->primary(); // Code USFM
	        $table->char('id_usfx',2);
	        $table->string('id_osis',12);
            $table->tinyInteger('book_order')->unsigned(); // Genesis 01
	        $table->tinyInteger('testament_order')->unsigned();
	        $table->string('book_testament');
	        $table->string('book_group');
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


	    Schema::create('buckets', function (Blueprint $table) {
		    $table->string('id', 64)->unique();
		    $table->integer('organization_id')->unsigned();
		    $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');
		    $table->boolean('hidden')->default(0);
		    $table->timestamps();
	    });

	    Schema::create('bible_fileset_sizes', function (Blueprint $table) {
		    $table->tinyIncrements('id');
		    $table->char('set_size_code',9)->unique();
		    $table->string('name')->unique();
		    $table->timestamps();
	    });

	    Schema::create('bible_size_translations', function (Blueprint $table) {
		    $table->char('set_size_code', 9)->primary();
		    $table->foreign('set_size_code')->references('id')->on('bible_fileset_sizes')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('name');
		    $table->string('description');
		    $table->char('iso', 3)->index();
		    $table->foreign('iso')->references('iso')->on('languages')->onDelete('cascade')->onUpdate('cascade');
		    $table->timestamps();
	    });

	    Schema::create('bible_fileset_types', function (Blueprint $table) {
		    $table->tinyIncrements('id');
		    $table->string('set_type_code',16)->unique();
		    $table->string('name')->unique();
		    $table->timestamps();
	    });

	    Schema::create('bible_filesets', function (Blueprint $table) {
		    $table->string('id', 16)->primary();
		    $table->char('hash_id',12)->index();
		    $table->string('bucket_id', 64);
		    $table->foreign('bucket_id')->references('id')->on('buckets')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('set_type_code',16);
		    $table->foreign('set_type_code')->references('set_type_code')->on('bible_fileset_types')->onUpdate('cascade')->onDelete('cascade');
		    $table->char('set_size_code',3);
		    $table->foreign('set_size_code')->references('set_size_code')->on('bible_fileset_sizes')->onUpdate('cascade')->onDelete('cascade');
		    $table->boolean('hidden')->default(0);
		    $table->unique(['id', 'bucket_id', 'set_type'], 'unique_prefix_for_s3');
		    $table->timestamps();
	    });

	    Schema::create('bible_fileset_tags', function (Blueprint $table) {
		    $table->string('hash_id', 12)->index();
		    $table->foreign('hash_id')->references('hash_id')->on('bible_filesets')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('name');
		    $table->text('description');
		    $table->boolean('admin_only');
		    $table->text('notes');
		    $table->char('iso', 3)->index();
		    $table->foreign('iso')->references('iso')->on('languages');
		    $table->timestamps();
	    });

	    Schema::create('bible_fileset_connections', function (Blueprint $table) {
		    $table->char('hash_id',12);
		    $table->foreign('hash_id')->references('hash_id')->on('bible_filesets')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('bible_id',12)->index();
		    $table->foreign('bible_id')->references('id')->on('bibles')->onUpdate('cascade')->onDelete('cascade');
		    $table->timestamps();
	    });

	    Schema::create('bible_files', function (Blueprint $table) {
	    	$table->increments('id');
		    $table->string('hash_id', 12);
		    $table->foreign('hash_id',12)->references('hash_id')->on('bible_filesets')->onUpdate('cascade')->onDelete('cascade');
		    $table->char('book_id',3);
		    $table->foreign('book_id')->references('id')->on('books');
		    $table->tinyInteger('chapter_start')->unsigned()->nullable();
		    $table->tinyInteger('chapter_end')->unsigned()->nullable();
		    $table->tinyInteger('verse_start')->unsigned()->nullable();
		    $table->tinyInteger('verse_end')->unsigned()->nullable();
		    $table->string('file_name');
		    $table->unique(['hash_id','book_id', 'chapter_start', 'verse_start'], 'unique_bible_file_by_reference');
		    $table->timestamps();
	    });

	    Schema::create('bible_file_timestamps', function (Blueprint $table) {
	    	$table->increments('id');
		    $table->integer('file_id')->unsigned();
		    $table->foreign('file_id')->references('id')->on('bible_files');
		    $table->tinyInteger('verse_start')->unsigned()->nullable();
		    $table->tinyInteger('verse_end')->unsigned()->nullable();
		    $table->float('timestamp');
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
	    Schema::dropIfExists('bible_file_timestamps');
        Schema::dropIfExists('bible_files');
	    Schema::dropIfExists('bible_fileset_connections');
	    Schema::dropIfExists('bible_size_translations');
	    Schema::dropIfExists('bible_sizes');
	    Schema::dropIfExists('bible_fileset_tags');
	    Schema::dropIfExists('bible_filesets');
	    Schema::dropIfExists('bible_fileset_types');
	    Schema::dropIfExists('bible_fileset_sizes');
	    Schema::dropIfExists('buckets');

        Schema::dropIfExists('book_translations');
	    Schema::dropIfExists('bible_books');
        Schema::dropIfExists('book_codes');

        Schema::dropIfExists('books');
	    Schema::dropIfExists('bible_links');
	    Schema::dropIfExists('bible_organizations');
        Schema::dropIfExists('bible_translations');
        Schema::dropIfExists('bible_equivalents');
        Schema::dropIfExists('bibles');
    }
}
