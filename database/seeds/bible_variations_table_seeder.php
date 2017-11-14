<?php

use Illuminate\Database\Seeder;

use App\Models\Bible\Text;
use App\Models\Bible\BibleVariation;
class bible_variations_table_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$old_testament = ["GEN","EXO","LEV","NUM","DEU","JOS","JDG","RUT","1SA","2SA","1KI","2KI","1CH","2CH","EZR","NEH","EST","JOB","PSA","PRO","ECC","SNG","ISA","JER","LAM","EZK","DAN","HOS","JOL","AMO","OBA","JON","MIC","NAM","HAB","ZEP","HAG","ZEC","MAL"];
    	$new_testament = ["MAT","MRK","LUK","JHN","ACT","ROM","1CO","2CO","GAL","EPH","PHP","COL","1TH","2TH","1TI","2TI","TIT","PHM","HEB","JAS","1PE","2PE","1JN","2JN","3JN","JUD","REV"];

        Text::with('bible')->orderBy('id')->chunk(500, function ($verses) use ($old_testament,$new_testament) {
	        foreach ($verses as $verse) {
		        $variation_id = (in_array($verse->book_id,$new_testament)) ? $verse->bible_id.'N2ET' : $verse->bible_id.'O2ET';
			    BibleVariation::firstOrCreate(['variation_id' => $variation_id,'id' => $verse->bible_id,'script' => $verse->bible->script,'date' => $verse->bible->date]);
			    $verse->bible_variation_id = $variation_id;
			    $verse->save();
	        }
        });

    }
}
