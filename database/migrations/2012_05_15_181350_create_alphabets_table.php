<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlphabetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp')->hasTable('alphabets')) {
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
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('numeral_systems')) {
            Schema::connection('dbp')->create('numeral_systems', function (Blueprint $table) {
                $table->string('id', 20)->primary();
                $table->text('description')->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('alphabet_numeral_systems')) {
            Schema::connection('dbp')->create('alphabet_numeral_systems', function (Blueprint $table) {
                $table->char('numeral_system_id', 20)->index();
                $table->foreign('numeral_system_id', 'FK_numeral_systems_alphabet_numeral_systems')->references('id')->on(config('database.connections.dbp.database').'.numeral_systems')->onUpdate('cascade');
                $table->char('script_id', 4)->nullable();
                $table->foreign('script_id', 'FK_alphabets_alphabet_numeral_systems')->references('script')->on(config('database.connections.dbp.database').'.alphabets')->onUpdate('cascade');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('numeral_system_glyphs')) {
            Schema::connection('dbp')->create('numeral_system_glyphs', function (Blueprint $table) {
                $table->char('numeral_system_id', 20)->index();
                $table->foreign('numeral_system_id', 'FK_numeral_systems_numeral_system_glyphs')->references('id')->on(config('database.connections.dbp.database').'.numeral_systems')->onUpdate('cascade');
                $table->tinyInteger('value')->unsigned();
                $table->string('glyph', 8);
                $table->string('numeral_written', 8)->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
            DB::connection('dbp')->statement('ALTER TABLE numeral_system_glyphs ADD CONSTRAINT uq_numeral_system_glyph UNIQUE(`numeral_system_id`, `value`, `glyph`)');
        }

        if (!Schema::connection('dbp')->hasTable('alphabet_language')) {
            Schema::connection('dbp')->create('alphabet_language', function (Blueprint $table) {
                $table->increments('id');
                $table->char('script_id', 4)->index();
                $table->foreign('script_id', 'FK_alphabets_alphabet_language')->references('script')->on(config('database.connections.dbp.database').'.alphabets')->onUpdate('cascade');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_alphabet_language')->references('id')->on(config('database.connections.dbp.database').'.languages')->onUpdate('cascade');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('alphabet_fonts')) {
            Schema::connection('dbp')->create('alphabet_fonts', function (Blueprint $table) {
                $table->increments('id');
                $table->char('script_id', 4);
                $table->foreign('script_id', 'FK_alphabets_alphabet_fonts')->references('script')->on(config('database.connections.dbp.database').'.alphabets')->onUpdate('cascade');
                $table->string('font_name');
                $table->string('font_filename');
                $table->integer('font_weight')->unsigned()->nullable()->default(null);
                $table->string('copyright')->nullable()->default(null);
                $table->string('url')->nullable()->default(null);
                $table->text('notes')->nullable()->default(null);
                $table->boolean('italic')->default(0);
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
        Schema::connection('dbp')->dropIfExists('alphabet_numeral_systems');
        Schema::connection('dbp')->dropIfExists('numeral_system_glyphs');
        Schema::connection('dbp')->dropIfExists('numeral_systems');
        Schema::connection('dbp')->dropIfExists('alphabet_language');
        Schema::connection('dbp')->dropIfExists('alphabet_fonts');
        Schema::connection('dbp')->dropIfExists('alphabets');
    }
}
