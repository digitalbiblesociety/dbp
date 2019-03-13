<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLexiconTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!\Schema::connection('dbp')->hasTable('lexicons')) {
            Schema::connection('dbp')->create('lexicons', function (Blueprint $table) {
                $table->char('id', 5)->primary();
                $table->string('base_word', 64);
                $table->string('usage');
                $table->string('definition');
                $table->string('derived')->nullable();
                $table->string('part_of_speech', 20)->nullable();
                $table->string('aramaic')->nullable();
                $table->string('comment')->nullable();
                $table->timestamps();
            });
        }

        if (!\Schema::connection('dbp')->hasTable('lexical_definitions')) {
            Schema::connection('dbp')->create('lexical_definitions', function (Blueprint $table) {
                $table->increments('id');
                $table->char('lexicon_id', 5);
                $table->foreign('lexicon_id', 'FK_lexicons_lexical_definitions')->references('id')->on(config('database.connections.dbp.database') . '.lexicons')->onUpdate('cascade')->onDelete('cascade');
                $table->boolean('literal');
                $table->string('word_variant', 64);
                $table->string('definition', 64);
                $table->timestamps();
            });
        }

        if (!\Schema::connection('dbp')->hasTable('lexical_lexemes')) {
            Schema::connection('dbp')->create('lexical_lexemes', function (Blueprint $table) {
                $table->string('id', 16);
                $table->string('type_of_speech', 64);
                $table->string('grouping', 64);
                $table->timestamps();
            });
        }

        if (!\Schema::connection('dbp')->hasTable('lexical_pronunciations')) {
            Schema::connection('dbp')->create('lexical_pronunciations', function (Blueprint $table) {
                $table->char('lexicon_id', 5)->primary();
                $table->foreign('lexicon_id', 'FK_lexicons_lexical_pronunciations')->references('id')->on(config('database.connections.dbp.database') . '.lexicons')->onUpdate('cascade')->onDelete('cascade');
                $table->string('ipa', 64);
                $table->string('ipa_mod', 64);
                $table->string('sbl', 64);
                $table->string('dic', 64);
                $table->string('dic_mod', 64);
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
        Schema::dropIfExists('lexical_pronunciations');
        Schema::dropIfExists('lexical_definitions');
        Schema::dropIfExists('lexicon');
    }
}
