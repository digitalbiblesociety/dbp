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
        if (!Schema::connection('dbp')->hasTable('bible_text')) {
            Schema::connection('dbp')->create('bible_text', function (Blueprint $table) {
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

        if (!Schema::connection('dbp')->hasTable('bible_text_concordance')) {
            Schema::connection('dbp')->create('bible_text_concordance', function (Blueprint $table) {
                $table->integer('bible_text_id')->unsigned();
                $table->foreign('bible_text_id')->references('id')->on(config('database.connections.dbp.database') . '.bible_text')->onUpdate('cascade')->onDelete('cascade');
                $table->string('key_word');
                $table->unique(['bible_text_id', 'key_word']);
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
        Schema::connection('dbp')->dropIfExists('bible_text_concordance');
        Schema::connection('dbp')->dropIfExists('bible_text');
    }
}
