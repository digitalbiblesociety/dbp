<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\BibleEquivalent;
use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleFile;
use App\Models\Bible\Book;
use App\Models\Bible\BookCode;
use database\seeds\SeederHelper;

class bible_audio_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    ini_set('memory_limit', '3000M');

	    \DB::table('bible_fileset_types')->delete();
	    \DB::table('bible_fileset_sizes')->delete();
	    \DB::table('bible_file_timestamps')->delete();
	    \DB::table('bible_files')->delete();

	    $bucket_exists = \App\Models\Organization\Asset::find('dbp_dev');
	    if(!$bucket_exists) \App\Models\Organization\Asset::create(['id' =>'dbp_dev', 'organization_id' => 9]);

	    \App\Models\Bible\BibleFilesetSize::create([
	    	'id'            => 1,
	    	'set_size_code' => 'OT',
		    'name'          => 'Old Testament'
	    ]);

	    \App\Models\Bible\BibleFilesetSize::create([
		    'id'            => 2,
		    'set_size_code' => 'NT',
		    'name'          => 'New Testament'
	    ]);

	    \App\Models\Bible\BibleFilesetType::create([
	    	'id'            => 1,
	    	'set_type_code' => 'audio_drama',
		    'name'          => 'Dramatized Audio'
	    ]);

	    \App\Models\Bible\BibleFilesetType::create([
		    'id'            => 2,
		    'set_type_code' => 'audio',
		    'name'          => 'Audio'
	    ]);

	    \App\Models\Bible\BibleFilesetType::create([
		    'id'            => 3,
		    'set_type_code' => 'text',
		    'name'          => 'Plain Text'
	    ]);

	    $seederhelper = new SeederHelper();
	    $chapters = $seederhelper->csv_to_array(storage_path() . "/data/dbp3/audio.csv");
	    $setsCreated = array();
	    $fcbh_id = \App\Models\Organization\Organization::where('slug','faith-comes-by-hearing')->first()->id;
	    foreach($chapters as $chapter) {
		    if(!isset($chapter['audio_path'])) { continue; }
	    	$dam_id = $chapter['dam_id'];
		    $testament = (substr($dam_id,-4,1) == 'N') ? "NT" : "OT";
		    //$testament_code = (substr($dam_id,-4,1) == 'N') ? 1 : 2;

		    if(substr($dam_id,-2,1) == 'D') {
			    $type = (substr($dam_id,-3,1) == 2) ? 1 : 2;
		    } else {
		    	$type = 3;
		    }

	    	if(!in_array($dam_id,$setsCreated)) {
			    $bibleEquivalent = BibleEquivalent::where('equivalent_id',$dam_id)->first();
			    if(!$bibleEquivalent) {$missing[] = $dam_id;continue;}

	    		$audioSet = BibleFileset::create([
	    			'hash_id'         => substr(md5($chapter['dam_id'].$type."dbp_dev"), 0, 12),
				    'id'              => $chapter['dam_id'],
					'set_type_code'   => \App\Models\Bible\BibleFilesetType::find($type)->set_type_code,
				    'asset_id'       => "dbp_dev",
				    'set_size_code'   => $testament,
			    ]);
			    $audioSet->save();
			    $audioSet->connections()->create([
				    'bible_id'      => $bibleEquivalent->bible->id,
			    ]);
			    $setsCreated[] = $dam_id;
		    }
	    	// Select Bible using FCBH DAM_ID

	    	// Create the Audio Resource

		    $book = Book::where('id', $chapter['book_code'])->first();
		    if(!$book) {echo "Missing USFM:". $chapter['book_code'];continue;}
			if(BibleFile::where('file_name',$chapter['audio_path'])->orWhere([
				['hash_id',       '=', $audioSet->hash_id],
				['book_id',       '=', $book->id],
				['chapter_start', '=', $chapter['number']],
				['verse_start',   '=', 1]
			])->first()) {continue;}
			$audioSet->files()->create([
				'file_name'     => $chapter['audio_path'],
				'book_id'       => $book->id,
				'chapter_start' => $chapter['number'],
				'chapter_end'   => null,
				'verse_start'   => 1,
				'verse_end'     => null,
			]);
	    }
	    echo "Missing IDs: ".implode(',',array_unique($missing));

    }
}
