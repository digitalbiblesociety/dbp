<?php

namespace App\Console\Commands;

use App\Models\Bible\BibleFileset;
use App\Models\Bible\Book;
use App\Models\User;
use App\Models\User\Note;
use Carbon\Carbon;
use Illuminate\Console\Command;

class sync_users extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:users {info}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync User Information';

    protected $info;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $arguments = $this->arguments('info');

        switch ($arguments['info']) {
	        case "notes": {
		        \DB::transaction($this->syncNotes());
		        break;
	        }
	        case "": {

	        }
        }
    }

    public function syncNotes()
    {
	    $books = Book::all()->pluck('id','id_osis');
	    $filesets = BibleFileset::with('bible')->get();

	    $missing_books = [];
	    $missing_ids = [];
	    $missing_users = [];

	    $bibles = [];


	    foreach($filesets as $fileset) {
	    	if($fileset->bible->first()) $bibles[$fileset->id] = $fileset->bible->first()->id;
	    }

    	// Count and compare note values
    	$note_count    = Note::count();
    	$note_count_v2 = \DB::connection('dbp_users_v2')->table('note')->count();

    	if($note_count != $note_count_v2) {
    		$last_note_timestamp = Note::orderBy('created_at')->first()->created_at;
    		$new_notes = \DB::connection('dbp_users_v2')->table('note')->where('created', '>=', $last_note_timestamp->toDateTimeString())->get();

    		foreach ($new_notes as $note) {
    			//dd(Carbon::createFromTimeString($note->created)->toDateString());
    			$bible_id = false;
    			if(isset($bibles[$note->dam_id])) $bible_id = $bibles[$note->dam_id];
    			if(isset($bibles[substr($note->dam_id,0,-4)])) if(!$bible_id) $bible_id = $bibles[substr($note->dam_id,0,-4)];
			    if(isset($bibles[substr($note->dam_id,0,6)])) if(!$bible_id) $bible_id = $bibles[substr($note->dam_id,0,6)];

			    if(!$bible_id) {
				    $missing_ids = $note->dam_id;
				    continue;
			    }

			    if(!isset($books[$note->book_id])) {
			    	$missing_books[] = $note->book_id;
			    	continue;
			    }

			    $userExists = User::where('id','dbp2import_'.$note->user_id)->exists();
			    if(!$userExists) {
			    	$missing_users[] = $note->user_id;
			    	continue;
			    }

    			Note::create([
    				'user_id'     => 'dbp2import_'.$note->user_id,
				    'bible_id'    => $bible_id,
				    'book_id'     => $books[$note->book_id],
				    'chapter'     => $note->chapter_id,
				    'verse_start' => $note->verse_id,
				    'notes'       => bcrypt($note->note),
				    'created_at'  => Carbon::createFromTimeString($note->created)->toDateString(),
				    'updated_at'  => Carbon::createFromTimeString($note->updated)->toDateString(),
			    ]);
		    }
		    echo "Missing Ids:\n";
		    echo $missing_ids;

		    echo "Missing Books:\n";
    		echo $missing_books;

		    echo "Missing Users:\n";
		    echo $missing_users;
	    }

    }

}
