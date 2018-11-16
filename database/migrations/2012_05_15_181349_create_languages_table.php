<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

		if(!Schema::connection('dbp')->hasTable('countries') ) {
			Schema::connection('dbp')->create('countries', function (Blueprint $table) {
				$table->char('id', 2)->primary();
				$table->char('iso_a3', 3)->unique();
				$table->char('fips', 2)->nullable();
				$table->boolean('wfb')->default(0);
				$table->boolean('ethnologue')->default(0);
				$table->char('continent', 2);
				$table->string('name');
				$table->text('introduction')->nullable();
				$table->text('overview')->nullable();
				$table->timestamp('created_at')->useCurrent();
				$table->timestamp('updated_at')->useCurrent();
			});
        }

	    if(!Schema::connection('dbp')->hasTable('language_status')) {
		    Schema::connection('dbp')->create('language_status', function (Blueprint $table) {
			    $table->char('id',2)->primary();
			    $table->string('title');
			    $table->text('description')->nullable();
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }

		if(!Schema::connection('dbp')->hasTable('languages')) {
			Schema::connection('dbp')->create('languages', function (Blueprint $table) {
				$table->increments('id');
				$table->char('glotto_id', 8)->nullable()->unique();
				$table->char('iso', 3)->nullable()->index();
				$table->char('iso2B', 3)->nullable()->unique();
				$table->char('iso2T', 3)->nullable()->unique();
				$table->char('iso1', 2)->nullable()->unique();
				$table->string('name');
				$table->string('maps')->nullable();
				$table->string('level')->nullable();
				$table->text('development')->nullable();
				$table->text('use')->nullable();
				$table->text('location')->nullable();
				$table->text('area')->nullable();
				$table->text('population')->nullable();
				$table->text('population_notes')->nullable();
				$table->text('notes')->nullable();
				$table->text('typology')->nullable();
				$table->text('writing')->nullable();
				$table->text('description')->nullable();
				$table->float('latitude', 11, 7)->nullable();
				$table->float('longitude', 11, 7)->nullable();
                $table->char('country_id', 2)->nullable()->default(null);
				$table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
				$table->char('status_id',2)->nullable();
				$table->foreign('status_id')->references('id')->on('language_status')->onUpdate('cascade');
				$table->text('status_notes')->nullable();
				$table->timestamp('created_at')->useCurrent();
				$table->timestamp('updated_at')->useCurrent();
			});
	        DB::connection('dbp')->statement('ALTER TABLE languages ADD CONSTRAINT CHECK (iso IS NOT NULL OR glotto_id IS NOT NULL)');
        }

		    if(!Schema::connection('dbp')->hasTable('language_translations') ) {
		        Schema::connection('dbp')->create('language_translations', function (Blueprint $table) {
			        $table->increments('id');
				    $table->integer('language_source_id')->unsigned();
				    $table->foreign('language_source_id')->references('id')->on('languages')->onUpdate('cascade');
				    $table->integer('language_translation_id')->unsigned();
				    $table->foreign('language_translation_id')->references('id')->on('languages')->onUpdate('cascade');
				    $table->string('name');
				    $table->text('description')->nullable();
				    $table->tinyInteger('priority')->nullable();
				    $table->unique(['language_source_id', 'language_translation_id', 'name'], 'unq_language_translations');
				    $table->timestamp('created_at')->useCurrent();
				    $table->timestamp('updated_at')->useCurrent();
		        });
            }

		    if(!Schema::connection('dbp')->hasTable('language_bibleInfo')) {
		    Schema::connection('dbp')->create('language_bibleInfo', function (Blueprint $table) {
			    $table->integer('language_id')->unsigned();
			    $table->foreign('language_id')->references('id')->on('languages')->onUpdate('cascade');
			    $table->tinyInteger('bible_status')->nullable();
			    $table->boolean('bible_translation_need')->nullable();
			    $table->integer('bible_year')->nullable();
			    $table->integer('bible_year_newTestament')->nullable();
			    $table->integer('bible_year_portions')->nullable();
			    $table->text('bible_sample_text')->nullable();
			    $table->string('bible_sample_img')->nullable();
			    $table->timestamp('created_at')->useCurrent();

			    $table->timestamp('updated_at')->useCurrent();
		    });
            }

		if(!Schema::connection('dbp')->hasTable('language_dialects')) {
		    Schema::connection('dbp')->create('language_dialects', function (Blueprint $table) {
			    $table->increments('id');
			    $table->integer('language_id')->unsigned();
			    $table->foreign('language_id')->references('id')->on('languages')->onUpdate('cascade');
			    $table->char('dialect_id', 8)->index()->nullable()->default(null);
			    $table->text('name');
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
		}

		if(!Schema::connection('dbp')->hasTable('language_classifications')) {
		    Schema::connection('dbp')->create('language_classifications', function (Blueprint $table) {
			    $table->increments('id');
			    $table->integer('language_id')->unsigned();
			    $table->foreign('language_id')->references('id')->on('languages')->onUpdate('cascade');
			    $table->char('classification_id', 8);
			    $table->tinyInteger('order')->unsigned();
			    $table->string('name');
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
        }

		if(!Schema::connection('dbp')->hasTable('language_codes')) {
		    Schema::connection('dbp')->create('language_codes', function (Blueprint $table) {
			    $table->increments('id');
			    $table->integer('language_id')->unsigned();
			    $table->foreign('language_id')->references('id')->on('languages')->onUpdate('cascade');
			    $table->string('source');
			    $table->string('code');
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
        }

		if(!Schema::connection('dbp')->hasTable('alphabets')) {
		    Schema::connection('dbp')->create('alphabets', function (Blueprint $table) {
			    $table->char('script', 4)->primary(); // ScriptSource/Iso ID
			    $table->string('name');
			    $table->string('unicode_pdf')->nullable();
			    $table->string('family')->nullable();
			    $table->string('type')->nullable();
			    $table->string('white_space')->nullable();
			    $table->string('open_type_tag')->nullable();
			    $table->string('complex_positioning')->nullable();
			    $table->boolean('requires_font')->default(0);
			    $table->boolean('unicode')->default(1);
			    $table->boolean('diacritics')->nullable();
			    $table->boolean('contextual_forms')->nullable();
			    $table->boolean('reordering')->nullable();
			    $table->boolean('case')->nullable();
			    $table->boolean('split_graphs')->nullable();
			    $table->string('status')->nullable();
			    $table->string('baseline')->nullable();
			    $table->string('ligatures')->nullable();
			    $table->char('direction', 3)->nullable(); // rtl, ltr, ttb
			    $table->text('direction_notes')->nullable();
			    $table->text('sample')->nullable();
			    $table->string('sample_img')->nullable();
			    $table->text('description')->nullable();
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
        }

		if(!Schema::connection('dbp')->hasTable('numeral_systems')) {
	        Schema::connection('dbp')->create('numeral_systems', function (Blueprint $table) {
		        $table->string('id', 20)->primary();
		        $table->text('description')->nullable();
		        $table->text('notes')->nullable();
		        $table->timestamp('created_at')->useCurrent();
		        $table->timestamp('updated_at')->useCurrent();
	        });
        }

		if(!Schema::connection('dbp')->hasTable('alphabet_numeral_systems')) {
		    Schema::connection('dbp')->create('alphabet_numeral_systems', function (Blueprint $table) {
			    $table->char('numeral_system_id', 20)->index();
			    $table->foreign('numeral_system_id')->references('id')->on('numeral_systems')->onUpdate('cascade');
			    $table->char('script_id', 4)->nullable();
			    $table->foreign('script_id')->references('script')->on('alphabets')->onUpdate('cascade');
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
        }

		if(!Schema::connection('dbp')->hasTable('numeral_system_glyphs')) {
	        Schema::connection('dbp')->create('numeral_system_glyphs', function (Blueprint $table) {
		        $table->char('numeral_system_id', 20)->index();
		        $table->foreign('numeral_system_id')->references('id')->on('numeral_systems')->onUpdate('cascade');
		        $table->tinyInteger('value')->unsigned();
		        $table->string('glyph', 8);
		        $table->string('numeral_written', 8)->nullable();
		        $table->timestamp('created_at')->useCurrent();
		        $table->timestamp('updated_at')->useCurrent();
	        });
			DB::connection('dbp')->statement('ALTER TABLE numeral_system_glyphs ADD CONSTRAINT uq_numeral_system_glyph UNIQUE(`numeral_system_id`, `value`, `glyph`)');
        }

		if(!Schema::connection('dbp')->hasTable('alphabet_language')) {
		    Schema::connection('dbp')->create('alphabet_language', function (Blueprint $table) {
			    $table->increments('id');
			    $table->char('script_id', 4)->index();
			    $table->foreign('script_id')->references('script')->on('alphabets')->onUpdate('cascade');
			    $table->integer('language_id')->unsigned();
			    $table->foreign('language_id')->references('id')->on('languages')->onUpdate('cascade');
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
        }

		if(!Schema::connection('dbp')->hasTable('alphabet_fonts')) {
		    Schema::connection('dbp')->create('alphabet_fonts', function (Blueprint $table) {
			    $table->increments('id');
			    $table->char('script_id', 4);
			    $table->foreign('script_id')->references('script')->on('alphabets')->onUpdate('cascade');
			    $table->string('font_name');
			    $table->string('font_filename');
			    $table->integer('font_weight')->unsigned()->nullable()->default(null);
			    $table->string('copyright')->nullable()->default(null);
			    $table->string('url')->nullable()->default(null);
			    $table->text('notes')->nullable()->default(null);
			    $table->boolean('italic')->default(0);
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
        }

		if(!Schema::connection('dbp')->hasTable('country_translations')) {
		    Schema::connection('dbp')->create('country_translations', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->integer('language_id')->unsigned();
			    $table->foreign('language_id')->references('id')->on('languages')->onUpdate('cascade');
			    $table->string('name');
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
        }

		if(!Schema::connection('dbp')->hasTable('country_regions')) {
		    Schema::connection('dbp')->create('country_regions', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->integer('language_id')->unsigned();
			    $table->foreign('language_id')->references('id')->on('languages')->onUpdate('cascade');
			    $table->string('name');
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
        }

		if(!Schema::connection('dbp')->hasTable('country_language')) {
		    Schema::connection('dbp')->create('country_language', function (Blueprint $table) {
			    $table->char('country_id', 2);
			    $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
			    $table->integer('language_id')->unsigned();
			    $table->foreign('language_id')->references('id')->on('languages')->onUpdate('cascade');
			    $table->integer('population')->default(0);
		    });
		    DB::connection('dbp')->statement('ALTER TABLE country_language ADD CONSTRAINT uq_country_language UNIQUE(country_id, language_id)');
		}

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::connection('dbp')->dropIfExists('alphabet_numeral_systems');
	    Schema::connection('dbp')->dropIfExists('numeral_system_glyphs');
	    Schema::connection('dbp')->dropIfExists('numeral_systems');
	    Schema::connection('dbp')->dropIfExists('alphabet_language');
        Schema::connection('dbp')->dropIfExists('alphabet_fonts');
        Schema::connection('dbp')->dropIfExists('alphabets');
	    Schema::connection('dbp')->dropIfExists('language_classifications');
	    Schema::connection('dbp')->dropIfExists('language_translations');
	    Schema::connection('dbp')->dropIfExists('language_bibleInfo');
	    Schema::connection('dbp')->dropIfExists('language_dialects');
	    Schema::connection('dbp')->dropIfExists('language_codes');
        Schema::connection('dbp')->dropIfExists('country_regions');
        Schema::connection('dbp')->dropIfExists('country_translations');
        Schema::connection('dbp')->dropIfExists('country_language');
        Schema::connection('dbp')->dropIfExists('languages');
        Schema::connection('dbp')->dropIfExists('countries');
    }
}
