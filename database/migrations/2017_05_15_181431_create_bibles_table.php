<?php



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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::connection('dbp')->dropIfExists('assets');
        \Schema::connection('dbp')->dropIfExists('book_codes');
        \Schema::connection('dbp')->dropIfExists('book_translations');
        \Schema::connection('dbp')->dropIfExists('bible_books');
        \Schema::connection('dbp')->dropIfExists('books');
        \Schema::connection('dbp')->dropIfExists('bible_equivalents');
        \Schema::connection('dbp')->dropIfExists('bibles');
    }
}
