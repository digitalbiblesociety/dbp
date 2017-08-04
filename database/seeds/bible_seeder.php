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
		$seederhelper = new SeederHelper();
		$bibles       = $seederhelper->csv_to_array( storage_path() . "/data/bibles/bibles.csv" );
		foreach ( $bibles as $key => $bible ) {
			$current_bible = new Bible();
			$current_bible->create([
				'id'          => $bible['abbr'],
				'iso'         => $bible['iso'],
				'date'        => $bible['date'],
				'scope'       => $bible['size'],
				'script'      => $bible['script'],
				'derived'     => $bible['derived'],
				'copyright'   => $bible['cpy'],
				'in_progress' => $bible['in_progress'],
			]);

			// English Title
			$current_bible->translations()->create([
				"iso"         => "eng",
				"vernacular"  => (($bible["iso"] == "eng") AND isset($bible["eng_title"]) ? 1 : 0),
				"name"        => $bible["eng_title"],
				"bible_id"    => $bible["abbr"],
				"description" => $bible["description"]
			]);

			// Vernacular Title
			$current_bible->translations()->create([
				"iso"         => $bible["iso"],
				"vernacular"  => ($bible["iso"] != "eng") ? 1 : 0,
				"name"        => $bible["eng_title"],
				"bible_id"    => $bible["abbr"],
				"description" => $bible["description"]
			]);

		}
	}

}