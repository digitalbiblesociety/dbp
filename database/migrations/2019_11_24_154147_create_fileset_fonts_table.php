<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateFilesetFontsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp')->hasTable('fonts')) {
            Schema::connection('dbp')->create('fonts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->longText('data');
                $table->string('type');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('bible_fileset_fonts')) {
            Schema::connection('dbp')->create('bible_fileset_fonts', function (Blueprint $table) {
                $table->char('hash_id', 12);
                $table->foreign('hash_id', 'FK_bible_filesets_bible_fileset_fonts')->references('hash_id')->on(config('database.connections.dbp.database') . '.bible_filesets')->onUpdate('cascade')->onDelete('cascade');
                $table->integer('font_id')->unsigned();
                $table->foreign('font_id', 'FK_fonts_bible_fileset_fonts')->references('id')->on(config('database.connections.dbp.database') . '.fonts')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::connection('dbp')->dropIfExists('bible_fileset_fonts');
        Schema::connection('dbp')->dropIfExists('fonts');
    }
}
