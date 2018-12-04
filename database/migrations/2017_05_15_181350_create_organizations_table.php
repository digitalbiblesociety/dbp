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
        if (!Schema::connection('dbp')->hasTable('organizations')) {
            Schema::connection('dbp')->create('organizations', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug', 191)->unique()->index();
                $table->string('abbreviation', 6)->unique()->index()->nullable();
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
                $table->foreign('country')->references('id')->on(config('database.connections.dbp.database').'.countries')->onUpdate('cascade');
                $table->string('zip');
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('email_director')->nullable();
                $table->float('latitude', 11, 7)->nullable();
                $table->float('longitude', 11, 7)->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('organization_translations')) {
            Schema::connection('dbp')->create('organization_translations', function (Blueprint $table) {
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id')->references('id')->on(config('database.connections.dbp.database').'.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->integer('organization_id')->unsigned();
                $table->foreign('organization_id')->references('id')->on(config('database.connections.dbp.database').'.organizations');
                $table->boolean('vernacular')->default(false);
                $table->boolean('alt')->default(false);
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('description_short')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                $table->primary(['language_id','organization_id','name'], 'organization_translations_primary');
            });
        }

        if (!Schema::connection('dbp')->hasTable('organization_relationships')) {
            Schema::connection('dbp')->create('organization_relationships', function ($table) {
                $table->integer('organization_parent_id')->unsigned();
                $table->foreign('organization_parent_id')->references('id')->on(config('database.connections.dbp.database').'.organizations');
                $table->integer('organization_child_id')->unsigned();
                $table->foreign('organization_child_id')->references('id')->on(config('database.connections.dbp.database').'.organizations');
                $table->string('type');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                $table->primary(['organization_child_id','organization_parent_id','type'], 'organization_relationships_primary');
            });
        }

        if (!Schema::connection('dbp')->hasTable('organization_logos')) {
            Schema::connection('dbp')->create('organization_logos', function ($table) {
                $table->integer('organization_id')->unsigned();
                $table->foreign('organization_id')->references('id')->on(config('database.connections.dbp.database').'.organizations');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id')->references('id')->on(config('database.connections.dbp.database').'.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->char('language_iso', 3)->nullable();
                $table->foreign('language_iso')->references('iso')->on(config('database.connections.dbp.database').'.languages');
                $table->string('url')->nullable();
                $table->boolean('icon')->default(false);
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
                $table->primary(['organization_id','language_id','icon']);
            });
        }

        if (!Schema::connection('dbp_users')->hasTable('user_organizations')) {
            Schema::connection('dbp_users')->create('user_organizations', function (Blueprint $table) {
                $table->integer('user_id')->unsigned()->primary();
                $table->foreign('user_id')->references('id')->on(config('database.connections.dbp_users.database').'.users')->onDelete('cascade')->onUpdate('cascade');
                $table->string('title');
                $table->string('role');
                $table->integer('organization_id')->unsigned();
                $table->foreign('organization_id')->references('id')->on(config('database.connections.dbp.database').'.organizations');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
        Schema::connection('dbp')->dropIfExists('user_organizations');
        Schema::connection('dbp')->dropIfExists('user_roles');
        Schema::connection('dbp')->dropIfExists('organization_logos');
        Schema::connection('dbp')->dropIfExists('organization_relationships');
        Schema::connection('dbp')->dropIfExists('organization_translations');
        Schema::connection('dbp')->dropIfExists('organizations');
    }
}
