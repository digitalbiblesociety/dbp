<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLexiconTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dbp')->create('lexicons', function (Blueprint $table) {
            $table->char('id', 5)->primary();
            $table->string('base_word', 64);
            $table->string('usage');
            $table->string('def_lit',124)->nullable();
            $table->string('def_short');
            $table->string('def_long');
            $table->string('deriv')->nullable();
            $table->string('pronun_ipa', 64);
            $table->string('pronun_ipa_mod', 64);
            $table->string('pronun_sbl', 64);
            $table->string('pronun_dic', 64);
            $table->string('pronun_dic_mod', 64);
            $table->string('part_of_speech', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lexicon');
    }
}
