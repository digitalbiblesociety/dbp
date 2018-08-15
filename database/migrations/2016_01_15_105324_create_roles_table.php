<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	if(!Schema::connection('dbp_users')->hasTable('roles')) {
		    Schema::connection('dbp_users')->create('roles', function (Blueprint $table) {
			    $table->increments('id')->unsigned();
			    $table->string('name');
			    $table->string('slug')->unique();
			    $table->string('description')->nullable();
			    $table->integer('level')->default(1);
			    $table->timestamps();
		    });
	    }

        if(!Schema::connection('dbp_users')->hasTable('role_user')) {
	        Schema::connection('dbp_users')->create('role_user', function (Blueprint $table) {
		        $table->increments('id')->unsigned();
		        $table->integer('role_id')->unsigned()->index();
		        $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
		        $table->integer('user_id')->unsigned()->index();
		        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		        $table->timestamps();
	        });
        }

	    if(!Schema::connection('dbp_users')->hasTable('permissions')) {
		    Schema::connection('dbp_users')->create('permissions', function (Blueprint $table) {
			    $table->increments('id')->unsigned();
			    $table->string('name');
			    $table->string('slug')->unique();
			    $table->string('description')->nullable();
			    $table->string('model')->nullable();
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp_users')->hasTable('permission_role')) {
		    Schema::connection('dbp_users')->create('permission_role', function (Blueprint $table) {
			    $table->increments('id')->unsigned();
			    $table->integer('permission_id')->unsigned()->index();
			    $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
			    $table->integer('role_id')->unsigned()->index();
			    $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
			    $table->timestamps();
		    });
	    }

	    if(!Schema::connection('dbp_users')->hasTable('permission_user')) {
		    Schema::connection('dbp_users')->create('permission_user', function (Blueprint $table) {
			    $table->increments('id')->unsigned();
			    $table->integer('permission_id')->unsigned()->index();
			    $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
			    $table->integer('user_id')->unsigned()->index();
			    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::connection('dbp_users')->dropIfExists('roles');
	    Schema::connection('dbp_users')->dropIfExists('role_user');
	    Schema::connection('dbp_users')->dropIfExists('permissions');
	    Schema::connection('dbp_users')->dropIfExists('permission_role');
	    Schema::connection('dbp_users')->dropIfExists('permission_user');
    }
}