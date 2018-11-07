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
		$seederhelper = new SeederHelper();
		$bibles       = $seederhelper->csv_to_array( storage_path('/data/bibles/bibles.csv') );
		foreach ( $bibles as $key => $bible ) {
			if(!isset($bible['abbr'])) continue;
			if($bible['abbr'] === null) continue;
			$language = Language::where('iso',$bible['iso'])->first();
			if(!$language) {echo "\nMissing: ".$bible['iso'];continue;}

			$alphabet = Alphabet::find($bible['script']);
			if(!$alphabet) {echo "\nMissing: ".$bible['script'];continue;}

			Bible::create([
				'id'          => $bible['abbr'],
				'iso'         => $language->iso,
				'date'        => $bible['date'],
				'scope'       => $bible['size'],
				'script'      => $bible['script'],
				'derived'     => $bible['derived'],
				'copyright'   => $bible['cpy'],
				'in_progress' => $bible['in_progress'],
			]);

			// English Title

			BibleTranslation::create([
				'bible_id'    => $bible['abbr'],
				'iso'         => 'eng',
				'name'        => $bible['eng_title'],
				'description' => $bible['description']
			]);

			// Vernacular Title
			BibleTranslation::create([
				'bible_id'    => $bible['abbr'],
				'iso'         => $bible['iso'],
				'name'        => $bible['vern_title'],
				'description' => $bible['description']
			]);

		}
	}

}