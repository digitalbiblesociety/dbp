<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UdpateTypeCodeLengthToFilesetTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('dbp')->hasTable('bible_fileset_types')) {
            Schema::connection('dbp')->table('bible_fileset_types', function (Blueprint $table) {
                $table->string('set_type_code', 18)->change();
            });
        }
        if (Schema::connection('dbp')->hasTable('bible_filesets')) {
            Schema::connection('dbp')->table('bible_filesets', function (Blueprint $table) {
                $table->string('set_type_code', 18)->change();
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
        if (Schema::connection('dbp')->hasTable('bible_filesets')) {
            Schema::connection('dbp')->table('bible_filesets', function (Blueprint $table) {
                $table->dropForeign('FK_bible_fileset_types_bible_filesets');
            });
        }

        if (Schema::connection('dbp')->hasTable('bible_fileset_types')) {
            Schema::connection('dbp')->table('bible_fileset_types', function (Blueprint $table) {
                $table->string('set_type_code', 16)->change();
            });
        }
        if (Schema::connection('dbp')->hasTable('bible_filesets')) {
            Schema::connection('dbp')->table('bible_filesets', function (Blueprint $table) {
                $table->string('set_type_code', 16)->change();
                $table->foreign('set_type_code', 'FK_bible_fileset_types_bible_filesets')->references('set_type_code')->on(config('database.connections.dbp.database') . '.bible_fileset_types')->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }
}
