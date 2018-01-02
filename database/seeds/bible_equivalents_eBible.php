<?php

use Illuminate\Database\Seeder;
use App\Models\Bible\BibleEquivalent;
class bible_equivalents_eBible extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Input Array
        $bible_equivalents = \DB::connection('sophia')->table('bible_list')->select('translationId','fcbhId')->get()->toArray();
        $organization = \App\Models\Organization\Organization::where('slug','ebible')->first();

        foreach($bible_equivalents as $bible_equivalent) {
            $equivalent = BibleEquivalent::where('equivalent_id',$bible_equivalent->fcbhId)->where('type','sophia')->first();
            if(!isset($equivalent->bible)) continue;
            BibleEquivalent::create([
                'abbr'          => $equivalent->bible->abbr,
                'equivalent_id' => $bible_equivalent->translationId,
                'organization_id' => $organization->id,
                'site'          => "ebible.org",
                'type'          => "eBible",
                'suffix'        => ''
            ]);
        }

    }
}
