<?php



use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp')->hasTable('resources')) {
            Schema::connection('dbp')->create('resources', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug')->nullable();
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_resources')->references('id')->on(config('database.connections.dbp.database').'.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->integer('organization_id')->unsigned();
                $table->foreign('organization_id', 'FK_organizations_resources')->references('id')->on(config('database.connections.dbp.database').'.organizations')->onUpdate('cascade')->onDelete('cascade');
                $table->string('source_id')->nullable();
                $table->string('cover')->nullable();
                $table->string('cover_thumbnail')->nullable();
                $table->string('date')->nullable();
                $table->string('type');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('resource_links')) {
            Schema::connection('dbp')->create('resource_links', function (Blueprint $table) {
                $table->integer('resource_id')->unsigned();
                $table->foreign('resource_id', 'FK_organizations_resource_links')->references('id')->on(config('database.connections.dbp.database').'.resources')->onUpdate('cascade')->onDelete('cascade');
                $table->string('title');
                $table->string('size')->nullable();
                $table->string('type');
                $table->string('url');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp')->hasTable('resource_translations')) {
            Schema::connection('dbp')->create('resource_translations', function (Blueprint $table) {
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_resource_translations')->references('id')->on(config('database.connections.dbp.database').'.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->integer('resource_id')->unsigned();
                $table->foreign('resource_id', 'FK_resources_resource_translations')->references('id')->on(config('database.connections.dbp.database').'.resources')->onUpdate('cascade')->onDelete('cascade');
                $table->boolean('vernacular');
                $table->boolean('tag');
                $table->string('title');
                $table->text('description')->nullable();
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
        Schema::connection('dbp')->dropIfExists('resource_translations');
        Schema::connection('dbp')->dropIfExists('resource_links');
        Schema::connection('dbp')->dropIfExists('resources');
    }
}
