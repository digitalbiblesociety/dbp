<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\BibleFile;
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

	    \DB::table('bible_file_timestamps')->delete();
	    \DB::table('bible_files')->delete();

	    $seederhelper = new SeederHelper();
	    $chapters = $seederhelper->csv_to_array(storage_path() . "/data/dbp2/tlibrary_chapters_mini.csv");
	    foreach($chapters as $chapter) {
	    	$dam_id = substr($chapter['dam_id'],0,7);
	    	// Select Bible using FCBH DAM_ID
	    	$bibleEquivalent = BibleEquivalent::where('equivalent_id',$dam_id)->first();
	    	if(!$bibleEquivalent) {$missing[] = $dam_id;continue;}
	    	// Create the Audio Resource
		    $bible = $bibleEquivalent->bible;
		    $book = Book::where('id_osis',$chapter['bible_book_definition_osis_code'])->first();
		    if(!$book) {echo "Missing OSIS:". $chapter['bible_book_definition_osis_code'];continue;}
			if(BibleFile::where('file_name',$chapter['schapterfilename'])->first()) {continue;}
			$audio = $bible->files()->create([
				'file_name'     => $chapter['schapterfilename'],
				'file_type'     => 'Audio',
				'book_id'       => $book->id,
				'chapter_start' => $chapter['iordernumber'],
				'chapter_end'   => null,
				'verse_start'   => 1,
				'verse_end'     => null,
			]);
	    }
	    echo "Missing IDs: ".implode(',',array_unique($missing));


		// Attach Timestamps
		$timestamps = $seederhelper->csv_to_array(storage_path() . "/data/dbp2/tlibrary_audio_timestamps.csv");
		    foreach ($timestamps as $timestamp) {
			    $dam_id = substr($timestamp['dam_id'],0,7);

			    // Select Bible using FCBH DAM_ID
			    $bibleEquivalent = BibleEquivalent::where('equivalent_id',$dam_id)->first();
			    if(!$bibleEquivalent) {$missing[] = $dam_id; continue;}
			    // Create the Audio Resource
			    $bible = $bibleEquivalent->bible;
			    $book = Book::where('id_osis',$timestamp['osis_code'])->first();
			    if(!$book) {echo "Missing OSIS:". $timestamp['osis_code'];continue;}

			    $bibleFile = BibleFile::where([['book_id',$book->id],['chapter_start',$timestamp['chapter_number']],['bible_id',$bible->id]])->first();
			    dd($book->id.' '. $timestamp['chapter_number']. ' '. $bible->id);
			    if(!$bibleFile) {echo "\n Missing Audio";continue;}

		        BibleFileTimestamp::create([
		        	'bible_file_id' => $bibleFile->id,
			        'book_id'       => $book->id,
			        'chapter_start' => $timestamp['chapter_number'],
			        'chapter_end'   => null,
			        'verse_start'   => $timestamp['verse_number'],
			        'verse_end'     => null,
			        'timestamp'     => $timestamp['timestamp']
		        ]);
		    }


    }
}
