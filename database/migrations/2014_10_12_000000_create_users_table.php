<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
	        $table->char('id', 16)->primary();
	        $table->string('name');
	        $table->string('password')->nullable();
	        $table->string('nickname')->nullable();
	        $table->string('avatar')->nullable();
	        $table->string('email')->unique()->nullable();
	        $table->rememberToken();
	        $table->timestamps();
        });

	    Schema::create('user_accounts', function (Blueprint $table) {
		    $table->char('user_id', 16)->primary();
		    $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
		    $table->string('provider');
		    $table->string('provider_user_id');
		    $table->timestamps();
	    });

	    Schema::create('user_keys', function (Blueprint $table) {
		    $table->char('user_id', 16)->primary();
		    $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
		    $table->string('key',24);
		    $table->string('name');
		    $table->text('description');
		    $table->timestamps();
	    });

	    Schema::create('cache', function ($table) {
		    $table->string('key')->unique();
		    $table->text('value');
		    $table->integer('expiration');
	    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('cache');
	    Schema::dropIfExists('user_accounts');
	    Schema::dropIfExists('user_notes');
	    Schema::dropIfExists('user_keys');
        Schema::dropIfExists('users');
    }
}
