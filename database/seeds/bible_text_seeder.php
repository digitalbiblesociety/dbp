<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\Text;
use App\Models\Bible\BookCode;
use App\Models\Bible\BibleEquivalent;

class bible_text_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

	    $output['missingBibles'] = [];
	    $output['missingBooks'] = [];
	    if(($handle = fopen(storage_path('/data/dbp2/tlibrary_versetext.csv'), 'r')) !== false)
	    {
		    // get the first row, which contains the column-titles (if necessary)
		    $header = fgetcsv($handle);

		    // loop through the file line-by-line
		    while(($data = fgetcsv($handle)) !== false)
		    {
		    	echo "\n csv:Start Line";
		    	$bible_id = substr($data[9],0,7);
		    	if(key_exists($bible_id,$output['missingBibles'])) { continue; }
		    	$bibleEquivalent = BibleEquivalent::where('site','bible.is')->where('equivalent_id',$bible_id)->first();
		    	if(!$bibleEquivalent) {
		    		$output['missingBibles'][$bible_id] = "missing";
		    		continue;
			    }

			    $bookCode = BookCode::where('type','osis')->where('code',$data[8])->first();
		    	if(!$bookCode) {
				    $output['missingBooks'][$data[8]] = "missing";
				    echo "\n csv:Missing ".$data[8];
				    continue;
			    }

		    	Text::create([
		    		'verse_start'    => $data[1],
				    'verse_end'      => $data[1],
				    'verse_text'     => $data[2],
				    'chapter_number' => $data[3],
				    'bible_id'       => $bibleEquivalent->bible->id,
				    'book_id'        => $bookCode->book_id,
			    ]);

			    unset($data);
		    }
		    fclose($handle);
	    }


    }
}
