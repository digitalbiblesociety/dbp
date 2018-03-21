<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');
	        $table->char('iso', 3)->index();
	        $table->foreign('iso')->references('iso')->on('languages')->onUpdate('cascade')->onDelete('cascade');
	        $table->integer('organization_id')->unsigned();
	        $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');
	        $table->string('source_id')->nullable();
	        $table->string('cover')->nullable();
	        $table->string('cover_thumbnail')->nullable();
	        $table->string('date')->nullable();
	        $table->string('type');
            $table->timestamps();
        });

	    Schema::create('resource_links', function (Blueprint $table) {
		    $table->integer('resource_id')->unsigned();
		    $table->foreign('resource_id')->references('id')->on('resources')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('title');
		    $table->string('size')->nullable();
		    $table->string('type');
		    $table->string('url');
		    $table->timestamps();
	    });

	    Schema::create('resource_translations', function (Blueprint $table) {
		    $table->char('iso', 3)->index();
		    $table->foreign('iso')->references('iso')->on('languages')->onDelete('cascade')->onUpdate('cascade');
		    $table->integer('resource_id')->unsigned();
		    $table->foreign('resource_id')->references('id')->on('resources')->onUpdate('cascade')->onDelete('cascade');
		    $table->boolean('vernacular');
		    $table->boolean('tag');
		    $table->string('title');
		    $table->text('description')->nullable();
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
	    Schema::dropIfExists('resource_translations');
	    Schema::dropIfExists('resource_links');
	    Schema::dropIfExists('resources');
    }
}
