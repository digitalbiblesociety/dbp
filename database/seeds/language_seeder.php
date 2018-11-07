<?php

use Illuminate\Database\Seeder;
use App\Models\Language\Language;
use App\Models\Language\LanguageAltName;
class language_seeder extends Seeder
{

	public function run()
	{
		\DB::connection('dbp')->table('language_translations')->delete();
		\DB::connection('dbp')->table('language_dialects')->delete();
		\DB::connection('dbp')->table('language_codes')->delete();
		\DB::connection('dbp')->table('language_classifications')->delete();
		\DB::connection('dbp')->table('languages')->delete();

		$seederHelper = new \database\seeds\SeederHelper();

		$statues = $seederHelper->csv_to_array(storage_path('data/status.csv'));
		foreach($statues as $status) {
			\App\Models\Language\LanguageStatus::create($status);
		}


		$languages = $seederHelper->csv_to_array(storage_path('data/languages.csv'));
		foreach($languages as $language_values) {

			foreach ($language_values as $key => $language_value) {
				if($language_value === 'NULL') $language_value = null;
				$values[$key] = $language_value;
			}

			Language::create($values);
		}

	}

}