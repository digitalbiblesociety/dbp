<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBiblesDefaultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp')->hasTable('bibles_defaults')) {
            Schema::connection('dbp')->create('bibles_defaults', function (Blueprint $table) {
                $table->increments('id');
                $table->char('language_code', 6);
                $table->string('bible_id', 12);
                $table->string('type', 12);
                $table->foreign('bible_id', 'FK_bibles_defaults')->references('id')->on(config('database.connections.dbp.database').'.bibles')->onDelete('cascade')->onUpdate('cascade');
                $table->unique(['language_code', 'bible_id', 'type'], 'unique_bible_default');
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
        Schema::connection('dbp')->dropIfExists('bibles_defaults');
    }
}
