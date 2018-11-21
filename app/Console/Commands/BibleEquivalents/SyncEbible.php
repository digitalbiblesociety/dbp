<?php

namespace App\Console\Commands\BibleEquivalents;

use App\Models\Bible\BibleEquivalent;
use App\Models\Organization\Organization;
use Illuminate\Console\Command;
use Sunra\PhpSimple\HtmlDomParser;

class SyncEbible extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ebible';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse the table on eBible.org and compare to the recorded Bible Equivalents';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $html_string = file_get_contents('http://ebible.org/Scriptures/');
        $html = HtmlDomParser::str_get_html($html_string);
        $bibles = $html->find('tr.redist');
        $this->info('Comparing: ' . count($bibles) . ' Bibles');

        foreach ($bibles as $bible) {
            $id = $bible->find('td a')[2]->href;
            $eBible_equivalents[] = explode('?id=', $id)[1];
        }

        $organization = Organization::whereSlug('ebible')->first();
        $recorded_equivalents = BibleEquivalent::whereIn('equivalent_id', $eBible_equivalents)
            ->where('organization_id', $organization->id)->get()->pluck('equivalent_id')->toArray();
        $unrecorded_equivalents = array_diff($eBible_equivalents, $recorded_equivalents);

        foreach ($unrecorded_equivalents as $unrecorded_equivalent) {
            BibleEquivalent::create([
                'bible_id'        => NULL,
                'site'            => 'http://ebible.org/find/details.php?id='.$unrecorded_equivalent,
                'organization_id' => $organization->id,
                'equivalent_id'   => $unrecorded_equivalent,
                'type'            => 'website',
                'suffix'          => NULL
            ]);
        }

    }
}
