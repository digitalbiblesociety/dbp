<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;
use App\Models\Language\Language;
use App\Models\Bible\Book;
use App\Models\Bible\BookTranslation;
class book_translations_seeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

		$seederHelper = new SeederHelper();
		$data_url = 'https://docs.google.com/spreadsheets/d/1lKJGinIrK_nNBMgaw6iPdL_7s2N6dcP0HQ1fpcju-Ak/export?format=csv&id=1lKJGinIrK_nNBMgaw6iPdL_7s2N6dcP0HQ1fpcju-Ak';

		$bookTranslations = $seederHelper->csv_to_array($data_url.'&gid=1922115575');
		foreach($bookTranslations as $translation) {
			$language = Language::where('iso',$translation['iso'])->first();
			if($language === null) { continue; }
			$book = Book::where('id_usfx',$translation['book_id'])->first();
			BookTranslation::create([
				'language_id' => $language->id,
				'book_id' => $book->id,
				'name' => $translation['name'],
				'name_long' => $translation['name_long'],
				'name_short' => $translation['name_short'],
				'name_abbreviation' => $translation['name_abbreviation'],
			]);
		}
	}
}
