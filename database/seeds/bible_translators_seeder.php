<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;
class bible_translators_seeder extends Seeder
{
	public function run()
	{
		$seederHelper = new SeederHelper();
		$translators = glob(storage_path().'/data/bibles/translators/*.json');
		foreach($translators as $translator) {
			$translator = json_decode(file_get_contents($translator), true);
			if(!isset($translator['title'])) continue;
			if(!isset($translator['body'])) $translator['body'] = '';
			$slug = $seederHelper->slug($translator['title']);
			DB::table('translators')->insert([
				"id" => $slug,
				"name" => $translator['title'],
				"born" => $translator['born'],
				"died" => $translator['died'],
				"description" => $translator['body']
			]);
		}
	}
}
