<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBibleTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!\Schema::connection('dbp')->hasTable('bible_translations')) {
            \Schema::connection('dbp')->create('bible_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_bible_translations')->references('id')->on(config('database.connections.dbp.database').'.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->string('bible_id', 12);
                $table->foreign('bible_id', 'FK_bibles_bible_translations')->references('id')->on(config('database.connections.dbp.database').'.bibles')->onUpdate('cascade')->onDelete('cascade');
                $table->boolean('vernacular')->default(false);
                $table->boolean('vernacular_trade')->default(false);
                $table->string('name');
                $table->string('type')->nullable();
                $table->string('features')->nullable();
                $table->text('description')->nullable();
                $table->text('notes')->nullable();
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
        \Schema::connection('dbp')->dropIfExists('bible_translations');
    }
}
