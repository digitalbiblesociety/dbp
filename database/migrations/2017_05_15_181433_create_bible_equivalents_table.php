<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBibleEquivalentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::connection('dbp')->dropIfExists('bible_equivalents');
    }
}
