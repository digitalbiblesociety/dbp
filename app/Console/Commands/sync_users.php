<?php

namespace App\Console\Commands;

use App\Models\Bible\BibleFileset;
use App\Models\Bible\Book;
use App\Models\User\User;
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
    		$last_note_timestamp = Note::orderBy('created_at','asc')->first();
    		if(!$last_note_timestamp) {
			    $last_note_timestamp = Carbon::create(1980,1,1);
		    } else {
    			$last_note_timestamp = $last_note_timestamp->created_at;
		    }

    		\DB::connection('dbp_users_v2')->table('note')->orderBy('created','asc')
		                                   ->where('created', '>=', $last_note_timestamp->toDateTimeString())
		                                   ->chunk(1000, function($new_notes) use ($books,$bibles)
		    {
			    foreach ($new_notes as $note) {
				    $bible_id = false;
				    if(isset($bibles[$note->dam_id])) $bible_id = $bibles[$note->dam_id];
				    if(isset($bibles[substr($note->dam_id,0,-4)])) if(!$bible_id) $bible_id = $bibles[substr($note->dam_id,0,-4)];
				    if(isset($bibles[substr($note->dam_id,0,6)])) if(!$bible_id) $bible_id = $bibles[substr($note->dam_id,0,6)];

				    if(!$bible_id) {
					    //echo "\n Missing Bible_ID";
					    $missing_ids = $note->dam_id;
					    continue;
				    }

				    if(!isset($books[$note->book_id])) {
					    //echo "\n Missing Books";
					    $missing_books[] = $note->book_id;
					    continue;
				    }

				    $user = User::where('notes',$note->user_id)->first();
				    if(!$user) {
				    	//echo "\n Missing User";
					    $missing_users[] = $note->user_id;
					    continue;
				    }

				    Note::create([
					    'user_id'     => $user->id,
					    'bible_id'    => $bible_id,
					    'book_id'     => $books[$note->book_id],
					    'chapter'     => $note->chapter_id,
					    'verse_start' => $note->verse_id,
					    'notes'       => bcrypt($note->note),
					    'created_at'  => Carbon::createFromTimeString($note->created)->toDateString(),
					    'updated_at'  => Carbon::createFromTimeString($note->updated)->toDateString(),
				    ]);
			    }
		    });

		    echo "Missing Ids:\n";
		    echo $missing_ids;

		    echo "Missing Books:\n";
		    echo $missing_books;

		    echo "Missing Users:\n";
		    echo $missing_users;

	    }

    }

}
