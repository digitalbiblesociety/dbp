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

    	if(!Schema::connection('dbp')->hasTable('organizations')) {
		    Schema::connection('dbp')->create('organizations', function (Blueprint $table) {
			    $table->increments('id');
			    $table->string('slug', 191)->unique()->index();
			    $table->string('abbreviation', 24)->unique()->index()->nullable();
			    $table->text('notes')->nullable();
			    $table->string('primaryColor', 7)->nullable();
			    $table->string('secondaryColor', 7)->nullable();
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
			    $table->foreign('country')->references('id')->on('countries')->onUpdate('cascade');
			    $table->integer('zip')->nullable()->unsigned();
			    $table->string('phone')->nullable();
			    $table->string('email')->nullable();
			    $table->string('email_director')->nullable();
			    $table->float('latitude', 11, 7)->nullable();
			    $table->float('longitude', 11, 7)->nullable();
			    $table->timestamp('created_at')->useCurrent();
			    $table->timestamp('updated_at')->useCurrent();
		    });
	    }

            if(!Schema::connection('dbp')->hasTable('organization_translations')) {
	            Schema::connection('dbp')->create('organization_translations', function (Blueprint $table) {
		            $table->integer('language_id')->unsigned();
		            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
		            $table->char('language_iso', 3)->index();
		            $table->foreign('language_iso')->references('iso')->on('languages')->onDelete('cascade')->onUpdate('cascade');
		            $table->integer('organization_id')->unsigned();
		            $table->foreign('organization_id')->references('id')->on('organizations');
		            $table->boolean('vernacular')->default(false);
		            $table->boolean('alt')->default(false);
		            $table->string('name');
		            $table->text('description')->nullable();
		            $table->string('description_short')->nullable();
		            $table->timestamp('created_at')->useCurrent();
		            $table->timestamp('updated_at')->useCurrent();
	            });
            }

		    if(!Schema::connection('dbp')->hasTable('organization_relationships')) {
			    Schema::connection('dbp')->create('organization_relationships', function ($table) {
				    $table->integer('organization_parent_id')->unsigned();
				    $table->foreign('organization_parent_id')->references('id')->on('organizations');
				    $table->integer('organization_child_id')->unsigned();
				    $table->foreign('organization_child_id')->references('id')->on('organizations');
				    $table->string('type');
				    $table->string('relationship_id');
				    $table->timestamp('created_at')->useCurrent();
				    $table->timestamp('updated_at')->useCurrent();
			    });
		    }

		    if(!Schema::connection('dbp')->hasTable('organization_logos')) {
			    Schema::connection('dbp')->create('organization_logos', function ($table) {
				    $table->integer('organization_id')->unsigned();
				    $table->foreign('organization_id')->references('id')->on('organizations');
				    $table->integer('language_id')->unsigned();
				    $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
				    $table->char('language_iso', 3)->nullable();
				    $table->foreign('language_iso')->references('iso')->on('languages');
				    $table->string('url')->nullable();
				    $table->boolean('icon')->default(false);
				    $table->timestamp('created_at')->useCurrent();
				    $table->timestamp('updated_at')->useCurrent();
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
	    Schema::connection('dbp')->dropIfExists('user_roles');
	    Schema::connection('dbp')->dropIfExists('organization_logos');
	    Schema::connection('dbp')->dropIfExists('organization_relationships');
	    Schema::connection('dbp')->dropIfExists('organization_translations');
        Schema::connection('dbp')->dropIfExists('organizations');
    }
}
