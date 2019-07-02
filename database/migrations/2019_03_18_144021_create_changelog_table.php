<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangelogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('dbp_users')->hasTable('changelog')) {
            Schema::connection('dbp_users')->create('changelog', function (Blueprint $table) {
                $table->increments('id');
                $table->string('subheading');
                $table->string('title');
                $table->text('description');
                $table->timestamp('released_at')->nullable();
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
        Schema::dropIfExists('changelog');
    }
}
