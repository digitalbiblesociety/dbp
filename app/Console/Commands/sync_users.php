<?php

namespace App\Console\Commands;

use App\Models\Bible\BibleFileset;
use App\Models\Bible\Book;
use App\Models\User\Account;
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
		        $this->syncNotes();
		        break;
	        }
	        case "remove_notes": {
		        break;
	        }

	        case "accounts": {
	        	$this->syncAccounts();
				break;
	        }
        }
    }

    public function syncAccounts()
    {
	    ini_set('memory_limit', '2064M');
	    set_time_limit(-1);

	    \DB::statement("SET foreign_key_checks=0");
	    Account::truncate();
	    \DB::statement("SET foreign_key_checks=1");
	    $missingUsers = [];

    	$accounts = \DB::connection('dbp_users_v2')->table('user_remote')->where('remote_type','!=','cookie')->select(['user_id','remote_id','remote_type'])->distinct()->get();
    	foreach($accounts as $account) {
    		$user = User::where('notes',$account->user_id)->first();
    		if(!$user) {$missingUsers[] = $account->user_id;continue;}
    		if(Account::where(['user_id' => $user->id,'provider_id' => $account->remote_type])->exists()) { continue; }
    		Account::create([
    			'user_id'          => $user->id,
			    'provider_id'      => $account->remote_type,
			    'provider_user_id' => $account->remote_id,
 		    ]);
	    }
	    print_r($missingUsers);
    }

    public function syncNotes()
    {
	    $books = Book::all()->pluck('id','id_osis');
	    $filesets = BibleFileset::with('bible')->get();

	    $bibles = [];
	    $missing_ids = [];
	    $missing_users = [];
	    $missing_books = [];

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
		                                   ->where('created', '>', $last_note_timestamp->toDateTimeString())
		                                   ->chunk(50000, function($new_notes) use ($books,$bibles)
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

				    if(Note::where('id',$note->id)->exists()) {continue;}

				    Note::create([
				    	'id'          => $note->id,
					    'user_id'     => $user->id,
					    'bible_id'    => $bible_id,
					    'book_id'     => $books[$note->book_id],
					    'chapter'     => $note->chapter_id,
					    'verse_start' => $note->verse_id,
					    'notes'       => $note->note,
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
