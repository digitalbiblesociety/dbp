<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlanImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('dbp_users')->hasTable('plans')) {
            Schema::connection('dbp_users')->table('plans', function (Blueprint $table) {
                $table->string('thumbnail', 191)->nullable()->after('name')->default(null);
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
        if (Schema::connection('dbp_users')->hasTable('plans')) {
            Schema::connection('dbp_users')->table('plans', function (Blueprint $table) {
                $table->dropColumn('thumbnail');
            });
        }
    }
}
