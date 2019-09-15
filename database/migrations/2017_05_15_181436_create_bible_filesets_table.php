<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBibleFilesetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!\Schema::connection('dbp')->hasTable('bible_fileset_sizes')) {
            \Schema::connection('dbp')->create('bible_fileset_sizes', function (Blueprint $table) {
                $table->tinyIncrements('id');
                $table->char('set_size_code', 9)->unique();
                $table->string('name')->unique();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_size_translations')) {
            \Schema::connection('dbp')->create('bible_size_translations', function (Blueprint $table) {
                $table->char('set_size_code', 9)->primary();
                $table->foreign('set_size_code', 'FK_bible_fileset_sizes_bible_size_translations')->references('set_size_code')->on(config('database.connections.dbp.database').'.bible_fileset_sizes')->onUpdate('cascade')->onDelete('cascade');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_bible_size_translations')->references('id')->on(config('database.connections.dbp.database').'.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->string('name');
                $table->string('description');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }


        if (!\Schema::connection('dbp')->hasTable('bible_fileset_types')) {
            \Schema::connection('dbp')->create('bible_fileset_types', function (Blueprint $table) {
                $table->tinyIncrements('id');
                $table->string('set_type_code', 16)->unique();
                $table->string('name')->unique();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_filesets')) {
            \Schema::connection('dbp')->create('bible_filesets', function (Blueprint $table) {
                $table->string('id', 16)->index();
                $table->char('hash_id', 12)->index();
                $table->string('asset_id', 64)->index();
                $table->foreign('asset_id', 'FK_assets_bible_filesets')->references('id')->on(config('database.connections.dbp.database').'.assets')->onUpdate('cascade')->onDelete('cascade');
                $table->string('set_type_code', 16)->index();
                $table->foreign('set_type_code', 'FK_bible_fileset_types_bible_filesets')->references('set_type_code')->on(config('database.connections.dbp.database').'.bible_fileset_types')->onUpdate('cascade')->onDelete('cascade');
                $table->char('set_size_code', 9)->index();
                $table->foreign('set_size_code', 'FK_bible_fileset_sizes_bible_filesets')->references('set_size_code')->on(config('database.connections.dbp.database').'.bible_fileset_sizes')->onUpdate('cascade')->onDelete('cascade');
                $table->boolean('hidden')->default(0);
                $table->unique(['id', 'asset_id', 'set_type_code'], 'unique_prefix_for_s3');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_fileset_relations')) {
            \Schema::connection('dbp')->create('bible_fileset_relations', function (Blueprint $table) {
                $table->string('id', 16)->primary();
                $table->char('parent_hash_id', 12)->index();
                $table->foreign('parent_hash_id', 'FK_bible_filesets_bible_fileset_relations_parent_hash_id')->references('hash_id')->on(config('database.connections.dbp.database').'.bible_filesets')->onUpdate('cascade')->onDelete('cascade');
                $table->char('child_hash_id', 12)->index();
                $table->foreign('child_hash_id', 'FK_bible_filesets_bible_fileset_relations_child_hash_id')->references('hash_id')->on(config('database.connections.dbp.database').'.bible_filesets')->onUpdate('cascade')->onDelete('cascade');
                $table->string('relationship', 64);
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_fileset_tags')) {
            \Schema::connection('dbp')->create('bible_fileset_tags', function (Blueprint $table) {
                $table->primary(['hash_id','name','language_id']);
                $table->string('hash_id', 12)->index();
                $table->foreign('hash_id', 'FK_bible_filesets_bible_fileset_tags')->references('hash_id')->on(config('database.connections.dbp.database').'.bible_filesets')->onUpdate('cascade')->onDelete('cascade');
                $table->string('name');
                $table->text('description');
                $table->boolean('admin_only');
                $table->text('notes');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_bible_fileset_tags')->references('id')->on(config('database.connections.dbp.database').'.languages');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_fileset_connections')) {
            \Schema::connection('dbp')->create('bible_fileset_connections', function (Blueprint $table) {
                $table->char('hash_id', 12);
                $table->foreign('hash_id', 'FK_bible_filesets_bible_fileset_connections')->references('hash_id')->on(config('database.connections.dbp.database').'.bible_filesets')->onUpdate('cascade')->onDelete('cascade');
                $table->string('bible_id', 12)->index();
                $table->foreign('bible_id', 'FK_bibles_bible_fileset_connections')->references('id')->on(config('database.connections.dbp.database').'.bibles')->onUpdate('cascade')->onDelete('cascade');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_fileset_copyrights')) {
            \Schema::connection('dbp')->create('bible_fileset_copyrights', function (Blueprint $table) {
                $table->increments('id');
                $table->char('hash_id', 12);
                $table->foreign('hash_id', 'FK_bible_filesets_bible_fileset_copyrights')->references('hash_id')->on(config('database.connections.dbp.database').'.bible_filesets')->onUpdate('cascade')->onDelete('cascade');
                $table->integer('copyright_date')->nullable();
                $table->text('copyright');
                $table->text('copyright_description');
                $table->boolean('open_access')->default(1);
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_fileset_copyright_roles')) {
            \Schema::connection('dbp')->create('bible_fileset_copyright_roles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->text('description');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_fileset_copyright_organizations')) {
            \Schema::connection('dbp')->create('bible_fileset_copyright_organizations', function (Blueprint $table) {
                $table->increments('id');
                $table->string('hash_id', 12);
                $table->foreign('hash_id', 'FK_bible_filesets_bible_fileset_copyright_organizations')->references('hash_id')->on(config('database.connections.dbp.database').'.bible_filesets')->onUpdate('cascade')->onDelete('cascade');
                $table->integer('organization_id')->unsigned();
                $table->foreign('organization_id', 'FK_organizations_bible_fileset_copyright_organizations')->references('id')->on(config('database.connections.dbp.database').'.organizations');
                $table->integer('organization_role')->unsigned();
                $table->foreign('organization_role', 'FK_bible_fileset_copyright_roles')->references('id')->on(config('database.connections.dbp.database').'.bible_fileset_copyright_roles');
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
        \Schema::connection('dbp')->dropIfExists('bible_fileset_connections');
        \Schema::connection('dbp')->dropIfExists('bible_fileset_relations');
        \Schema::connection('dbp')->dropIfExists('bible_fileset_tags');
        \Schema::connection('dbp')->dropIfExists('bible_fileset_types');
        \Schema::connection('dbp')->dropIfExists('bible_fileset_sizes');
        \Schema::connection('dbp')->dropIfExists('bible_filesets');

        \Schema::connection('dbp')->dropIfExists('bible_size_translations');
        \Schema::connection('dbp')->dropIfExists('bible_sizes');
    }
}
