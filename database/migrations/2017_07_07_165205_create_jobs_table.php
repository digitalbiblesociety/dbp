<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    if(!Schema::connection('dbp')->hasTable('jobs')) {
		    Schema::connection('dbp')->create('jobs', function (Blueprint $table) {
			    $table->bigIncrements('id');
			    $table->string('queue');
			    $table->longText('payload');
			    $table->tinyInteger('attempts')->unsigned();
			    $table->unsignedInteger('reserved_at')->nullable();
			    $table->unsignedInteger('available_at');
			    $table->unsignedInteger('created_at');
			    $table->index(['queue', 'reserved_at']);
		    });
	    }

	    if(!Schema::connection('dbp')->hasTable('failed_jobs')) {
		    Schema::connection('dbp')->create('failed_jobs', function (Blueprint $table) {
			    $table->bigIncrements('id');
			    $table->text('connection');
			    $table->text('queue');
			    $table->longText('payload');
			    $table->longText('exception');
			    $table->timestamp('failed_at')->useCurrent();
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
        Schema::connection('dbp')->dropIfExists('jobs');
	    Schema::connection('dbp')->dropIfExists('failed_jobs');
    }
}
