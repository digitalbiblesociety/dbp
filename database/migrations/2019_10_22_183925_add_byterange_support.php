<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddByterangeSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('dbp')->hasTable('bible_file_stream_segments')) {
            Schema::connection('dbp')->table('bible_file_stream_segments', function (Blueprint $table) {
                $table->integer('bytes')->nullable()->after('runtime')->default(null);
                $table->integer('offset')->nullable()->after('runtime')->default(null);
                $table->integer('timestamp_id')->unsigned()->nullable()->after('runtime');
                $table->string('file_name')->nullable()->change();
                $table->foreign('timestamp_id', 'FK_bible_file_timestamp_stream_segments')->references('id')->on(config('database.connections.dbp.database').'.bible_file_timestamps')->onUpdate('cascade')->onDelete('cascade');
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
        if (Schema::connection('dbp')->hasTable('bible_file_stream_segments')) {
            Schema::connection('dbp')->table('bible_file_stream_segments', function (Blueprint $table) {
                $table->dropColumn('bytes');
                $table->dropColumn('offset');
                $table->dropForeign('FK_bible_file_timestamp_stream_segments');
                $table->dropColumn('timestamp_id');
                $table->string('file_name')->nullable(false)->change();
            });
        }
    }
}
