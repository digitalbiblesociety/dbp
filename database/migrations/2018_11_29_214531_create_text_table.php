<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp')->hasTable('bible_verses')) {
            Schema::connection('dbp')->create('bible_verses', function (Blueprint $table) {
                $table->increments('id');
                $table->char('hash_id', 12)->index();
                $table->foreign('hash_id')->references('hash_id')->on(config('database.connections.dbp.database') . '.bible_filesets')->onUpdate('cascade')->onDelete('cascade');
                $table->char('book_id', 3);
                $table->foreign('book_id')->references('id')->on(config('database.connections.dbp.database') . '.books')->onUpdate('cascade')->onDelete('cascade');
                $table->tinyInteger('chapter')->unsigned();
                $table->tinyInteger('verse_start')->unsigned();
                $table->tinyInteger('verse_end')->unsigned();
                $table->text('verse_text');
                $table->unique(['hash_id', 'book_id', 'chapter', 'verse_start'], 'unique_text_reference');
            });
        }

        if (!Schema::connection('dbp')->hasTable('bible_strongs')) {
            Schema::connection('dbp')->create('bible_strongs', function (Blueprint $table) {
                $table->string('strong_number', 6)->primary();
                $table->string('root_word');
                $table->string('transliteration');
                $table->string('pronunciation');
                $table->string('definition');
                $table->text('usage');
            });
        }

        if (!Schema::connection('dbp')->hasTable('bible_concordance')) {
            Schema::connection('dbp')->create('bible_concordance', function (Blueprint $table) {
                $table->increments('id');
                $table->string('key_word')->unique();
            });
        }

        if (!Schema::connection('dbp')->hasTable('bible_verse_concordance')) {
            Schema::connection('dbp')->create('bible_verse_concordance', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('bible_verse_id')->unsigned();
                $table->foreign('bible_verse_id')->references('id')->on(config('database.connections.dbp.database') . '.bible_verses')->onUpdate('cascade')->onDelete('cascade');
                $table->integer('bible_concordance')->unsigned();
                $table->foreign('bible_concordance')->references('id')->on(config('database.connections.dbp.database') . '.bible_concordance')->onUpdate('cascade')->onDelete('cascade');
                $table->char('strong_number', 6)->nullable();
                $table->foreign('strong_number')->references('strong_number')->on(config('database.connections.dbp.database') . '.bible_strongs')->onUpdate('cascade');
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
        Schema::connection('dbp')->dropIfExists('bible_verse_concordance');
        Schema::connection('dbp')->dropIfExists('bible_concordance');
        Schema::connection('dbp')->dropIfExists('bible_strongs');
        Schema::connection('dbp')->dropIfExists('bible_verses');
    }
}
