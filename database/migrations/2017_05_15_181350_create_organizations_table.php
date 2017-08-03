<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug',191)->unique()->index();
	        $table->char('abbreviation',6)->unique()->index()->nullable();
            $table->text('notes')->nullable();
            $table->string('primaryColor',7)->nullable();
            $table->string('secondaryColor',7)->nullable();
            $table->boolean('inactive')->default(false)->nullable();
            $table->string('url_facebook')->nullable();
            $table->string('url_website')->nullable();
	        $table->string('url_donate')->nullable();
            $table->string('url_twitter')->nullable();
	        $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
	        $table->foreign('country')->references('id')->on('geo.countries')->onUpdate('cascade');
            $table->integer('zip')->nullable()->unsigned();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
        });

        Schema::create('organization_translations', function (Blueprint $table) {
            $table->char('language_iso', 3)->index();
            $table->foreign('language_iso')->references('iso')->on('geo.languages')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->boolean('vernacular')->default(false);
	        $table->boolean('alt')->default(false);
            $table->string('name');
            $table->text('description')->nullable();
	        $table->string('description_short')->nullable();
        });

	    Schema::create('user_roles', function (Blueprint $table) {
		    $table->string('user_id')->primary();
		    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
		    $table->string('role');
		    $table->integer('organization_id')->unsigned();
		    $table->foreign('organization_id')->references('id')->on('organizations');
	    });

	    Schema::create('organization_relationships', function($table) {
		    $table->integer('organization_parent_id')->unsigned();
		    $table->foreign('organization_parent_id')->references('id')->on('organizations');
		    $table->integer('organization_child_id')->unsigned();
		    $table->foreign('organization_child_id')->references('id')->on('organizations');
		    $table->string('type');
		    $table->string('relationship_id');
	    });

	    Schema::create('organization_services', function($table) {
		    $table->integer('organization_id')->unsigned();
		    $table->foreign('organization_id')->references('id')->on('organizations');
		    $table->string('type');
		    $table->string('name');
		    $table->text('description')->nullable();
	    });

	    Schema::create('organization_logos', function($table) {
		    $table->integer('organization_id')->unsigned();
		    $table->foreign('organization_id')->references('id')->on('organizations');
		    $table->char('language_iso', 3)->nullable();
		    $table->foreign('language_iso')->references('iso')->on('geo.languages');
		    $table->string('logo')->nullable();
		    $table->boolean('icon')->default(false);
	    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('user_roles');
	    Schema::dropIfExists('organization_logos');
	    Schema::dropIfExists('organization_relationships');
	    Schema::dropIfExists('organization_translations');
        Schema::dropIfExists('organization_services');
        Schema::dropIfExists('organizations');
    }
}
