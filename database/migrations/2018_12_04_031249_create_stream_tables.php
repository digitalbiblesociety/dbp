<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreamTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp')->hasTable('stream')) {
            Schema::connection('dbp')->create('stream', function (Blueprint $table) {
                $table->string('name', 64)->primary();
            });
        }

        if (!Schema::connection('dbp')->hasTable('stream_res')) {
            Schema::connection('dbp')->create('stream_res', function (Blueprint $table) {
                $table->string('filename', 64)->primary();
                $table->string('properties', 128);
                $table->string('stream_parent', 64);
                $table->foreign('stream_parent', 'FK_stream_parent_stream_res')->references('name')->on('stream')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        if (!Schema::connection('dbp')->hasTable('stream_ts')) {
            Schema::connection('dbp')->create('stream_ts', function (Blueprint $table) {
                $table->string('filename', 64)->primary();
                $table->string('properties', 64);
                $table->string('stream_res', 64);
                $table->foreign('stream_res', 'FK_stream_res_stream_ts')->references('filename')->on('stream_res')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::connection('dbp')->dropIfExists('stream_res');
        Schema::connection('dbp')->dropIfExists('stream_res');
        Schema::connection('dbp')->dropIfExists('stream');
    }
}
