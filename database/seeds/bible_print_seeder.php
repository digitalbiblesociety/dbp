<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\BibleEquivalent;

class bible_print_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bibles = json_decode(file_get_contents(storage_path().'/data/bibles_print.json'), true);
        foreach($bibles as $bible) {
            $currentText = BibleEquivalent::where('type','sophia')->where('equivalent_id',$bible['abbr'])->first()->bible;
            \DB::table('bible_print')->insert([
                "bible_id" => $currentText->abbr,
	            "page_number" => $bible['pageCount'],
	            "isbn" => $bible['isbn'],
	            "additional_Cost" => $bible['additionalCost'],
	            "base_cost" => $bible['baseCost'],
                "notes" => ''
            ]);
        }
    }
}
