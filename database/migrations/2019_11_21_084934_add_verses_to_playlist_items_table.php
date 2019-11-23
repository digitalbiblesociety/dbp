<?php

use App\Models\Playlist\PlaylistItems;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVersesToPlaylistItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dbp_users')->table('playlist_items', function (Blueprint $table) {
            $table->integer('verses')->after('verse_end')->unsigned();
        });
        $play_list_items = PlaylistItems::all();
        foreach ($play_list_items as $play_list_item) {
            $play_list_item->calculateVerses()->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('dbp_users')->table('playlist_items', function (Blueprint $table) {
            $table->dropColumn('verses');
        });
    }
}
