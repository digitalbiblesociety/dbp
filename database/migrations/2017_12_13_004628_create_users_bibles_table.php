<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersBiblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('user_notes', function (Blueprint $table) {
	    	$table->increments('id');
		    $table->string('user_id', 64);
		    $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
		    $table->string('bible_id', 12);
		    $table->foreign('bible_id')->references('id')->on('bibles')->onDelete('cascade')->onUpdate('cascade');
		    $table->char('book_id', 3);
		    $table->foreign('book_id')->references('id')->on('books');
		    $table->integer('chapter')->unsigned();
		    $table->integer('verse_start')->unsigned();
		    $table->integer('verse_end')->unsigned()->nullable();
		    $table->unsignedInteger('project_id')->nullable();
		    $table->text('notes')->nullable();
		    $table->text('highlights')->nullable();
		    $table->timestamps();
	    });

	    Schema::create('user_note_tags', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('note_id')->unsigned();
		    $table->string('type', 64);
		    $table->string('value', 64);
		    $table->timestamps();
	    });


	    Schema::create('user_access', function (Blueprint $table) {
		    $table->string('user_id', 64)->primary();
		    $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
		    $table->string('key_id', 64);
		    $table->foreign('key_id')->references('key')->on('user_keys')->onUpdate('cascade');
		    $table->string('bible_id',12)->nullable();
		    $table->foreign('bible_id')->references('id')->on('bibles')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('fileset_id',16);
		    $table->foreign('fileset_id')->references('id')->on('bible_filesets')->onUpdate('cascade')->onDelete('cascade');
		    $table->unsignedInteger('organization_id')->nullable();
		    $table->foreign('organization_id')->references('id')->on('organizations');
		    $table->boolean('whitelist')->default(1);
		    $table->text('access_notes')->nullable();
		    $table->string('access_type')->nullable();
		    $table->boolean('access_granted')->default(1);
		    $table->timestamps();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('user_note_tags');
	    Schema::dropIfExists('user_access');
        Schema::dropIfExists('user_notes');
    }
}
