<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\Audio;
use \App\Models\Bible\Bible;
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

	    $seederhelper = new SeederHelper();
	    \DB::table('bible_audio_references')->delete();
	    /*
    	\DB::table('bible_audio')->delete()
	    $chapters = $seederhelper->csv_to_array(storage_path() . "/data/dbp2/tlibrary_chapters_mini.csv");
	    foreach($chapters as $chapter) {
	    	$dam_id = substr($chapter['dam_id'],0,7);
	    	// Select Bible using FCBH DAM_ID
	    	$bibleEquivalent = BibleEquivalent::where('equivalent_id',$dam_id)->first();
	    	if(!$bibleEquivalent) {$missing[] = $dam_id;continue;}
	    	// Create the Audio Resource
		    $bible = $bibleEquivalent->bible;
		    $bookCode = BookCode::where('type','osis')->where('code',$chapter['bible_book_definition_osis_code'])->first();
		    if(!$bookCode) {echo "Missing OSIS:". $chapter['bible_book_definition_osis_code'];continue;}
			if(Audio::where('filename',$chapter['schapterfilename'])->first()) {continue;}
			$audio = $bible->audio()->create([
				'filename'      => $chapter['schapterfilename'],
				'book_id'       => $bookCode->book_id,
				'chapter_start' => $chapter['iordernumber'],
				'chapter_end'   => null,
				'verse_start'   => $chapter['iordernumber'],
				'verse_end'     => null,
			]);
	    }
	    echo "Missing IDs: ".implode(',',array_unique($missing));
*/

		// Attach Timestamps
		$timestamps = $seederhelper->csv_to_array(storage_path() . "/data/dbp2/tlibrary_audio_timestamps.csv");
		    foreach ($timestamps as $timestamp) {
			    $dam_id = substr($timestamp['dam_id'],0,7);
			    // Select Bible using FCBH DAM_ID
			    $bibleEquivalent = BibleEquivalent::where('equivalent_id',$dam_id)->first();
			    if(!$bibleEquivalent) {$missing[] = $dam_id;continue;}
			    // Create the Audio Resource
			    $bible = $bibleEquivalent->bible;
			    $bookCode = BookCode::where('type','osis')->where('code',$timestamp['osis_code'])->first();
			    if(!$bookCode) {echo "Missing OSIS:". $timestamp['osis_code'];continue;}

			    $audio = Audio::where('book_id',$bookCode->book->id)
			                  ->where('chapter_start',$timestamp['chapter_number'])
			                  ->where('bible_id',$bible->id)->first();
			    if(!$audio) {echo "\n Missing Audio";continue;}
		        \App\Models\Bible\AudioReferences::create([
		        	'audio_id'      => $audio->id,
			        'book_id'       => $bookCode->book_id,
			        'chapter_start' => $timestamp['chapter_number'],
			        'chapter_end'   => null,
			        'verse_start'   => $timestamp['verse_number'],
			        'verse_end'     => null,
			        'timestamp'     => $timestamp['timestamp']
		    ]);
		    }


    }
}
