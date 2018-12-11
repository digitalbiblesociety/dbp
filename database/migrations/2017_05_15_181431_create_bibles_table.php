<?php



use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // if(!\Schema::connection('dbp')->hasTable('organizations')) {

        if (!\Schema::connection('dbp')->hasTable('bibles')) {
            \Schema::connection('dbp')->create('bibles', function (Blueprint $table) {
                $table->string('id', 12)->unique()->onUpdate('cascade')->onDelete('cascade');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_bibles')->references('id')->on(config('database.connections.dbp.database').'.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->string('versification', 20)->nullable();
                $table->string('numeral_system_id', 20)->nullable();
                $table->foreign('numeral_system_id', 'FK_numeral_systems_bibles')->references('id')->on(config('database.connections.dbp.database').'.numeral_systems')->onDelete('cascade')->onUpdate('cascade');
                $table->string('date')->nullable();
                $table->char('scope', 4)->nullable();
                $table->char('script', 4)->nullable();
                $table->foreign('script', 'FK_alphabets_bibles')->references('script')->on(config('database.connections.dbp.database').'.alphabets')->onDelete('cascade')->onUpdate('cascade');
                $table->string('copyright')->nullable();
                $table->string('in_progress')->nullable();
                $table->tinyInteger('priority')->default(0)->unsigned();
                $table->boolean('reviewed')->default(0)->unsigned();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
            \DB::connection('dbp')->statement('ALTER TABLE bibles ADD CONSTRAINT CHECK (reviewed=0 OR (versification IS NOT NULL AND date IS NOT NULL AND script IS NOT NULL))');
        }

        if (!\Schema::connection('dbp')->hasTable('bible_translations')) {
            \Schema::connection('dbp')->create('bible_translations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_bible_translations')->references('id')->on(config('database.connections.dbp.database').'.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->string('bible_id', 12);
                $table->foreign('bible_id', 'FK_bibles_bible_translations')->references('id')->on(config('database.connections.dbp.database').'.bibles')->onUpdate('cascade')->onDelete('cascade');
                $table->boolean('vernacular')->default(false);
                $table->boolean('vernacular_trade')->default(false);
                $table->string('name');
                $table->string('type')->nullable();
                $table->string('features')->nullable();
                $table->text('description')->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_equivalents')) {
            \Schema::connection('dbp')->create('bible_equivalents', function (Blueprint $table) {
                $table->string('bible_id', 12);
                $table->foreign('bible_id', 'FK_bibles_bible_equivalents')->references('id')->on(config('database.connections.dbp.database').'.bibles')->onDelete('cascade')->onUpdate('cascade');
                $table->string('equivalent_id');
                $table->integer('organization_id')->unsigned();
                $table->foreign('organization_id', 'FK_organizations_bible_equivalents')->references('id')->on(config('database.connections.dbp.database').'.organizations');
                $table->string('url')->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_organizations')) {
            \Schema::connection('dbp')->create('bible_organizations', function ($table) {
                $table->string('bible_id', 12)->nullable();
                $table->foreign('bible_id', 'FK_bibles_bible_organizations')->references('id')->on(config('database.connections.dbp.database').'.bibles')->onDelete('cascade')->onUpdate('cascade');
                $table->integer('organization_id')->unsigned()->nullable();
                $table->foreign('organization_id', 'FK_organizations_bible_organizations')->references('id')->on(config('database.connections.dbp.database').'.organizations');
                $table->string('relationship_type');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_links')) {
            \Schema::connection('dbp')->create('bible_links', function (Blueprint $table) {
                $table->increments('id');
                $table->string('bible_id', 12);
                $table->foreign('bible_id', 'FK_bibles_bible_links')->references('id')->on(config('database.connections.dbp.database').'.bibles')->onDelete('cascade')->onUpdate('cascade');
                $table->string('type');
                $table->text('url');
                $table->string('title');
                $table->string('provider')->nullable();
                $table->boolean('visible')->default(1);
                $table->integer('organization_id')->unsigned()->nullable();
                $table->foreign('organization_id', 'FK_organizations_bible_links')->references('id')->on(config('database.connections.dbp.database').'.organizations');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('books')) {
            \Schema::connection('dbp')->create('books', function (Blueprint $table) {
                $table->char('id', 3)->primary(); // Code USFM
                $table->char('id_usfx', 2);
                $table->string('id_osis', 12);
                $table->string('book_testament');
                $table->string('book_group')->nullable();
                $table->integer('chapters')->nullable()->unsigned();
                $table->integer('verses')->nullable()->unsigned();
                $table->string('name');
                $table->text('notes')->nullable();
                $table->text('description')->nullable();
                $table->tinyInteger('testament_order')->unsigned()->nullable();
                $table->tinyInteger('protestant_order')->unsigned()->nullable();
                $table->tinyInteger('luther_order')->unsigned()->nullable();
                $table->tinyInteger('synodal_order')->unsigned()->nullable();
                $table->tinyInteger('german_order')->unsigned()->nullable();
                $table->tinyInteger('kjva_order')->unsigned()->nullable();
                $table->tinyInteger('vulgate_order')->unsigned()->nullable();
                $table->tinyInteger('lxx_order')->unsigned()->nullable();
                $table->tinyInteger('orthodox_order')->unsigned()->nullable();
                $table->tinyInteger('nrsva_order')->unsigned()->nullable();
                $table->tinyInteger('catholic_order')->unsigned()->nullable();
                $table->tinyInteger('finnish_order')->unsigned()->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_books')) {
            \Schema::connection('dbp')->create('bible_books', function (Blueprint $table) {
                $table->string('bible_id', 12);
                $table->foreign('bible_id', 'FK_bibles_bible_books')->references('id')->on(config('database.connections.dbp.database').'.bibles')->onDelete('cascade')->onUpdate('cascade');
                $table->char('book_id', 3);
                $table->foreign('book_id', 'FK_books_bible_books')->references('id')->on(config('database.connections.dbp.database').'.books');
                $table->string('name')->nullable();
                $table->string('name_short')->nullable();
                $table->string('chapters', 491)->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('book_translations')) {
            \Schema::connection('dbp')->create('book_translations', function (Blueprint $table) {
                $table->integer('language_id')->unsigned();
                $table->foreign('language_id', 'FK_languages_book_translations')->references('id')->on(config('database.connections.dbp.database').'.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->char('book_id', 3);
                $table->foreign('book_id', 'FK_books_book_translations')->references('id')->on(config('database.connections.dbp.database').'.books');
                $table->string('name');
                $table->text('name_long');
                $table->string('name_short');
                $table->string('name_abbreviation');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('assets')) {
            \Schema::connection('dbp')->create('assets', function (Blueprint $table) {
                $table->string('id', 64)->unique();
                $table->integer('organization_id')->unsigned();
                $table->foreign('organization_id', 'FK_organizations_assets')->references('id')->on(config('database.connections.dbp.database').'.organizations')->onUpdate('cascade')->onDelete('cascade');
                $table->string('asset_type', 12);
                $table->boolean('hidden')->default(0);
                $table->string('base_name')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

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
                $table->string('asset_id', 64);
                $table->foreign('asset_id', 'FK_assets_bible_filesets')->references('id')->on(config('database.connections.dbp.database').'.assets')->onUpdate('cascade')->onDelete('cascade');
                $table->string('set_type_code', 16);
                $table->foreign('set_type_code', 'FK_bible_fileset_types_bible_filesets')->references('set_type_code')->on(config('database.connections.dbp.database').'.bible_fileset_types')->onUpdate('cascade')->onDelete('cascade');
                $table->char('set_size_code', 9);
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

        if (!\Schema::connection('dbp')->hasTable('bible_files')) {
            \Schema::connection('dbp')->create('bible_files', function (Blueprint $table) {
                $table->increments('id');
                $table->string('hash_id', 12);
                $table->foreign('hash_id', 'FK_bible_filesets_bible_files')->references('hash_id')->on(config('database.connections.dbp.database').'.bible_filesets')->onUpdate('cascade')->onDelete('cascade');
                $table->char('book_id', 3);
                $table->foreign('book_id', 'FK_books_bible_files')->references('id')->on(config('database.connections.dbp.database').'.books');
                $table->tinyInteger('chapter_start')->unsigned()->nullable();
                $table->tinyInteger('chapter_end')->unsigned()->nullable();
                $table->tinyInteger('verse_start')->unsigned()->nullable();
                $table->tinyInteger('verse_end')->unsigned()->nullable();
                $table->string('file_name');
                $table->integer('file_size')->unsigned()->nullable();
                $table->integer('duration')->unsigned()->nullable();
                $table->unique(['hash_id', 'book_id', 'chapter_start', 'verse_start'], 'unique_bible_file_by_reference');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_file_titles')) {
            \Schema::connection('dbp')->create('bible_file_titles', function (Blueprint $table) {
                $table->integer('file_id')->unsigned();
                $table->foreign('file_id', 'FK_bible_files_bible_file_titles')->references('id')->on(config('database.connections.dbp.database').'.bible_files')->onUpdate('cascade')->onDelete('cascade');
                $table->char('iso', 3);
                $table->foreign('iso', 'FK_languages_bible_file_titles')->references('iso')->on(config('database.connections.dbp.database').'.languages')->onDelete('cascade')->onUpdate('cascade');
                $table->text('title');
                $table->text('description')->nullable();
                $table->text('key_words')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_file_tags')) {
            \Schema::connection('dbp')->create('bible_file_tags', function (Blueprint $table) {
                $table->integer('file_id')->unsigned();
                $table->foreign('file_id', 'FK_languages_bible_file_titles')->references('id')->on(config('database.connections.dbp.database').'.bible_files')->onUpdate('cascade')->onDelete('cascade');
                $table->unique(['file_id', 'tag', 'value'], 'unique_bible_file_tag');
                $table->string('tag', 4);
                $table->string('value');
                $table->boolean('admin_only');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_file_video_resolutions')) {
            \Schema::connection('dbp')->create('bible_file_video_resolutions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('bible_file_id')->unsigned();
                $table->foreign('bible_file_id', 'FK_bible_files_bible_file_video_resolutions')->references('id')->on(config('database.connections.dbp.database').'.bible_files')->onUpdate('cascade')->onDelete('cascade');
                $table->string('file_name');
                $table->integer('bandwidth')->unsigned();
                $table->integer('resolution_width')->unsigned();
                $table->integer('resolution_height')->unsigned();
                $table->string('codec', 64);
                $table->boolean('stream');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_file_video_transport_stream')) {
            \Schema::connection('dbp')->create('bible_file_video_transport_stream', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('video_resolution_id')->unsigned();
                // custom name: FK_bible_file_video_resolutions_bible_file_video_transport_stream
                $table->foreign('video_resolution_id', 'FK_video_resolutions_video_transport_stream')->references('id')->on(config('database.connections.dbp.database').'.bible_file_video_resolutions')->onUpdate('cascade')->onDelete('cascade');
                $table->string('file_name')->unique();
                $table->float('runtime');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_file_timestamps')) {
            \Schema::connection('dbp')->create('bible_file_timestamps', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('bible_file_id')->unsigned();
                $table->foreign('bible_file_id', 'FK_bible_file_id_bible_file_timestamps')->references('id')->on(config('database.connections.dbp.database').'.bible_files')->onUpdate('cascade')->onDelete('cascade');
                $table->tinyInteger('verse_start')->unsigned()->nullable();
                $table->tinyInteger('verse_end')->unsigned()->nullable();
                $table->float('timestamp');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        if (!\Schema::connection('dbp')->hasTable('bible_fileset_copyrights')) {
            \Schema::connection('dbp')->create('bible_fileset_copyrights', function (Blueprint $table) {
                $table->increments('id');
                $table->char('hash_id', 12);
                $table->foreign('hash_id', 'FK_bible_filesets_bible_fileset_copyrights')->references('hash_id')->on(config('database.connections.dbp.database').'.bible_filesets')->onUpdate('cascade')->onDelete('cascade');
                $table->string('description_date');
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
                $table->foreign('organization_role', 'FK_bible_fileset_copyright_roles_bible_fileset_copyright_organizations')->references('id')->on(config('database.connections.dbp.database').'.bible_fileset_copyright_roles');
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
        \Schema::connection('dbp')->dropIfExists('bible_file_titles');
        \Schema::connection('dbp')->dropIfExists('bible_file_timestamps');
        \Schema::connection('dbp')->dropIfExists('bible_file_translations');
        \Schema::connection('dbp')->dropIfExists('bible_fileset_relations');
        \Schema::connection('dbp')->dropIfExists('bible_files');
        \Schema::connection('dbp')->dropIfExists('bible_fileset_connections');
        \Schema::connection('dbp')->dropIfExists('bible_size_translations');
        \Schema::connection('dbp')->dropIfExists('bible_sizes');
        \Schema::connection('dbp')->dropIfExists('bible_fileset_tags');
        \Schema::connection('dbp')->dropIfExists('bible_filesets');
        \Schema::connection('dbp')->dropIfExists('bible_fileset_types');
        \Schema::connection('dbp')->dropIfExists('bible_fileset_sizes');
        \Schema::connection('dbp')->dropIfExists('assets');

        \Schema::connection('dbp')->dropIfExists('book_translations');
        \Schema::connection('dbp')->dropIfExists('bible_books');
        \Schema::connection('dbp')->dropIfExists('book_codes');

        \Schema::connection('dbp')->dropIfExists('books');
        \Schema::connection('dbp')->dropIfExists('bible_links');
        \Schema::connection('dbp')->dropIfExists('bible_organizations');
        \Schema::connection('dbp')->dropIfExists('bible_translations');
        \Schema::connection('dbp')->dropIfExists('bible_equivalents');
        \Schema::connection('dbp')->dropIfExists('bibles');
    }
}
