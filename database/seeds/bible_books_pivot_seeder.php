<?php

use Illuminate\Database\Seeder;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleBook;
use App\Models\Bible\Book;

class bible_books_pivot_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    \DB::table('bible_books')->delete();

	    $tables = \DB::connection('sophia')->select('SHOW TABLES');
	    foreach($tables as $table) {
	    	$table_id = $table->Tables_in_sophia;
		    $bible_id = substr($table_id,0,-4);

	    	if(strpos($table_id, '_vpl') === false) { continue; }

	    	$bible = Bible::find($bible_id);
	    	if(!$bible) { echo "\n Missing: ". $bible_id;continue;}

	    	$books_reponse = \DB::connection('sophia')->table($table_id)->distinct()->select('book','chapter')->get();
		    $books = [];
			foreach($books_reponse as $book) {
				if(!isset($books[$book->book])) $books[$book->book] = [];
				$books[$book->book][] = $book->chapter;
			}

	    	foreach($books as $book_id => $chapters) {
	    		$book = Book::where('id_usfx',$book_id)->orWhere('id',$book_id)->orWhere('id_osis',$book_id)->first();
				if(!$book) { echo "\nMissing Book_ID: ". $book_id; continue;}
				BibleBook::create([
					'bible_id'      => $bible->id,
					'book_id'       => $book->id,
					'name'          => $book->name,
					'name_short'    => '',
					'chapters'      => implode(',',$chapters)
				]);
		    }

	    }

    }
}
