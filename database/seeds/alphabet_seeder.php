<?php

use Illuminate\Database\Seeder;
use \database\seeds\SeederHelper;
use App\Models\Language\Alphabet;
use App\Models\Language\AlphabetNumber;

class alphabet_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    \DB::connection('dbp')->table('alphabet_fonts')->delete();
	    \DB::connection('dbp')->table('alphabet_language')->delete();
	    \DB::connection('dbp')->table('alphabets')->delete();
        $seederhelper = new SeederHelper();
        $sheet_id = '1GoBzI4VRP2bQW8LMdv0eJrEICSSAJ-k_8yix3XjBf8w';
        //$alphabets = $seederhelper->csv_to_array("https://docs.google.com/spreadsheets/d/$sheet_id/export?format=csv&id=$sheet_id");
        //foreach ($alphabets as $alphabet) Alphabet::create($alphabet);

	    $alphabets_paths = glob(storage_path('data/languages/alphabets/*.json'));
	    foreach ($alphabets_paths as $alphabets_path) {
		    $alphabet = json_decode(file_get_contents($alphabets_path),true);
		    if(strlen($alphabet["features"]["Direction"]) > 3) {
		    	if(strpos($alphabet["features"]["Direction"], 'RTL') !== false) {$direction = "rtl";}
			    if(strpos($alphabet["features"]["Direction"], 'LTR') !== false) {$direction = "ltr";}
		        $direction_notes = $alphabet["features"]["Direction"];
		    } else {
			    $direction = strtolower($alphabet["features"]["Direction"]);
			    $direction_notes = null;
		    }
		    preg_match("'<a href=\".*?\">(.*?)</a>'si", $alphabet["features"]["Type"], $match);
		    $type = (isset($match[1])) ? $match[1] : "alphabet";

	    	Alphabet::create(
	    		[
                'script'              => basename($alphabets_path,'.json'),
                'name'                => $alphabet["title"],
				'requires_font'       => 0,
				'family'              => $alphabet["features"]["Family"],
				'type'                => $type,
				'white_space'         => $alphabet["features"]["White Space"],
				'complex_positioning' => $alphabet["features"]["Complex Positioning"],
				'open_type_tag'       => $alphabet["features"]["OpenType Tag"],
				'diacritics'          => $this->checkBoolean($alphabet["features"]["Diacritics"]),
				'contextual_forms'    => $this->checkBoolean($alphabet["features"]["Contextual Forms"]),
				'reordering'          => $this->checkBoolean($alphabet["features"]["Reordering"]),
				'case'                => $this->checkBoolean($alphabet["features"]["Ligatures"]),
				'split_graphs'        => $this->checkBoolean($alphabet["features"]["Split Graphs"]),
				'status'              => $alphabet["features"]["Status"],
				'baseline'            => $alphabet["features"]["Baseline"],
				'ligatures'           => $alphabet["features"]["Ligatures"],
				'direction'           => $direction,
				'direction_notes'     => $direction_notes,
				'sample'              => null,
				'sample_img'          => null,
				'description'         => $alphabet["description"],
		    ]);
	    }

	    $alphabets = $seederhelper->csv_to_array("https://docs.google.com/spreadsheets/d/$sheet_id/export?format=csv&id=$sheet_id&gid=0");
	    foreach ($alphabets as $alphabet) {
	    	$alphabetExists = Alphabet::find($alphabet['script']);
	    	if(!$alphabetExists) {
	    		Alphabet::create($alphabet);
	    	} else {
	    		$alphabetExists->sample = $alphabet['sample'];
			    $alphabetExists->sample = $alphabet['sample_img'];
			    $alphabetExists->save();
		    }
	    }

    }

    public function checkBoolean($boolean) {
    	if(!isset($boolean)) return null;
    	if($boolean == "yes") return 1;
	    if($boolean == "no") return 0;
    }

}
