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
        if (!Schema::connection('dbp')->hasTable('countries')) {
            Schema::connection('dbp')->create('countries', function (Blueprint $table) {
                $table->char('id', 2)->primary();
                $table->char('iso_a3', 3)->unique();
                $table->char('fips', 2)->nullable();
                $table->boolean('wfb')->default(0);
                $table->boolean('ethnologue')->default(0);
                $table->char('continent', 2)->index();
                $table->string('name');
                $table->text('introduction')->nullable();
                $table->text('overview')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('language_status')) {
            Schema::connection('dbp')->create('language_status', function (Blueprint $table) {
                $table->char('id', 2)->primary();
                $table->string('title');
                $table->text('description')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('languages')) {
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
                $table->integer('population')->nullable();
                $table->text('population_notes')->nullable();
                $table->text('notes')->nullable();
                $table->text('typology')->nullable();
                $table->text('writing')->nullable();
                $table->text('description')->nullable();
                $table->float('latitude', 11, 7)->nullable();
                $table->float('longitude', 11, 7)->nullable();
                $table->char('country_id', 2)->nullable()->default(null);
                $table->foreign('country_id', 'FK_languages_countries')->references('id')->on(config('database.connections.dbp.database').'.countries')->onUpdate('cascade');
                $table->char('status_id', 2)->nullable();
                $table->foreign('status_id', 'FK_languages_language_status')->references('id')->on(config('database.connections.dbp.database').'.language_status')->onUpdate('cascade');
                $table->text('status_notes')->nullable();
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            });
            DB::connection('dbp')->statement('ALTER TABLE languages ADD CONSTRAINT CHECK (iso IS NOT NULL OR glotto_id IS NOT NULL)');
        }

        if (!Schema::connection('dbp')->hasTable('language_translations')) {
            Schema::connection('dbp')->create('language_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('language_source_id')->unsigned();
                $table->foreign('language_source_id', 'FK_languages_language_translations_language_source_id')->references('id')->on(config('database.connections.dbp.database').'.languages')->onUpdate('cascade');
                $table->integer('language_translation_id')->unsigned();
                $table->foreign('language_translation_id', 'FK_languages_language_translations_language_translation_id')->references('id')->on(config('database.connections.dbp.database').'.languages')->onUpdate('cascade');
                $table->string('name');
                $table->tinyInteger('priority')->nullable()->default(0);
                $table->unique(['language_source_id', 'language_translation_id', 'name'], 'unq_language_translations');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('language_bible_info')) {
            Schema::connection('dbp')->create('language_bible_info', function (Blueprint $table) {
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_language_bibleInfo')->references('id')->on(config('database.connections.dbp.database').'.languages')->onUpdate('cascade');
                $table->tinyInteger('bible_status')->nullable();
                $table->boolean('bible_translation_need')->nullable();
                $table->integer('bible_year')->nullable();
                $table->integer('bible_year_newTestament')->nullable();
                $table->integer('bible_year_portions')->nullable();
                $table->text('bible_sample_text')->nullable();
                $table->string('bible_sample_img')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('language_dialects')) {
            Schema::connection('dbp')->create('language_dialects', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_language_dialects')->references('id')->on(config('database.connections.dbp.database').'.languages')->onUpdate('cascade');
                $table->char('dialect_id', 8)->index()->nullable()->default(null);
                $table->text('name');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('language_classifications')) {
            Schema::connection('dbp')->create('language_classifications', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_language_classifications')->references('id')->on(config('database.connections.dbp.database').'.languages')->onUpdate('cascade');
                $table->char('classification_id', 8);
                $table->tinyInteger('order')->unsigned();
                $table->string('name');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('language_codes')) {
            Schema::connection('dbp')->create('language_codes', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_language_codes')->references('id')->on(config('database.connections.dbp.database').'.languages')->onUpdate('cascade');
                $table->string('source');
                $table->string('code');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('country_translations')) {
            Schema::connection('dbp')->create('country_translations', function (Blueprint $table) {
                $table->char('country_id', 2);
                $table->foreign('country_id', 'FK_countries_country_translations')->references('id')->on(config('database.connections.dbp.database').'.countries')->onUpdate('cascade');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_country_translations')->references('id')->on(config('database.connections.dbp.database').'.languages')->onUpdate('cascade');
                $table->string('name');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('country_regions')) {
            Schema::connection('dbp')->create('country_regions', function (Blueprint $table) {
                $table->char('country_id', 2);
                $table->foreign('country_id', 'FK_countries_country_regions')->references('id')->on(config('database.connections.dbp.database').'.countries')->onUpdate('cascade');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_country_regions')->references('id')->on(config('database.connections.dbp.database').'.languages')->onUpdate('cascade');
                $table->string('name');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('country_language')) {
            Schema::connection('dbp')->create('country_language', function (Blueprint $table) {
                $table->char('country_id', 2);
                $table->foreign('country_id', 'FK_countries_country_language')->references('id')->on(config('database.connections.dbp.database').'.countries')->onUpdate('cascade');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_country_language')->references('id')->on(config('database.connections.dbp.database').'.languages')->onUpdate('cascade');
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
        Schema::connection('dbp')->dropIfExists('language_classifications');
        Schema::connection('dbp')->dropIfExists('language_translations');
        Schema::connection('dbp')->dropIfExists('language_bible_info');
        Schema::connection('dbp')->dropIfExists('language_dialects');
        Schema::connection('dbp')->dropIfExists('language_codes');
        Schema::connection('dbp')->dropIfExists('country_regions');
        Schema::connection('dbp')->dropIfExists('country_translations');
        Schema::connection('dbp')->dropIfExists('country_language');
        Schema::connection('dbp')->dropIfExists('languages');
        Schema::connection('dbp')->dropIfExists('countries');
    }
}
