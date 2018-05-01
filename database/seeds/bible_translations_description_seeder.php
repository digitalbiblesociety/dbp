<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;
use \App\Models\Bible\BibleTranslation;
class bible_translations_description_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $seederhelper = new SeederHelper();
	    $bible_translations = $seederhelper->csv_to_array(storage_path('data/bibles/bible_translations.csv'));
	    foreach ($bible_translations as $key => $translation) {
		    $bible_translations[$key]["bible_id"] = "";

	    	$current_bible_translation = BibleTranslation::where('name',$translation["Bible Name"])->first();
	    	if(!$current_bible_translation) {
	    		echo "\n".$translation["Bible Name"];
	    		continue;
		    }
	    	if($translation["Description"] != '') {
			    //$current_bible_translation->description = $translation["Description"];
			    //$current_bible_translation->save();
			    $bible_translations[$key]["bible_id"] = $current_bible_translation->bible_id;
		    }
	    }
	    file_put_contents(storage_path('data/bible_translations.json'), json_encode($bible_translations));

    }
}
