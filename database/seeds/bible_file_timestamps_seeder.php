<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\Book;
use App\Models\Bible\BibleFileTimestamp;
use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleEquivalent;

class bible_file_timestamps_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    \DB::table('bible_file_timestamps')->delete();
    	$seederhelper = new \database\seeds\SeederHelper();
	    $skip = array();
	    // Attach Timestamps
	    $timestamps = $seederhelper->csv_to_array(storage_path("data/dbp2/tlibrary_audio_timestamps.csv"));
	    foreach ($timestamps as $timestamp) {
		    $dam_id = substr($timestamp['dam_id'],0,7);
		    if(in_array($dam_id,$skip)) {continue;}

		    // Select Bible using FCBH DAM_ID
		    $bibleEquivalent = BibleEquivalent::where('equivalent_id',$dam_id)->first();
		    if(!$bibleEquivalent) {$skip[] = $dam_id; continue;}
		    // Create the Audio Resource
		    $bible = $bibleEquivalent->bible;
		    $book = Book::where('id_osis',$timestamp['osis_code'])->first();
		    if(!$book) {echo "Missing OSIS:". $timestamp['osis_code'];continue;}

		    $bibleFile = BibleFile::where([
		    	['book_id',$book->id],
			    ['chapter_start',$timestamp['chapter_number']],
			    ['bible_id',$bible->id],
			    ['file_type','Audio']
		    ])->first();

		    if(!$bibleFile) {
			    $skip[] = $dam_id;
		    	echo "\n Missing Audio $dam_id";
		    	continue;
		    }

		    BibleFileTimestamp::create([
			    'bible_file_id' => $bibleFile->id,
			    'bible_id'      => $bible->id,
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
