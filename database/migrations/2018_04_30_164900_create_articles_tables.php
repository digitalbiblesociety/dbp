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

	    if(!Schema::connection('dbp_users')->hasTable('articles')) {
		    Schema::connection('dbp_users')->create('articles', function (Blueprint $table) {
			    $table->increments('id');
			    $table->char('iso', 3)->index();
			    $table->foreign('iso')->references('iso')->on('dbp.languages')->onUpdate('cascade')->onDelete('cascade');
			    $table->integer('organization_id')->unsigned();
			    $table->foreign('organization_id')->references('id')->on('dbp.organizations')->onUpdate('cascade')->onDelete('cascade');
			    $table->integer('user_id')->unsigned();
			    $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
			    $table->string('cover')->nullable();
			    $table->string('cover_thumbnail')->nullable();
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp_users')->hasTable('article_translations')) {
		    Schema::connection('dbp_users')->create('article_translations', function (Blueprint $table) {
			    $table->integer('article_id')->unsigned();
			    $table->foreign('article_id')->references('id')->on('articles')->onUpdate('cascade')->onDelete('cascade');
			    $table->char('iso', 3);
			    $table->foreign('iso')->references('iso')->on('dbp.languages')->onUpdate('cascade');
			    $table->string('name');
			    $table->text('description')->nullable();
			    $table->boolean('vernacular')->default(0);
			    $table->unique(['article_id', 'iso'], 'unq_article_translations');
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp_users')->hasTable('article_tags')) {
		    Schema::connection('dbp_users')->create('article_tags', function (Blueprint $table) {
			    $table->integer('article_id')->unsigned();
			    $table->foreign('article_id')->references('id')->on('articles')->onUpdate('cascade')->onDelete('cascade');
			    $table->char('iso', 3);
			    $table->foreign('iso')->references('iso')->on('dbp.languages')->onUpdate('cascade');
			    $table->string('tag');
			    $table->string('name');
			    $table->text('description')->nullable();
			    $table->unique(['article_id', 'iso'], 'unq_article_tags');
			    $table->timestamps();
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
	    Schema::connection('dbp_users')->dropIfExists('articles');
	    Schema::connection('dbp_users')->dropIfExists('article_translations');
	    Schema::connection('dbp_users')->dropIfExists('article_tags');
    }
}
