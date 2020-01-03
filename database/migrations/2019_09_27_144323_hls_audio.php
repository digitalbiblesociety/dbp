<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HlsAudio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('dbp')->hasTable('bible_file_video_resolutions')) {
            Schema::connection('dbp')->table('bible_file_video_resolutions', function (Blueprint $table) {
                if (Schema::connection('dbp')->hasColumn('bible_file_video_resolutions', 'resolution_width')) {
                    $table->integer('resolution_width')->unsigned()->nullable()->change();
                }
                if (Schema::connection('dbp')->hasColumn('bible_file_video_resolutions', 'resolution_height')) {
                    $table->integer('resolution_height')->unsigned()->nullable()->change();
                }
            });
        }

        if (Schema::connection('dbp')->hasTable('bible_file_video_resolutions') && Schema::connection('dbp')->hasTable('bible_file_video_transport_stream')) {
            Schema::connection('dbp')->table('bible_file_video_resolutions', function (Blueprint $table) {
                $table->dropForeign('FK_bible_files_bible_file_video_resolutions');
            });
            Schema::connection('dbp')->table('bible_file_video_transport_stream', function (Blueprint $table) {
                $table->dropForeign('FK_bible_file_video_resolutions_bible_file_video_transport_strea');
            });

            Schema::connection('dbp')->rename('bible_file_video_resolutions', 'bible_file_stream_bandwidths');
            Schema::connection('dbp')->rename('bible_file_video_transport_stream', 'bible_file_stream_segments');

            Schema::connection('dbp')->table('bible_file_stream_bandwidths', function (Blueprint $table) {
                $table->foreign('bible_file_id', 'FK_bible_files_bible_file_stream_bandwidths')->references('id')->on(config('database.connections.dbp.database') . '.bible_files')->onUpdate('cascade')->onDelete('cascade');
            });
            Schema::connection('dbp')->table('bible_file_stream_segments', function (Blueprint $table) {
                $table->renameColumn('video_resolution_id', 'stream_bandwidth_id');
                $table->foreign('stream_bandwidth_id', 'FK_stream_bandwidths_stream_segments')->references('id')->on(config('database.connections.dbp.database') . '.bible_file_stream_bandwidths')->onUpdate('cascade')->onDelete('cascade');
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
        if (Schema::connection('dbp')->hasTable('bible_file_stream_bandwidths') && Schema::connection('dbp')->hasTable('bible_file_stream_segments')) {
            Schema::connection('dbp')->table('bible_file_stream_bandwidths', function (Blueprint $table) {
                $table->dropForeign('FK_bible_files_bible_file_stream_bandwidths');
            });
            Schema::connection('dbp')->table('bible_file_stream_segments', function (Blueprint $table) {
                $table->dropForeign('FK_stream_bandwidths_stream_segments');
            });

            Schema::connection('dbp')->rename('bible_file_stream_bandwidths', 'bible_file_video_resolutions');
            Schema::connection('dbp')->rename('bible_file_stream_segments', 'bible_file_video_transport_stream');

            Schema::connection('dbp')->table('bible_file_video_resolutions', function (Blueprint $table) {
                $table->foreign('bible_file_id', 'FK_bible_files_bible_file_video_resolutions')->references('id')->on(config('database.connections.dbp.database') . '.bible_files')->onUpdate('cascade')->onDelete('cascade');
            });
            Schema::connection('dbp')->table('bible_file_video_transport_stream', function (Blueprint $table) {
                $table->renameColumn('stream_bandwidth_id', 'video_resolution_id');
                $table->foreign('video_resolution_id', 'FK_bible_file_video_resolutions_bible_file_video_transport_strea')->references('id')->on(config('database.connections.dbp.database') . '.bible_file_video_resolutions')->onUpdate('cascade')->onDelete('cascade');
            });
        }

        if (Schema::connection('dbp')->hasTable('bible_file_video_resolutions')) {
            Schema::connection('dbp')->table('bible_file_video_resolutions', function (Blueprint $table) {
                if (Schema::connection('dbp')->hasColumn('bible_file_video_resolutions', 'resolution_width')) {
                    $table->integer('resolution_width')->unsigned()->nullable(false)->change();
                }
                if (Schema::connection('dbp')->hasColumn('bible_file_video_resolutions', 'resolution_height')) {
                    $table->integer('resolution_height')->unsigned()->nullable(false)->change();
                }
            });
        }
    }
}
