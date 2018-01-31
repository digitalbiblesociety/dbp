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
        $faker = Faker::create();
	    // Fetch Bibles From Sophia
	    $sophia_tables = \DB::connection('sophia')->select('SHOW TABLES');
	    foreach($sophia_tables as $sophia_table) {
	    	if((strpos($sophia_table->Tables_in_sophia, '_vpl') !== false)) $bibles[] = substr($sophia_table->Tables_in_sophia,0,-4);
	    }
	    $bibles = Bible::whereIn('id',$bibles)->get()->pluck('id')->ToArray();
	    //  foreach (range(1,250) as $key) {
		//  	User::create([
		//  		'id'       => $this->generateRandomString(),
		//  		'email'    => $faker->email,
		//  		'name'     => $faker->name,
		//  		'password' => bcrypt($faker->word)
		//  	]);
	    //  }

        foreach (range(1,10000) as $key) {
	        $table = array_random($bibles);
	        $reference = \DB::connection('sophia')->table($table."_vpl")->inRandomOrder()->first();

			if(!isset($reference->canon_order)) continue;
        	Note::create([
        		'user_id'      => User::inRandomOrder()->first()->id,
		        'bible_id'     => $table,
		        'notes'        => encrypt($faker->realText(250,2)),
		        'book_id'      => (strlen($reference->book) == 3) ? $reference->book : Book::where('id_usfx',$reference->book)->first()->id,
				'chapter'      => $reference->chapter,
				'verse_start'  => $reference->verse_start,
				'highlights'   => implode(' | ',array_random(explode(' ',$reference->verse_text),random_int(0,2))) ?? ""
	        ]);


        }
    }

	public function generateRandomString($length = 16)
	{
		return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}

}
