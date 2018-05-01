<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('articles', function (Blueprint $table) {
		    $table->increments('id');
		    $table->char('iso', 3)->index();
		    $table->foreign('iso')->references('iso')->on('languages')->onUpdate('cascade')->onDelete('cascade');
		    $table->integer('organization_id')->unsigned();
		    $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('user_id', 64);
		    $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
		    $table->string('cover')->nullable();
		    $table->string('cover_thumbnail')->nullable();
		    $table->timestamps();
	    });

	    Schema::create('article_translations', function (Blueprint $table) {
		    $table->integer('article_id')->unsigned();
		    $table->foreign('article_id')->references('id')->on('articles')->onUpdate('cascade')->onDelete('cascade');
		    $table->char('iso',3);
		    $table->foreign('iso')->references('iso')->on('languages')->onUpdate('cascade');
		    $table->string('name');
		    $table->text('description')->nullable();
		    $table->boolean('vernacular')->default(0);
		    $table->unique(['article_id','iso'], 'unq_article_translations');
		    $table->timestamps();
	    });

	    Schema::create('article_tags', function (Blueprint $table) {
		    $table->integer('article_id')->unsigned();
		    $table->foreign('article_id')->references('id')->on('articles')->onUpdate('cascade')->onDelete('cascade');
		    $table->char('iso',3);
		    $table->foreign('iso')->references('iso')->on('languages')->onUpdate('cascade');
		    $table->string('tag');
		    $table->string('name');
		    $table->text('description')->nullable();
		    $table->unique(['article_id', 'iso'], 'unq_article_tags');
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
	    Schema::dropIfExists('articles');
	    Schema::dropIfExists('article_translations');
	    Schema::dropIfExists('article_tags');
    }
}
