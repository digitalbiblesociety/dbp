<?php

use Illuminate\Database\Seeder;
use App\Models\User\User;

use App\Models\Bible\BibleFileset;
use App\Models\Bible\Book;
use App\Models\User\Study\HighlightColor;
use App\Models\User\Study\Highlight;
use App\Models\User\Study\Note;
use App\Models\User\Study\Bookmark;
use Carbon\Carbon;

class users_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    // $this->seedBookmarks();
	    // $this->seedAccounts();
	    // $this->seedNotes();
	    // $this->seedUsers();
	    $this->seedHighlights();
    }

    public function seedUsers()
    {
	    $first_user = \App\Models\User\User::orderBy('id','DESC')->first();
	    $first_user_id = $first_user->id ?? 0;

	    \DB::connection('dbp_users_v2')->table('user')
			->where('id','>',$first_user_id)->orderBy('id')
	        ->chunk(500, function ($users) {
		        foreach($users as $user) {
				    $currentUser = [
					    'id'                => $user->id,
					    'name'              => $user->username ?? $user->email,
					    'password'          => bcrypt($user->password),
					    'first_name'        => $user->first_name,
					    'last_name'         => $user->last_name,
					    'token'             => str_random(24),
					    'email'             => $user->email,
					    'activated'         => (int) $user->confirmed,
				    ];
				    User::create($currentUser);
		        }
	        });
    }

    public function seedHighlights()
    {
    	$first_highlight = Highlight::orderBy('id','DESC')->first();
    	$first_highlight_id = $first_highlight->id ?? 0;

	    $colorEquivalents = ['orange' => 1, 'green' => 2, 'yellow' => 3, 'blue' => 4, 'pink' => 5];
	    $bibles = \App\Models\Bible\Bible::with('filesets')->get();

	    $books = Book::select(['id_osis','id_usfx','id','protestant_order'])->get();

	    \DB::connection('dbp_users_v2')
	       ->table('highlight')->where('id', '>', $first_highlight_id)->orderBy('id')
	       ->chunk(500, function ($highlights) use($bibles, $colorEquivalents, $books) {
		    foreach($highlights as $highlight) {
			    $bible = $bibles->where('id',substr($highlight->dam_id,0,6))->first();
			    if(!$bible) { Log::driver('seed_errors')->info('bb_nfd_'.substr($highlight->dam_id,0,6)); continue; }
			    $fileset = $bible->filesets->where('set_type_code','text_plain')->first();
			    if(!$fileset) { continue; }

			    $book = $books->where('id_osis', $highlight->book_id)->first();
			    if($book === null) $book = $books->where('protestant_order',$highlight->book_id)->first();
			    if($book === null) {
				    Log::driver('seed_errors')->info('bb_nfd_'.$highlight->book_id);
			    	continue;
			    }

			    $tableExists = \Schema::connection('sophia')->hasTable($fileset->id.'_vpl');
			    if(!$tableExists) {Log::driver('seed_errors')->info('b_nfd'.$fileset->id.'_vpl'); continue; }
		    	$verse = \DB::connection('sophia')
			                ->table($fileset->id.'_vpl')->select(['verse_text','chapter'])->where('book',$book->id_usfx)
			                ->where('chapter',$highlight->chapter_id)->where('verse_start',$highlight->verse_id)->first();
		    	if($verse === null) {
				    Log::driver('seed_errors')->info('bb_nfd_'.$fileset->id);
		    		break;
			    }
		    	$word_count = substr_count($verse->verse_text, ' ') + 1;

				Highlight::create([
					'id'                => $highlight->id,
					'user_id'           => $highlight->user_id,
					'bible_id'          => $bible->id,
					'book_id'           => $book->id,
					'chapter'           => $highlight->chapter_id,
					'verse_start'       => $highlight->verse_id,
					'highlight_start'   => 1,
					'highlighted_words' => $word_count,
					'highlighted_color' => $colorEquivalents[$highlight->color]
				]);
		    }
	    });
    }

	public function seedNotes()
	{
		$first_note = \App\Models\User\Study\Note::orderBy('id','DESC')->first();
		$first_note_id = $first_note->id ?? 0;

		$skippedFilesets = [];
		$filesets = BibleFileset::with('bible')->get();
		$books = Book::select('id_osis','id')->get()->pluck('id','id_osis')->toArray();

		\DB::connection('dbp_users_v2')->table('note')->where('id','>',$first_note_id)->orderBy('id')->chunk(500, function ($notes) use($filesets, $books, $skippedFilesets) {
			foreach($notes as $note) {
				$fileset = $filesets->where('id', $note->dam_id)->first();
				if(!$fileset) $fileset = $filesets->where('id',substr($note->dam_id,0,-4))->first();
				if(!$fileset) $fileset = $filesets->where('id',substr($note->dam_id,0,-2))->first();
				if(!$fileset) {continue;}
				if($fileset->bible->first()) {
					if(!isset($fileset->bible->first()->id)) {continue;}
				} else {
					continue;
				}
				if(!isset($books[$note->book_id])) {continue;}

				Note::create([
					"id"            => $note->id,
					"user_id"       => $note->user_id,
					"bible_id"      => $fileset->bible->first()->id,             //  => "ENGESVO2ET"
					"book_id"       => $books[$note->book_id],          //  => "Ezra"
					"chapter"       => $note->chapter_id,               //  => "1"
					"verse_start"   => $note->verse_id,                 //  => "1"
					"notes"         => encrypt($note->note),             //  => "Note 1"
					'created_at'    => Carbon::createFromTimeString($note->created)->toDateString(),
					'updated_at'    => Carbon::createFromTimeString($note->updated)->toDateString(),
				]);
			}
		});
		file_put_contents(storage_path('/data/dbp2_seed_logs_highlights.json'),json_encode([$skippedFilesets]));
	}

	public function seedBookmarks()
	{
		$first_bookmark = Bookmark::orderBy('id','DESC')->first();
		$first_bookmark_id = $first_bookmark->id ?? 0;

		$filesets = BibleFileset::with('bible')->get();
		$books = Book::select('id_osis','id')->get()->pluck('id','id_osis')->toArray();
		$skippedFilesets = [];
		$skippedBooks = [];

		\DB::connection('dbp_users_v2')->table('bookmark')
			->where('status','current')->where('id','>',$first_bookmark_id)
			->orderBy('id')->chunk(500, function ($bookmarks) use($filesets, $books, $skippedFilesets, $skippedBooks) {

				foreach ($bookmarks as $bookmark) {

					$fileset = $filesets->where('id', $bookmark->dam_id)->first();
					if(!$fileset) $fileset = $filesets->where('id',substr($bookmark->dam_id,0,-4))->first();
					if(!$fileset) $fileset = $filesets->where('id',substr($bookmark->dam_id,0,-2))->first();
					if(!$fileset) {$skippedFilesets[] = $bookmark->dam_id; continue;}
					if(!isset($fileset->bible)) {$skippedFilesets[] = $bookmark->dam_id; continue;}

					if(!isset($books[$bookmark->book_id])) {$skippedBooks[] = $bookmark->book_id;continue;}

					Bookmark::create([
						'id'         => $bookmark->id,
						'user_id'    => $bookmark->user_id,
						'bible_id'   => $fileset->bible->first()->id,
						'book_id'    => $books[$bookmark->book_id],
						'chapter'    => $bookmark->chapter_id,
						'verse_start'=> $bookmark->verse_id,
						'created_at' => Carbon::createFromTimeString($bookmark->created)->toDateString(),
						'updated_at' => Carbon::createFromTimeString($bookmark->updated)->toDateString()
					]);

				}
		});

		file_put_contents(storage_path('/data/dbp2_seed_logs_bookmarks.json'),json_encode(['filesets' => $skippedFilesets,'books'=>$skippedBooks]));
	}

	public function seedProfiles()
	{

	}

	public function seedAccounts()
	{

		$first_account = \App\Models\User\Account::orderBy('id','DESC')->first();
		$first_account_id = $first_account->id ?? 0;

		\DB::connection('dbp_users_v2')->table('user_remote')
			->select(['id','user_id','remote_id','remote_type'])->where('id','>',$first_account_id)
			->orderBy('id')->distinct()->chunk(500, function ($accounts) {

		   	    foreach ($accounts as $account) {

		   	    	if(\App\Models\User\Account::where([
				        'user_id'          => $account->user_id,
				        'provider_id'      => $account->remote_type
			        ])->exists()) {continue;}

				    \App\Models\User\Account::create([
					    'id'               => $account->id,
					    'user_id'          => $account->user_id,
					    'provider_user_id' => $account->remote_id,
					    'provider_id'      => $account->remote_type
				    ]);
		        }

			});

	}


}
