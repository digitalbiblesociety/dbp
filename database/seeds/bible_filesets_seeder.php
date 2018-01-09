<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;
use App\Models\Bible\BibleFileset;
class bible_filesets_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $sheet_id = '1GY9aObshuUOTcgrTJSKnWs8pHcO30ul_tZapiL_lCL8';
	    $todoId = '1908304539';
	    $fullDumpId = '1619837455';

	    $seederHelper = new SeederHelper();
	    $bibleEquivalents = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/'.$sheet_id.'/export?format=csv&id='.$sheet_id.'&gid='.$todoId);
	    $allVolumes = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/'.$sheet_id.'/export?format=csv&id='.$sheet_id.'&gid='.$fullDumpId);

	    foreach($bibleEquivalents as $equivalent) {
	    	if($equivalent['equivalent_id']) $bibles[$equivalent['bible_id']][] = $equivalent['equivalent_id'];
	    }
	    foreach($bibles as $bible_id => $equivalents) {
	    	foreach($allVolumes as $volume) {
				// N2 is drama
			    if(in_array($volume['dam_id_root'],$equivalents)) {
			    	if($volume['media_code'] == "ET") {$set_type = 'text_plain';}
				    if($volume['media_code'] == "DV") {
			    		$set_type = 'video';
			    	}
				    if($volume['media_code'] == "DA") {
			    		$drama_type = ($volume['media_code_ext'] == 2) ? '_drama' : '';
			    		$set_type = 'audio'.$drama_type;
			    	}

				    $bible_exists = \App\Models\Bible\Bible::where('id',$bible_id)->first();
				    if(!$bible_exists) {echo "\n Missing:".$bible_id;continue;}
				    switch($volume['collection_code']) {
					    case "NT": {$size_name = "New Testament";break;}
					    case "OT": {$size_name = "Old Testament";break;}
					    default: $size_name = "";
				    }
				    BibleFileset::create([
					    'id'              => $volume['dam_id'],
					    'bible_id'        => $bible_id,
					    'name'            => 'Faith Comes by Hearing',
					    'set_type'        => $set_type,
					    'organization_id' => 9,
					    'size_code'       => $volume['collection_code'],
					    'size_name'       => $size_name
				    ]);
			    }
		    }
	    }

    }
}
