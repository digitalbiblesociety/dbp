<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;
use App\Models\Bible\BookTranslation;
use App\Models\Bible\Book;
use App\Models\Language\Language;
class bible_books_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    /**
     * Run the database seeds.
     *
     * @return void
     */
        $seederhelper = new SeederHelper();
        $canon = $seederhelper->csv_to_array(storage_path() . "/data/bibles/books.csv");
	    Book::insert($canon);

	    $bookTranslations = $seederhelper->csv_to_array(storage_path() . "/data/bibles/book_translations.csv");
	    foreach($bookTranslations as $translation) {
	    	$language = Language::where('iso',$translation['iso'])->first();
	    	if(!isset($language)) {
	    		echo $translation['iso'];
	    		continue;
		    }
	    	$newTranslation = new BookTranslation();
	    	$newTranslation->glotto = $language['id'];
	    	$newTranslation->book_id = $translation['book_id'];
		    $newTranslation->name = $translation['name'];
		    $newTranslation->description = $translation['description'];
		    $newTranslation->save();
	    }
	    /*
				$seederHelper = new SeederHelper();
				$bibleEquivalents = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4/export?format=csv&id=1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4&gid=2021834900');



				foreach($bibleEquivalents as $equivalent) {
					if(!file_exists(storage_path().'/data/bibles/DBL/'.$equivalent["equivalent_id"].'.xml')) {

							$url = 'http://app.thedigitalbiblelibrary.org/entry/revision_log_xml?email=jon%40dbs.org&id='. $equivalent["equivalent_id"] .'&key=38479d6d0a9eee3721a89dba54f2a285';

							$handle = curl_init($url);
							curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

							$response = curl_exec($handle);

							$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
							if($httpCode == 403) {
								echo "\n403: ".$equivalent["abbr"];
								continue;
							}

							if($httpCode == 404) {
								echo "\n404 ".$equivalent["abbr"];
								continue;
							}

							$file = file_get_contents($url);
							file_put_contents(storage_path().'/data/bibles/DBL/'.$equivalent["equivalent_id"].'.xml');

							curl_close($handle);

					}

				}
				dd("finished");

        foreach($bibleEquivalents as $equivalent) {
            $errors = ['4f684f4a0bee8478','79a9589a3ed2d2c2','99c8724862823aaa','802e5ae718c58e71','cd59a9cd8c596e6b','bcf390214c4db08f','65eb1779153f56e0','62cd602845e324dc','a6d3e3fdb5e17efe','dfc6da1000025af7','7098ea3143634317'];
            if(in_array($equivalent['equivalent_id'],$errors)) {
                echo "\n Skipping ".$equivalent['equivalent_id'];
                continue;
            }
            $dbl = simplexml_load_file(storage_path().'/data/DBL/'.$equivalent["equivalent_id"].'.xml');
            if(!isset($dbl->bookNames->book)) {
                echo "\nID not found:".$equivalent["abbr"];
                continue;
            }
            foreach($dbl->bookNames->book as $value) {
                if(strlen($value->abbr) == 3) {
                    $book = \DB::table('books')->where('usfm',strtoupper($value->abbr))->first();
                } elseif(strlen($value->abbr) == 2) {
                    $book = \DB::table('books')->where('usfx',strtoupper($value->abbr))->first();
                }
                if(!isset($book)) {
                    echo "book not found for ".$value->abbr;
                    continue;
                }
                \DB::table('bible_book')->insert([
                   'abbr'       => $equivalent["abbr"],
                   'book_id'    => $book->usfx,
                   'name'       => $value->long,
                   'name_short' => $value->short
                ]);
            }
        }
	    */
    }
}
