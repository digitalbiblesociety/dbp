<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBibleFontsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp')->hasTable('bible_fonts_table')) {
            Schema::connection('dbp')->create('bible_fonts_table', function (Blueprint $table) {
                $table->increments('id');
                $table->string('bible_id', 12);
                $table->foreign('bible_id', 'FK_bibles_bible_fonts')->references('id')->on(config('database.connections.dbp.database') . '.bibles')->onDelete('cascade')->onUpdate('cascade');
                $table->string('font_name');
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
        if (Schema::connection('dbp')->hasTable('bible_fonts_table')) {
            Schema::connection('dbp')->dropIfExists('bible_fonts_table');
        }
    }
}
