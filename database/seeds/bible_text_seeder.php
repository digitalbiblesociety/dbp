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
		\DB::table('bible_text')->delete();
	    $output['missingBibles'] = [];
	    $output['missingBooks'] = [];
	    $bibleEquivalents = BibleEquivalent::where('site','bible.is')->get()->pluck('bible_id','equivalent_id');
	    $bookCodes = BookCode::where('type','osis')->join('books', 'book_codes.book_id', '=', 'books.id')
	                                               ->select('code as osis','book_id as usfm','books.book_order')
	                                               ->get()->keyBy('osis')->ToArray();
	    if(($handle = fopen(storage_path('/data/dbp2/tlibrary_versetext.csv'), 'r')) !== false)
	    {
		    // get the first row, which contains the column-titles (if necessary)
		    $header = fgetcsv($handle);
		    $output['containsDuplicates'] = [];

		    // loop through the file line-by-line
		    while(($data = fgetcsv($handle)) !== false)
		    {
		    	$bible_id = substr($data[9],0,7);

		    	if(key_exists($bible_id,$output['missingBibles'])) { continue; }

		    	if(!isset($bibleEquivalents[$bible_id])) {
				    $output['missingBibles'][$bible_id] = "missing";
		    		echo "Missing ID: $bible_id\n";
		    		continue;
			    }

			    if(key_exists($bible_id,$output['missingBooks'])) { continue; }
		    	if(!isset($bookCodes[$data[8]])) {$output['missingBooks'][$data[8]] = "Missing"; echo "csv:Missing ".$data[8]."\n"; continue; }

			    $chapter = $data[3];
			    $current_book = $bookCodes[$data[8]];
			    $bible_id = $bibleEquivalents[$bible_id];
			    $verse_start = $data[1];
			    $verse_text = $data[2];

			    if(($chapter > 150) | ($verse_start > 177)) {
				    echo 'Chapter or Verse Out of bounds'.$data[0];
					continue;
		    	}
				$verse_id = $bible_id.'_'.$current_book['book_order'].'_'.$current_book['usfm'].'_'.str_pad($chapter,3,0, STR_PAD_LEFT).'_'.str_pad($verse_start,3,0, STR_PAD_LEFT);
			    if(isset($output['containsDuplicates'][$bible_id])) {continue;}
		    	if(Text::find($verse_id)) {$output['containsDuplicates'][$bible_id] = "duplicate";continue;}
		    	Text::create([
		    		'id'             => $verse_id,
		    		'verse_start'    => $verse_start,
				    'verse_end'      => null,
				    'verse_text'     => $verse_text,
				    'chapter_number' => $chapter,
				    'bible_id'       => $bible_id,
				    'book_id'        => $current_book['usfm'],
			    ]);

			    unset($data);
		    }
		    fclose($handle);
		    file_put_contents(storage_path('/logs/bible_texts_seed_logs.txt'), json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
	    }


    }
}
