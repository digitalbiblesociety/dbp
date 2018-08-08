<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\Models\User\Note;
use App\Models\User\User;
use App\Models\Bible\Bible;
use App\Models\Bible\Book;

class users_notes_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    DB::statement("SET foreign_key_checks=0");
	    Note::truncate();
	    DB::statement("SET foreign_key_checks=1");

	    $seederHelper = new \database\seeds\SeederHelper();
	    ini_set('memory_limit', '2064M');
	    set_time_limit(-1);
	    $user_notes = $seederHelper->csv_to_array(storage_path('data/user_notes.csv'));
	    foreach ($user_notes as $note) {
	    	foreach ($note as $key => $element) if($element == "NULL") $note[$key] = null;
	    	if(User::where('id',$note['user_id'])->exists()) Note::create($note);
	    }

    }

}
