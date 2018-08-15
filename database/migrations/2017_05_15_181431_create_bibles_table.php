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
    	// if(!Schema::connection('dbp')->hasTable('organizations')) {

        if(!Schema::connection('dbp')->hasTable('bibles')) {
	        Schema::connection('dbp')->create('bibles', function (Blueprint $table) {
		        $table->string('id', 12)->unique()->onUpdate('cascade')->onDelete('cascade');
		        $table->char('iso', 3);
		        $table->foreign('iso')->references('iso')->on('languages')->onDelete('cascade')->onUpdate('cascade');
		        $table->integer('language_id')->unsigned();
		        $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
		        $table->string('versification', 20);
		        $table->string('numeral_system_id', 20);
		        $table->foreign('numeral_system_id')->references('id')->on('numeral_systems')->onDelete('cascade')->onUpdate('cascade');
		        $table->string('date');
		        $table->char('scope', 4)->nullable();
		        $table->char('script', 4)->nullable();
		        $table->foreign('script')->references('script')->on('alphabets')->onDelete('cascade')->onUpdate('cascade');
		        $table->text('derived')->nullable();
		        $table->string('copyright')->nullable();
		        $table->string('in_progress')->nullable();
		        $table->tinyInteger('priority')->default(0)->unsigned();
		        $table->timestamps();
	        });
        }

        if(!Schema::connection('dbp')->hasTable('bible_translations')) {
	        Schema::connection('dbp')->create('bible_translations', function (Blueprint $table) {
		        $table->increments('id');
		        $table->char('iso', 3);
		        $table->foreign('iso')->references('iso')->on('languages')->onDelete('cascade')->onUpdate('cascade');
		        $table->integer('language_id')->unsigned();
		        $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
		        $table->string('bible_id', 12);
		        $table->foreign('bible_id')->references('id')->on('bibles')->onUpdate('cascade')->onDelete('cascade');
		        $table->boolean('vernacular')->default(false);
		        $table->boolean('vernacular_trade')->default(false);
		        $table->string('name');
		        $table->string('type')->nullable();
		        $table->string('features')->nullable();
		        $table->text('description')->nullable();
		        $table->text('notes')->nullable();
		        $table->timestamps();
	        });
        }

        if(!Schema::connection('dbp')->hasTable('bible_equivalents')) {
	        Schema::connection('dbp')->create('bible_equivalents', function (Blueprint $table) {
		        $table->string('bible_id', 12);
		        $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
		        $table->string('equivalent_id');
		        $table->integer('organization_id')->unsigned();
		        $table->foreign('organization_id')->references('id')->on('organizations');
		        $table->string('type')->nullable();
		        $table->string('site')->nullable();
		        $table->string('suffix')->default(null);
		        $table->timestamps();
	        });
        }

        if(!Schema::connection('dbp')->hasTable('bible_organizations')) {
	        Schema::connection('dbp')->create('bible_organizations', function ($table) {
		        $table->string('bible_id', 12)->nullable();
		        $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
		        $table->integer('organization_id')->unsigned()->nullable();
		        $table->foreign('organization_id')->references('id')->on('organizations');
		        $table->string('relationship_type');
		        $table->timestamps();
	        });
        }

	    if(!Schema::connection('dbp')->hasTable('bible_links')) {
		    Schema::connection('dbp')->create('bible_links', function (Blueprint $table) {
			    $table->increments('id');
			    $table->string('bible_id', 12);
			    $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
			    $table->string('type');
			    $table->text('url');
			    $table->string('title');
			    $table->string('provider')->nullable();
			    $table->boolean('visible')->default(1);
			    $table->integer('organization_id')->unsigned()->nullable();
			    $table->foreign('organization_id')->references('id')->on('organizations');
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }

        if(!Schema::connection('dbp')->hasTable('books')) {
	        Schema::connection('dbp')->create('books', function (Blueprint $table) {
		        $table->char('id', 3)->primary(); // Code USFM
		        $table->char('id_usfx', 2);
		        $table->string('id_osis', 12);
		        $table->string('book_testament');
		        $table->string('book_group');
		        $table->Integer('chapters')->nullable()->unsigned();
		        $table->Integer('verses')->nullable()->unsigned();
		        $table->string('name');
		        $table->text('notes');
		        $table->text('description');
		        $table->tinyInteger('testament_order')->unsigned()->nullable();
		        $table->tinyInteger('protestant_order')->unsigned()->nullable();
		        $table->tinyInteger('luther_order')->unsigned()->nullable();
		        $table->tinyInteger('synodal_order')->unsigned()->nullable();
		        $table->tinyInteger('german_order')->unsigned()->nullable();
		        $table->tinyInteger('kjva_order')->unsigned()->nullable();
		        $table->tinyInteger('vulgate_order')->unsigned()->nullable();
		        $table->tinyInteger('lxx_order')->unsigned()->nullable();
		        $table->tinyInteger('orthodox_order')->unsigned()->nullable();
		        $table->tinyInteger('nrsva_order')->unsigned()->nullable();
		        $table->tinyInteger('catholic_order')->unsigned()->nullable();
		        $table->tinyInteger('finnish_order')->unsigned()->nullable();
		        $table->timestamps();
	        });
        }

        if(!Schema::connection('dbp')->hasTable('bible_books')) {
	        Schema::connection('dbp')->create('bible_books', function (Blueprint $table) {
		        $table->string('bible_id', 12);
		        $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
		        $table->char('book_id', 3);
		        $table->foreign('book_id')->references('id')->on('books');
		        $table->string('name')->nullable();
		        $table->string('name_short')->nullable();
		        $table->string('chapters', 491)->nullable();
		        $table->timestamps();
	        });
        }

        if(!Schema::connection('dbp')->hasTable('book_translations')) {
	        Schema::connection('dbp')->create('book_translations', function (Blueprint $table) {
		        $table->integer('language_id')->unsigned();
		        $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
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
        }

	    if(!Schema::connection('dbp')->hasTable('buckets')) {
		    Schema::connection('dbp')->create('buckets', function (Blueprint $table) {
			    $table->string('id', 64)->unique();
			    $table->integer('organization_id')->unsigned();
			    $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');
			    $table->boolean('hidden')->default(0);
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('bible_fileset_sizes')) {
		    Schema::connection('dbp')->create('bible_fileset_sizes', function (Blueprint $table) {
			    $table->tinyIncrements('id');
			    $table->char('set_size_code', 9)->unique();
			    $table->string('name')->unique();
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('bible_size_translations')) {
		    Schema::connection('dbp')->create('bible_size_translations', function (Blueprint $table) {
			    $table->char('set_size_code', 9)->primary();
			    $table->foreign('set_size_code')->references('set_size_code')->on('bible_fileset_sizes')->onUpdate('cascade')->onDelete('cascade');
			    $table->integer('language_id')->unsigned();
			    $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
			    $table->string('name');
			    $table->string('description');
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('bible_fileset_types')) {
		    Schema::connection('dbp')->create('bible_fileset_types', function (Blueprint $table) {
			    $table->tinyIncrements('id');
			    $table->string('set_type_code', 16)->unique();
			    $table->string('name')->unique();
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('bible_filesets')) {
		    Schema::connection('dbp')->create('bible_filesets', function (Blueprint $table) {
			    $table->string('id', 16)->index();
			    $table->char('hash_id', 12)->index();
			    $table->string('bucket_id', 64);
			    $table->foreign('bucket_id')->references('id')->on('buckets')->onUpdate('cascade')->onDelete('cascade');
			    $table->string('set_type_code', 16);
			    $table->foreign('set_type_code')->references('set_type_code')->on('bible_fileset_types')->onUpdate('cascade')->onDelete('cascade');
			    $table->char('set_size_code', 9);
			    $table->foreign('set_size_code')->references('set_size_code')->on('bible_fileset_sizes')->onUpdate('cascade')->onDelete('cascade');
			    $table->boolean('hidden')->default(0);
			    $table->unique(['id', 'bucket_id', 'set_type_code'], 'unique_prefix_for_s3');
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('bible_fileset_relations')) {
		    Schema::connection('dbp')->create('bible_fileset_relations', function (Blueprint $table) {
			    $table->string('id', 16)->primary();
			    $table->char('parent_hash_id', 12)->index();
			    $table->foreign('parent_hash_id')->references('hash_id')->on('bible_filesets')->onUpdate('cascade')->onDelete('cascade');
			    $table->char('child_hash_id', 12)->index();
			    $table->foreign('child_hash_id')->references('hash_id')->on('bible_filesets')->onUpdate('cascade')->onDelete('cascade');
			    $table->string('relationship', 64);
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('bible_fileset_tags')) {
		    Schema::connection('dbp')->create('bible_fileset_tags', function (Blueprint $table) {
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
	    }

	    if(!Schema::connection('dbp')->hasTable('bible_fileset_connections')) {
		    Schema::connection('dbp')->create('bible_fileset_connections', function (Blueprint $table) {
			    $table->char('hash_id', 12);
			    $table->foreign('hash_id')->references('hash_id')->on('bible_filesets')->onUpdate('cascade')->onDelete('cascade');
			    $table->string('bible_id', 12)->index();
			    $table->foreign('bible_id')->references('id')->on('bibles')->onUpdate('cascade')->onDelete('cascade');
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('bible_files')) {
		    Schema::connection('dbp')->create('bible_files', function (Blueprint $table) {
			    $table->increments('id');
			    $table->string('hash_id', 12);
			    $table->foreign('hash_id', 12)->references('hash_id')->on('bible_filesets')->onUpdate('cascade')->onDelete('cascade');
			    $table->char('book_id', 3);
			    $table->foreign('book_id')->references('id')->on('books');
			    $table->tinyInteger('chapter_start')->unsigned()->nullable();
			    $table->tinyInteger('chapter_end')->unsigned()->nullable();
			    $table->tinyInteger('verse_start')->unsigned()->nullable();
			    $table->tinyInteger('verse_end')->unsigned()->nullable();
			    $table->string('file_name');
			    $table->integer('file_size')->unsigned()->nullable();
			    $table->integer('duration')->unsigned()->nullable();
			    $table->unique(['hash_id', 'book_id', 'chapter_start', 'verse_start'],
				    'unique_bible_file_by_reference');
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('bible_file_titles')) {
		    Schema::connection('dbp')->create('bible_file_titles', function (Blueprint $table) {
			    $table->integer('file_id')->unsigned();
			    $table->foreign('file_id')->references('id')->on('bible_files')->onUpdate('cascade')->onDelete('cascade');
			    $table->char('iso', 3);
			    $table->foreign('iso')->references('iso')->on('languages')->onDelete('cascade')->onUpdate('cascade');
			    $table->text('title');
			    $table->text('description')->nullable();
			    $table->text('key_words')->nullable();
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('bible_file_timestamps')) {
		    Schema::connection('dbp')->create('bible_file_timestamps', function (Blueprint $table) {
			    $table->integer('file_id')->unsigned();
			    $table->foreign('file_id')->references('id')->on('bible_files')->onUpdate('cascade')->onDelete('cascade');
			    $table->tinyInteger('verse_start')->unsigned()->nullable();
			    $table->tinyInteger('verse_end')->unsigned()->nullable();
			    $table->float('timestamp');
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('bible_fileset_copyrights')) {
		    Schema::connection('dbp')->create('bible_fileset_copyrights', function (Blueprint $table) {
			    $table->increments('id');
			    $table->string('hash_id', 12);
			    $table->foreign('hash_id')->references('hash_id')->on('bible_filesets')->onUpdate('cascade')->onDelete('cascade');
			    $table->string('date');
			    $table->text('copyright');
			    $table->text('description');
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('bible_fileset_copyright_roles')) {
		    Schema::connection('dbp')->create('bible_fileset_copyright_roles', function (Blueprint $table) {
		    	$table->increments('id');
			    $table->string('name');
			    $table->text('description');
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('bible_fileset_copyright_organizations')) {
		    Schema::connection('dbp')->create('bible_fileset_copyright_organizations', function (Blueprint $table) {
			    $table->increments('id');
			    $table->string('hash_id', 12);
			    $table->foreign('hash_id')->references('hash_id')->on('bible_filesets')->onUpdate('cascade')->onDelete('cascade');
			    $table->integer('organization_id')->unsigned();
			    $table->foreign('organization_id')->references('id')->on('organizations');
			    $table->integer('organization_role')->unsigned();
			    $table->foreign('organization_role')->references('id')->on('bible_fileset_copyright_roles');
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
	    Schema::connection('dbp')->dropIfExists('bible_file_titles');
	    Schema::connection('dbp')->dropIfExists('bible_file_timestamps');
	    Schema::connection('dbp')->dropIfExists('bible_file_translations');
	    Schema::connection('dbp')->dropIfExists('bible_fileset_relations');
	    Schema::connection('dbp')->dropIfExists('bible_files');
	    Schema::connection('dbp')->dropIfExists('bible_fileset_connections');
	    Schema::connection('dbp')->dropIfExists('bible_size_translations');
	    Schema::connection('dbp')->dropIfExists('bible_sizes');
	    Schema::connection('dbp')->dropIfExists('bible_fileset_tags');
	    Schema::connection('dbp')->dropIfExists('bible_filesets');
	    Schema::connection('dbp')->dropIfExists('bible_fileset_types');
	    Schema::connection('dbp')->dropIfExists('bible_fileset_sizes');
	    Schema::connection('dbp')->dropIfExists('buckets');

        Schema::connection('dbp')->dropIfExists('book_translations');
	    Schema::connection('dbp')->dropIfExists('bible_books');
        Schema::connection('dbp')->dropIfExists('book_codes');

        Schema::connection('dbp')->dropIfExists('books');
	    Schema::connection('dbp')->dropIfExists('bible_links');
	    Schema::connection('dbp')->dropIfExists('bible_organizations');
        Schema::connection('dbp')->dropIfExists('bible_translations');
        Schema::connection('dbp')->dropIfExists('bible_equivalents');
        Schema::connection('dbp')->dropIfExists('bibles');
    }
}
