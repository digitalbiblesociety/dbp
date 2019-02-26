<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentaryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!\Schema::connection('dbp')->hasTable('verse_references')) {
            Schema::connection('dbp')->create('verse_references', function (Blueprint $table) {
                $table->increments('id');
                $table->char('book_id', 3);
                $table->foreign('book_id', 'FK_books_verse_references')->references('id')->on(config('database.connections.dbp.database') . '.books')->onUpdate('cascade')->onDelete('cascade');
                $table->tinyInteger('chapter')->unsigned();
                $table->tinyInteger('verse_start')->unsigned();
                $table->tinyInteger('verse_end')->unsigned();
                $table->unique(['book_id', 'chapter', 'verse_start'], 'unique_text_reference');
            });
        }

        if (!\Schema::connection('dbp')->hasTable('commentaries')) {
            Schema::connection('dbp')->create('commentaries', function (Blueprint $table) {
                $table->string('id', 12)->primary();
                $table->string('type', 12); // critical, devotional, pastoral, exegetical
                $table->string('author');
                $table->integer('date')->unsigned();
                $table->string('features')->nullable();
                $table->string('publisher')->nullable();
                $table->timestamps();
            });
        }

        if (!\Schema::connection('dbp')->hasTable('commentary_translations')) {
            \Schema::connection('dbp')->create('commentary_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_commentary_translations')->references('id')->on(config('database.connections.dbp.database') . '.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->string('commentary_id', 12);
                $table->foreign('commentary_id', 'FK_commentaries_commentary_translations')->references('id')->on(config('database.connections.dbp.database') . '.commentaries')->onUpdate('cascade')->onDelete('cascade');
                $table->boolean('vernacular')->default(false);
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('commentary_sections')) {
            \Schema::connection('dbp')->create('commentary_sections', function (Blueprint $table) {
                $table->increments('id');
                $table->string('commentary_id', 12);
                $table->foreign('commentary_id', 'FK_commentaries_commentary_sections')->references('id')->on(config('database.connections.dbp.database') . '.commentaries')->onUpdate('cascade')->onDelete('cascade');
                $table->string('title');
                $table->text('content')->nullable();
                $table->char('book_id', 3);
                $table->foreign('book_id', 'FK_books_commentary_sections')->references('id')->on(config('database.connections.dbp.database') . '.books');
                $table->tinyInteger('chapter_start')->unsigned()->nullable();
                $table->tinyInteger('chapter_end')->unsigned()->nullable();
                $table->tinyInteger('verse_start')->unsigned()->nullable();
                $table->tinyInteger('verse_end')->unsigned()->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('glossary_person')) {
            Schema::connection('dbp')->create('glossary_person', function (Blueprint $table) {
                $table->string('id', 24)->primary();
                $table->string('description', 120);
                $table->string('born', 12)->nullable();
                $table->string('died', 12)->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('glossary_person_names')) {
            Schema::connection('dbp')->create('glossary_person_names', function (Blueprint $table) {
                $table->increments('id');
                $table->string('person_id', 24);
                $table->foreign('person_id', 'FK_glossary_person_glossary_person_names')->references('id')->on(config('database.connections.dbp.database') . '.glossary_person')->onUpdate('cascade')->onDelete('cascade');
                $table->string('extended_strongs');
                $table->string('vernacular');
                $table->string('ot_ketiv_translated')->nullable();
                $table->string('ot_qere_translated')->nullable();
                $table->string('nt_variant_translated')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('glossary_person_name_references')) {
            Schema::connection('dbp')->create('glossary_person_name_references', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('person_name_id')->unsigned();
                $table->foreign('person_name_id', 'FK_glossary_person_glossary_person_names')->references('id')->on(config('database.connections.dbp.database') . '.glossary_person_name_references')->onUpdate('cascade')->onDelete('cascade');
                $table->integer('verse_reference_id')->unsigned();
                $table->foreign('verse_reference_id', 'FK_verse_references_glossary_reference')->references('id')->on(config('database.connections.dbp.database') . '.verse_references')->onUpdate('cascade')->onDelete('cascade');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('glossary_person_translation')) {
            Schema::connection('dbp')->create('glossary_person_translation', function (Blueprint $table) {
                $table->increments('id');
                $table->string('person_id', 24);
                $table->foreign('person_id', 'FK_glossary_person_glossary_person_translation')->references('id')->on(config('database.connections.dbp.database') . '.glossary_person')->onUpdate('cascade')->onDelete('cascade');
                $table->string('bible_id', 12);
                $table->foreign('bible_id', 'FK_bibles_glossary_person_translation')->references('id')->on(config('database.connections.dbp.database') . '.bibles')->onUpdate('cascade')->onDelete('cascade');
                $table->string('name');
                $table->unique(['person_id', 'bible_id', 'name'], 'unique_name_reference');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('glossary_person_relationships')) {
            Schema::connection('dbp')->create('glossary_person_relationships', function (Blueprint $table) {
                $table->increments('id');
                $table->string('person_id', 24);
                $table->foreign('person_id', 'FK_person_glossary_person_translation')->references('id')->on(config('database.connections.dbp.database') . '.glossary_person')->onUpdate('cascade')->onDelete('cascade');
                $table->string('related_person_id', 12);
                $table->foreign('related_person_id', 'FK_related_person_glossary_person_translation')->references('id')->on(config('database.connections.dbp.database') . '.glossary_person')->onUpdate('cascade')->onDelete('cascade');
                $table->string('relationship_type', 24);
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
        Schema::connection('dbp')->dropIfExists('commentary_sections');
        Schema::connection('dbp')->dropIfExists('commentary_translations');
        Schema::connection('dbp')->dropIfExists('commentaries');
        Schema::connection('dbp')->dropIfExists('verse_references');
        Schema::connection('dbp')->dropIfExists('glossary_person');
        Schema::connection('dbp')->dropIfExists('glossary_person_name');
        Schema::connection('dbp')->dropIfExists('glossary_person_translation');
        Schema::connection('dbp')->dropIfExists('glossary_person_relationships');
    }
}
