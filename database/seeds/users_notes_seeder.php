<?php

use Illuminate\Database\Seeder;

use App\Models\User\Note;
use App\Models\Bible\Book;
use App\Models\Bible\BibleFileset;
use App\Models\User\User;
use Illuminate\Support\Facades\Storage;
use App\Models\Bible\Bible;
use Carbon\Carbon;

class users_notes_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    ini_set('memory_limit', '2064M');
	    DB::statement("SET foreign_key_checks=0");
	    Note::truncate();
	    DB::statement("SET foreign_key_checks=1");

	    $books = Book::select('id_osis','id')->get()->pluck('id','id_osis')->toArray();
	    $users = User::select('id','notes')->get()->pluck('id','notes')->toArray();
	    $user_notes = Storage::disk('data')->path('dbp_users_v2___9-10-18.csv');
		$seederHelper = new \database\seeds\SeederHelper();
		$notes = $seederHelper->csv_to_array($user_notes);

	    $filesets = BibleFileset::with('bible')->get();

	    $bibles = [];
	    foreach($filesets as $fileset) {
		    if($fileset->bible->first()) $bibles[$fileset->id] = $fileset->bible->first()->id;
	    }

	    foreach ($notes as $note) {
			$skippedBibles = [];
		    $skippedBooks  = [];
		    $skippedUsers  = [];

		    if(in_array($note['dam_id'], $skippedBibles)) {continue;}
		    if(in_array($note['user_id'],$skippedUsers))  {continue;}
		    if(in_array($note['book_id'],$skippedBooks))  {continue;}

			$bible_id = false;
			if(isset($bibles[$note['dam_id']])) $bible_id = $bibles[$note['dam_id']];
			if(isset($bibles[substr($note['dam_id'],0,-4)])) if(!$bible_id) $bible_id = $bibles[substr($note['dam_id'],0,-4)];
			if(isset($bibles[substr($note['dam_id'],0,6)])) if(!$bible_id) $bible_id = $bibles[substr($note['dam_id'],0,6)];

			if(!$bible_id) {
				$skippedBibles[] = $note['dam_id'];
				continue;
			}
			if(!isset($users[$note['user_id']])) {
				$skippedUsers = $note['user_id'];
				continue;
			}
			if(!isset($books[$note['book_id']])) {
				$skippedBooks = $note['book_id'];
				continue;
			}

			Note::create([
                "user_id"       => $users[$note['user_id']],  //  => "5"
                "bible_id"      => $bible_id,                         //  => "ENGESVO2ET"
                "book_id"       => $books[$note['book_id']],          //  => "Ezra"
                "chapter"       => $note['chapter_id'],               //  => "1"
                "verse_start"   => $note['verse_id'],                 //  => "1"
                "notes"         => bcrypt($note['note']),             //  => "Note 1"
				'created_at'    => Carbon::createFromTimeString($note['created'])->toDateString(),
				'updated_at'    => Carbon::createFromTimeString($note['updated'])->toDateString(),
			]);

		}

		echo "\n";
		echo $skippedBibles;

	    echo "\n";
	    echo $skippedUsers;

	    echo "\n";
	    echo $skippedBooks;

    }


}
