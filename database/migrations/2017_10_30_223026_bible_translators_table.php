<?php



use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BibleTranslatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::connection('dbp')->hasTable('translators')) {
            Schema::connection('dbp')->create('translators', function (Blueprint $table) {
                $table->string('id', 191)->primary()->unique();
                $table->string('name');
                $table->string('born')->nullable();
                $table->string('died')->nullable();
                $table->text('description');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
        }

        if (!Schema::connection('dbp')->hasTable('bible_translator')) {
            Schema::connection('dbp')->create('bible_translator', function ($table) {
                $table->string('bible_id', 12)->index();
                $table->foreign('bible_id')->references('id')->on('bibles')->onUpdate('cascade')->onDelete('cascade');
                $table->string('translator_id', 191);
                $table->foreign('translator_id')->references('id')->on('translators')->onDelete('cascade')->onUpdate('cascade');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent();
            });
        }

        if (!Schema::connection('dbp')->hasTable('translator_relations')) {
            Schema::connection('dbp')->create('translator_relations', function (Blueprint $table) {
                $table->string('translator_id', 191);
                $table->foreign('translator_id')->references('id')->on('translators')->onDelete('cascade')->onUpdate('cascade');
                $table->string('translator_relation_id', 191);
                $table->foreign('translator_relation_id')->references('id')->on('translators')->onUpdate('cascade')->onDelete('cascade');
                $table->integer('organization_id')->unsigned()->nullable();
                $table->foreign('organization_id')->references('id')->on('organizations');
                $table->string('type');
                $table->string('description');
                $table->string('notes');
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
        Schema::connection('dbp')->dropIfExists('translator_relations');
        Schema::connection('dbp')->dropIfExists('bible_translator');
        Schema::connection('dbp')->dropIfExists('translators');
    }
}
