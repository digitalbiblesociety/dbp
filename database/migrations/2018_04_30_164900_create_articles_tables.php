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
        if (!Schema::connection('dbp_users')->hasTable('articles')) {
            Schema::connection('dbp_users')->create('articles', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('organization_id')->unsigned();
                $table->foreign('organization_id', 'FK_organizations_articles')->references('id')->on(config('database.connections.dbp.database').'.organizations')->onUpdate('cascade')->onDelete('cascade');
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id', 'FK_users_articles')->references('id')->on(config('database.connections.dbp_users.database').'.users')->onUpdate('cascade')->onDelete('cascade');
                $table->string('cover')->nullable();
                $table->string('cover_thumbnail')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp_users')->hasTable('article_translations')) {
            Schema::connection('dbp_users')->create('article_translations', function (Blueprint $table) {
                $table->integer('article_id')->unsigned();
                $table->foreign('article_id', 'FK_articles_article_translations')->references('id')->on(config('database.connections.dbp_users.database').'.articles')->onUpdate('cascade')->onDelete('cascade');
                $table->char('iso', 3);
                $table->foreign('iso', 'FK_languages_article_translations')->references('iso')->on(config('database.connections.dbp.database').'.languages')->onUpdate('cascade');
                $table->string('slug')->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->boolean('vernacular')->default(0);
                $table->unique(['article_id', 'iso'], 'unq_article_translations');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!Schema::connection('dbp_users')->hasTable('article_tags')) {
            Schema::connection('dbp_users')->create('article_tags', function (Blueprint $table) {
                $table->integer('article_id')->unsigned();
                $table->foreign('article_id', 'FK_articles_article_tags')->references('id')->on(config('database.connections.dbp_users.database').'.articles')->onUpdate('cascade')->onDelete('cascade');
                $table->char('iso', 3);
                $table->foreign('iso', 'FK_languages_article_tags')->references('iso')->on(config('database.connections.dbp.database').'.languages')->onUpdate('cascade');
                $table->string('tag');
                $table->string('name');
                $table->text('description')->nullable();
                $table->unique(['article_id', 'iso'], 'unq_article_tags');
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
        Schema::connection('dbp_users')->dropIfExists('articles');
        Schema::connection('dbp_users')->dropIfExists('article_translations');
        Schema::connection('dbp_users')->dropIfExists('article_tags');
    }
}
