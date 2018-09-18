<?php

use Illuminate\Database\Seeder;
use App\Models\User\User;


use App\Models\User\Study\HighlightColor;
use App\Models\User\Study\Highlight;
use App\Models\User\Study\Note;
use App\Models\User\Study\Bookmark;
class users_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$this->seedUsers();
    }


    public function seedUsers()
    {
	    $first_user = \App\Models\User\User::orderBy('id','DESC')->first();
	    $first_user_id = $first_user->id ?? 0;

	    \DB::connection('dbp_users_v2')->table('user')->where('id','>',$first_user_id)->orderBy('id')->chunk(500, function ($users) {
		    foreach($users as $user) {
			    $currentUser = [
				    'id'                => $user->id,
				    'name'              => $user->username ?? $user->email,
				    'password'          => 's_'.bcrypt($user->password),
				    'first_name'        => $user->first_name,
				    'last_name'         => $user->last_name,
				    'token'             => str_random(24),
				    'email'             => $user->email,
				    'activated'         => intval($user->confirmed),
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
	    $filesets = BibleFileset::all();
	    $skippedFilesets = [];

	    \DB::connection('dbp_users_v2')
	       ->table('highlight')->where('id','>',$first_highlight_id)->orderBy('id')->where('status','current')
	       ->chunk(500, function ($highlights) use($filesets, $skippedFilesets, $colorEquivalents) {
		    foreach($highlights as $highlight) {
		    	if(in_array($highlight->dam_id, $skippedFilesets)) { continue; }
			    $fileset = $filesets->where('id', $highlight->dam_id)->orWhere('id',substr($highlight->dam_id,0,-4))->orWhere('id',substr($highlight->dam_id,0,-2))->first();
		    	if(!$fileset) {$skippedFilesets[] = $highlight->dam_id;continue;}

		    	$verse = \DB::connection('sophia')
			                ->table($fileset->id.'_vpl')->select('verse_text')
			                ->where('chapter',$highlight->chapter)->where('verse',$highlight->verse_start)->first();
		    	$word_count = count(explode(' ',$verse->verse_text));

				Highlight::create([
					'id'                => $highlight->id,
					'user_id'           => $highlight->user_id,
					'fileset_id'        => $fileset->id,
					'highlight_start'   => 1,
					'highlighted_words' => $word_count,
					'highlighted_color' => $colorEquivalents[$highlight->color]
				]);
		    }
	    });

	    file_put_contents(storage_path('/data/dbp2_seed_logs_highlights.json'),json_encode($skippedFilesets));
    }

	public function seedNotes()
	{
		$first_note = \App\Models\User\Study\Note::orderBy('id','DESC')->first();
		$first_note_id = $first_note->id ?? 0;

		$filesets = BibleFileset::with('bible')->get();
		$books = Book::select('id_osis','id')->get()->pluck('id','id_osis')->toArray();

		\DB::connection('dbp_users_v2')->table('note')->where('id','>',$first_note_id)->orderBy('id')->chunk(500, function ($notes) use($filesets, $books) {
			foreach($notes as $note) {
				$fileset = $filesets->where('id', $note->dam_id)->orWhere('id',substr($note->dam_id,0,-4))->orWhere('id',substr($note->dam_id,0,-2))->first();
				if(!$fileset) {$skippedFilesets[] = $note->dam_id; continue;}

				Note::create([
					"user_id"       => $note->user_id,
					"bible_id"      => $fileset->bible->id,             //  => "ENGESVO2ET"
					"book_id"       => $books[$note->book_id],          //  => "Ezra"
					"chapter"       => $note->chapter_id,               //  => "1"
					"verse_start"   => $note->verse_id,                 //  => "1"
					"notes"         => bcrypt($note->note),             //  => "Note 1"
					'created_at'    => Carbon::createFromTimeString($note->created)->toDateString(),
					'updated_at'    => Carbon::createFromTimeString($note->updated)->toDateString(),
				]);
			}
		});
	}

	public function seedBookmarks()
	{
		$first_bookmark = Bookmark::orderBy('id','DESC')->first();
		$first_bookmark_id = $first_bookmark->id ?? 0;

		$filesets = BibleFileset::with('bible')->get();
		$books = Book::select('id_osis','id')->get()->pluck('id','id_osis')->toArray();

		\DB::connection('dbp_users_v2')->table('bookmark')
			->where('status','current')->where('id','>',$first_bookmark_id)
			->orderBy('id')->chunk(500, function ($bookmarks) use($filesets, $books) {

				foreach ($bookmarks as $bookmark) {
					Bookmark::create([
						'user_id'    => $bookmark->user_id,
						'bible_id'   => $bookmark->bible_id,
						'book_id'    => $books[$bookmark->book_id],
						'chapter_id' => $bookmark->chapter_id,
						'verse_id'   => $bookmark->verse_start,
						'created_at' => Carbon::createFromTimeString($bookmark->created)->toDateString(),
						'updated_at' => Carbon::createFromTimeString($bookmark->updated)->toDateString()
					]);
				}
		});
	}

	public function seedProfiles()
	{

	}

	public function seedAccounts()
	{

		\DB::connection('dbp_users_v2')->table('remote')
		   ->where('status','current')->where('id','>',$first_account_id)
		   ->orderBy('id')->chunk(500, function ($bookmarks) use($filesets, $books) {

				foreach ($bookmarks as $bookmark) {
					Bookmark::create([
						'user_id'    => $bookmark->user_id,
						'bible_id'   => $bookmark->bible_id,
						'book_id'    => $books[$bookmark->book_id],
						'chapter_id' => $bookmark->chapter_id,
						'verse_id'   => $bookmark->verse_start,
						'created_at' => Carbon::createFromTimeString($bookmark->created)->toDateString(),
						'updated_at' => Carbon::createFromTimeString($bookmark->updated)->toDateString()
					]);
				}
			});

	}


}
