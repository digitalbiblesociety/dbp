<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBibleFilesStreamBytesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp')->hasTable('bible_file_stream_bytes')) {
            Schema::connection('dbp')->create('bible_file_stream_bytes', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('stream_bandwidth_id')->unsigned();
                $table->foreign('stream_bandwidth_id', 'FK_bible_file_bandwidth_stream_bytes')->references('id')->on(config('database.connections.dbp.database') . '.bible_file_stream_bandwidths')->onUpdate('cascade')->onDelete('cascade');
                $table->float('runtime');
                $table->integer('bytes');
                $table->integer('offset');
                $table->integer('timestamp_id')->unsigned();
                $table->foreign('timestamp_id', 'FK_bible_file_timestamp_stream_bytes')->references('id')->on(config('database.connections.dbp.database') . '.bible_file_timestamps')->onUpdate('cascade')->onDelete('cascade');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (Schema::connection('dbp')->hasTable('bible_file_stream_segments')) {
            Schema::connection('dbp')->table('bible_file_stream_segments', function (Blueprint $table) {
                \DB::connection('dbp')->statement('INSERT bible_file_stream_bytes (stream_bandwidth_id, runtime, bytes, offset, timestamp_id) SELECT stream_bandwidth_id, runtime, bytes, offset, timestamp_id FROM bible_file_stream_segments where file_name is null');
                \DB::connection('dbp')->table('bible_file_stream_segments')->whereNull('file_name')->delete();
                $table->dropColumn('bytes');
                $table->dropColumn('offset');
                $table->dropForeign('FK_bible_file_timestamp_stream_segments');
                $table->dropColumn('timestamp_id');
                $table->string('file_name')->nullable(false)->change();
                $table->dropForeign('FK_stream_bandwidths_stream_segments');
                $table->foreign('stream_bandwidth_id', 'FK_stream_bandwidths_stream_ts')->references('id')->on(config('database.connections.dbp.database') . '.bible_file_stream_bandwidths')->onUpdate('cascade')->onDelete('cascade');
            });
            Schema::connection('dbp')->rename('bible_file_stream_segments', 'bible_file_stream_ts');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::connection('dbp')->hasTable('bible_file_stream_ts')) {
            Schema::connection('dbp')->rename('bible_file_stream_ts', 'bible_file_stream_segments');
            Schema::connection('dbp')->table('bible_file_stream_segments', function (Blueprint $table) {
                $table->integer('bytes')->nullable()->after('runtime')->default(null);
                $table->integer('offset')->nullable()->after('runtime')->default(null);
                $table->integer('timestamp_id')->unsigned()->nullable()->after('runtime');
                $table->string('file_name')->nullable()->change();
                $table->foreign('timestamp_id', 'FK_bible_file_timestamp_stream_segments')->references('id')->on(config('database.connections.dbp.database') . '.bible_file_timestamps')->onUpdate('cascade')->onDelete('cascade');
                $table->dropForeign('FK_stream_bandwidths_stream_ts');
                $table->foreign('stream_bandwidth_id', 'FK_stream_bandwidths_stream_segments')->references('id')->on(config('database.connections.dbp.database') . '.bible_file_stream_bandwidths')->onUpdate('cascade')->onDelete('cascade');
            });
        }
        Schema::connection('dbp')->dropIfExists('bible_file_stream_bytes');
    }
}
