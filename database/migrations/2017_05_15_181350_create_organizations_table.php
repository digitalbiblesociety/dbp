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
            $table->boolean('fobai')->default(false)->nullable();
            $table->text('notes');
            $table->string('primaryColor',7)->nullable();
            $table->string('secondaryColor',7)->nullable();
            $table->string('logo')->nullable();
            $table->boolean('inactive')->default(false)->nullable();
            $table->boolean('globalContributor')->default(false)->nullable();
            $table->boolean('libraryContributor')->default(false)->nullable();
            $table->string('facebook')->nullable();
            $table->string('website')->nullable();
            $table->string('twitter')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
        });

        Schema::create('organization_translations', function (Blueprint $table) {
            $table->char('glotto_id', 8)->index();
            $table->foreign('glotto_id')->references('id')->on('geo.languages')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('organization_id')->unsigned();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->boolean('vernacular')->default(false);
            $table->string('name');
            $table->text('description');
        });

	    Schema::create('user_roles', function (Blueprint $table) {
		    $table->string('user_id')->primary();
		    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
		    $table->string('role');
		    $table->integer('organization_id')->unsigned();
		    $table->foreign('organization_id')->references('id')->on('organizations');
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
	    Schema::dropIfExists('user_roles');
        Schema::dropIfExists('organization_translations');
        Schema::dropIfExists('organizations');
    }
}
