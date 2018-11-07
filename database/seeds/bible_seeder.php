<?php

use Illuminate\Database\Seeder;
use \database\seeds\SeederHelper;
use App\Models\Language\Language;
use App\Models\Language\Alphabet;
use App\Models\Bible\Bible;
class bible_seeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		\DB::connection('dbp')->table('bible_translations')->delete();
		\DB::connection('dbp')->table('bibles')->delete();
		$seederHelper = new SeederHelper();
		$bibles       = $seederHelper->csv_to_array( storage_path('data/bibles.csv') );

		foreach($bibles as $bible) {
			Bible::created($bible);
		}

		$bible_translations = $seederHelper->csv_to_array(storage_path('data/bible_translations.csv'));
		foreach ($bible_translations as $bible_translation) {
			if(!Bible::where('id',$bible_translation['bible_id'])->exists()) {continue;}
			\App\Models\Bible\BibleTranslation::create($bible_translation);
		}


	}

}