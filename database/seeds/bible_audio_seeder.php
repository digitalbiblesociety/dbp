<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFileset;
use \App\Models\Bible\Bible;
use App\Models\Bible\BibleFileTimestamp;
use App\Models\Bible\Book;
use App\Models\Bible\BookCode;
use \App\Models\Bible\BibleEquivalent;
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

	    \DB::table('bible_files')->delete();

	    $seederhelper = new SeederHelper();
	    $chapters = $seederhelper->csv_to_array(storage_path() . "/data/dbp2/tlibrary_chapters_mini.csv");
	    $setsCreated = array();

	    foreach($chapters as $chapter) {
	    	$dam_id = substr($chapter['dam_id'],0,7);
	    	if(!in_array($dam_id,$setsCreated)) {
			    $bibleEquivalent = BibleEquivalent::where('equivalent_id',$dam_id)->first();
			    if(!$bibleEquivalent) {$missing[] = $dam_id;continue;}
	    		$audioSet = new BibleFileset();
			    $audioSet->id = $chapter['dam_id'];
			    $audioSet->variation_id = null;
	    		$audioSet->set_type = 'Audio';
			    $audioSet->name = 'Faith Comes by Hearing';
	    		$audioSet->bible_id = $bibleEquivalent->bible->id;
	    		$audioSet->organization_id = \App\Models\Organization\Organization::where('slug','faith-comes-by-hearing')->first()->id;
			    $audioSet->save();
			    $setsCreated[] = $dam_id;
		    }
	    	// Select Bible using FCBH DAM_ID

	    	// Create the Audio Resource

		    $book = Book::where('id_osis',$chapter['bible_book_definition_osis_code'])->first();
		    if(!$book) {echo "Missing OSIS:". $chapter['bible_book_definition_osis_code'];continue;}
			if(BibleFile::where('file_name',$chapter['schapterfilename'])->first()) {continue;}
			$audioSet->files()->create([
				'file_name'     => $chapter['schapterfilename'],
				'book_id'       => $book->id,
				'chapter_start' => $chapter['iordernumber'],
				'chapter_end'   => null,
				'verse_start'   => 1,
				'verse_end'     => null,
			]);
	    }
	    echo "Missing IDs: ".implode(',',array_unique($missing));

    }
}
