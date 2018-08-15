<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {
    	if(!Schema::connection('dbp_users')->hasTable('users')){
	        Schema::connection('dbp_users')->create('users', function (Blueprint $table) {
			    $table->increments('id');
		        $table->string('name');
			    $table->string('first_name')->nullable();
			    $table->string('last_name')->nullable();
			    $table->string('email')->unique()->nullable();
			    $table->string('password');
			    $table->boolean('activated')->default(false);
			    $table->string('token');
			    $table->ipAddress('signup_ip_address')->nullable();
			    $table->ipAddress('signup_confirmation_ip_address')->nullable();
			    $table->ipAddress('signup_sm_ip_address')->nullable();
			    $table->ipAddress('admin_ip_address')->nullable();
			    $table->ipAddress('updated_ip_address')->nullable();
			    $table->ipAddress('deleted_ip_address')->nullable();
		        $table->text('notes');
			    $table->rememberToken();
			    $table->timestamps();
			    $table->softDeletes();
	        });
        }

        if(!Schema::connection('dbp_users')->hasTable('user_keys')) {
	        Schema::connection('dbp_users')->create('user_keys', function (Blueprint $table) {
			    $table->integer('user_id')->unsigned()->primary();
			    $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
			    $table->string('key',64)->unique();
			    $table->string('name')->nullable();
			    $table->text('description')->nullable();
			    $table->timestamps();
	        });
        }

	    if(!Schema::connection('dbp_users')->hasTable('user_organizations')) {
	        Schema::connection('dbp_users')->create('user_organizations', function (Blueprint $table) {
			    $table->integer('user_id')->unsigned()->primary();
			    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
			    $table->string('title');
			    $table->string('role');
			    $table->integer('organization_id')->unsigned();
			    //$table->foreign('organization_id')->references('id')->on(new \Illuminate\Database\Query\Expression($db . '.organizations'));
			    $table->timestamps();
	        });
	    }

	    if(!Schema::connection('dbp_users')->hasTable('password_resets')) {
	        Schema::connection('dbp_users')->create('password_resets', function (Blueprint $table) {
			    $table->increments('id');
			    $table->string('email')->index();
			    $table->string('token')->index();
			    $table->timestamp('created_at')->nullable();
	        });
	    }

	    if(!Schema::connection('dbp_users')->hasTable('social_logins')) {
	        Schema::connection('dbp_users')->create('social_logins', function (Blueprint $table) {
			    $table->increments('id');
			    $table->integer('user_id')->unsigned();
			    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			    $table->string('provider', 50);
			    $table->text('social_id');
			    $table->timestamps();
	        });
	    }

	    if(!Schema::connection('dbp_users')->hasTable('activations')) {
	        Schema::connection('dbp_users')->create('activations', function (Blueprint $table) {
			    $table->increments('id');
			    $table->integer('user_id')->unsigned()->index();
			    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			    $table->string('token');
			    $table->ipAddress('ip_address');
			    $table->timestamps();
	        });
	    }

	    if(!Schema::connection('dbp_users')->hasTable('profiles')) {
    		Schema::connection('dbp_users')->create('profiles', function (Blueprint $table) {
		        $table->increments('id');
		        $table->integer('user_id')->unsigned()->index();
		        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		        $table->string('location')->nullable();
		        $table->text('bio')->nullable();
		        $table->string('twitter_username')->nullable();
		        $table->string('github_username')->nullable();
		        $table->string('avatar')->nullable();
		        $table->boolean('avatar_status')->default(0);
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
        Schema::connection('dbp_users')->dropIfExists('users');
	    Schema::connection('dbp_users')->dropIfExists('user_keys');
	    Schema::connection('dbp_users')->dropIfExists('user_organizations');
	    Schema::connection('dbp_users')->dropIfExists('password_resets');
	    Schema::connection('dbp_users')->dropIfExists('social_logins');
	    Schema::connection('dbp_users')->dropIfExists('activations');
	    Schema::connection('dbp_users')->dropIfExists('profiles');
    }
}
