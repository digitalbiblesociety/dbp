<?php

namespace App\Console\Commands\BibleEquivalents;

use App\Models\Bible\Bible;
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
    protected $description = 'Sync with the texts of eBible.org';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $ebible = HtmlDomParser::str_get_html(file_get_contents('http://ebible.org/Scriptures/'));
        $links = $ebible->find('a[href^=details.php?id=]');

        foreach ($links as $link) {
            $ids[] = substr($link->href, 15);
        }

        $ids = collect($ids)->unique();
        $bible_equivalents = BibleEquivalent::where('site', 'ebible.org')->get();

        $organization = Organization::where('slug', 'ebible')->first();

        foreach ($ids as $id) {
            $equivalent_exists = $bible_equivalents->where('equivalent_id', $id)->first();
            if (!$equivalent_exists) {
                BibleEquivalent::create([
                    'bible_id'         => 'XXXXXX',
                    'equivalent_id'    => $id,
                    'organization_id'  => $organization->id,
                    'site'             => 'ebible.org',
                    'type'             => 'website',
                    'constructed_url'  => 'http://ebible.org/find/details.php?id='.$id,
                    'needs_review'     => 1,
                    'suffix'           => ''
                ]);
            }
        }


        $seederHelper = new SeederHelper();
        $bibleEquivalents = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4/export?format=csv&id=1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4&gid=1002399925');

        foreach ($bibleEquivalents as $bible_equivalent) {
            $current_equivalent = BibleEquivalent::where('site', 'ebible.org')->where('equivalent_id', $bible_equivalent['equivalent_id'])->first();

            if ($current_equivalent) {
                $bible_exists = Bible::where('id', $bible_equivalent['bible_id'])->exists();
                if (!$bible_exists) {
                    echo "Missing: ".$bible_equivalent['bible_id'];
                    continue;
                }

                $current_equivalent->bible_id = $bible_equivalent['bible_id'];
                $current_equivalent->save();
            }
        }
    }
}
