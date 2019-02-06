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
        if (!Schema::connection('dbp_users')->hasTable('users')) {
            Schema::connection('dbp_users')->create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('v2_id')->unsigned();
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
                $table->text('notes')->nullable();
                $table->rememberToken();
                $table->timestamp('last_login')->nullable()->default(NULL);
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                $table->softDeletes();
            });
        }

        if (!Schema::connection('dbp_users')->hasTable('user_keys')) {
            Schema::connection('dbp_users')->create('user_keys', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id', 'FK_users_user_keys')->references('id')->on(config('database.connections.dbp_users.database').'.users')->onUpdate('cascade');
                $table->string('key', 64)->unique();
                $table->string('name')->nullable();
                $table->text('description')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp_users')->hasTable('password_resets')) {
            Schema::connection('dbp_users')->create('password_resets', function (Blueprint $table) {
                $table->increments('id');
                $table->string('email')->index();
                $table->string('token')->index();
                $table->string('reset_path')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->unique(['token','email']);
            });
        }

        if (!Schema::connection('dbp_users')->hasTable('activations')) {
            Schema::connection('dbp_users')->create('activations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned()->index();
                $table->foreign('user_id', 'FK_users_activations')->references('id')->on(config('database.connections.dbp_users.database').'.users')->onDelete('cascade');
                $table->string('token');
                $table->ipAddress('ip_address');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp_users')->hasTable('profiles')) {
            Schema::connection('dbp_users')->create('profiles', function (Blueprint $table) {
                $table->integer('user_id')->unsigned()->index();
                $table->foreign('user_id', 'FK_users_profiles')->references('id')->on(config('database.connections.dbp_users.database').'.users')->onDelete('cascade')->onUpdate('cascade');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_profiles')->references('id')->on(config('database.connections.dbp.database').'.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->text('bio')->nullable();
                $table->string('address_1')->nullable();
                $table->string('address_2')->nullable();
                $table->string('address_3')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('zip')->nullable();
                $table->char('country_id', 2)->nullable();
                $table->foreign('country_id', 'FK_countries_profiles')->references('id')->on(config('database.connections.dbp.database').'.countries')->onUpdate('cascade');
                $table->string('avatar')->nullable();
                $table->tinyInteger('sex')->default(0)->unsigned(); // Aligns to the ISO/IEC 5218 Standards
                $table->string('phone', 22)->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                $table->timestamp('birthday')->nullable();
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
        Schema::connection('dbp_users')->dropIfExists('password_resets');
        Schema::connection('dbp_users')->dropIfExists('activations');
        Schema::connection('dbp_users')->dropIfExists('profiles');
    }
}
