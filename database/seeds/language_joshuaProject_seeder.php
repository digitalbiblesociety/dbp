<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;
use App\Models\Language\Language;

class language_joshuaProject_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

	    $seederHelper = new SeederHelper();
	    $languages = $seederHelper->csv_to_array(storage_path('/data/languages/language_joshuaProject.csv'));

	    foreach($languages as $language) {
		    Language::where('iso','=',$language['ROL3'])->update([
		    	'bible_status'            => intval($language['BibleStatus']),
			    'bible_translation_need'  => ($language['TranslationNeedQuestionable'] == "Y") ? true : false,
			    'bible_year'              => (intval($language['BibleYear']) != 0) ? intval($language['BibleYear']) : NULL,
			    'bible_year_newTestament' => (intval($language['NTYear']) != 0) ? intval($language['NTYear']) : NULL,
			    'bible_year_portions'     => (intval($language['PortionsYear']) != 0) ? intval($language['PortionsYear']) : NULL,
		    ]);
	    }

    }
}
