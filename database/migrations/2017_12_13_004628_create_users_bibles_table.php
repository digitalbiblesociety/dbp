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
		    $table->char('user_id', 36)->primary();
		    $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
		    $table->string('bible_id',12);
		    $table->foreign('bible_id')->references('id')->on('bibles')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('project_id');
		    $table->string('reference_id')->nullable();
		    $table->text('highlights')->nullable();
		    $table->text('notes')->nullable();
		    $table->timestamps();
	    });

	    Schema::create('user_access', function (Blueprint $table) {
	    	$table->increments('id');
		    $table->char('key_id', 24);
		    $table->char('user_id', 16);
		    $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
		    $table->string('bible_id',12)->nullable();
		    $table->foreign('bible_id')->references('id')->on('bibles')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('fileset_id',16);
		    $table->foreign('fileset_id')->references('id')->on('bible_filesets')->onUpdate('cascade')->onDelete('cascade');
		    $table->integer('organization_id')->unsigned()->nullable();
		    $table->foreign('organization_id')->references('id')->on('organizations');
		    $table->boolean('whitelist')->default(1);
		    $table->text('access_notes')->nullable();
		    $table->string('access_type')->nullable();
		    $table->boolean('access_given')->default(1);
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
	    Schema::dropIfExists('user_access');
        Schema::dropIfExists('user_notes');
    }
}
