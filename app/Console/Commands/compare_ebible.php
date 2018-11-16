<?php

namespace App\Console\Commands;

use App\Models\Bible\BibleEquivalent;
use Illuminate\Console\Command;
use Sunra\PhpSimple\HtmlDomParser;

class compare_ebible extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compare:ebible';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $html_string = file_get_contents("http://ebible.org/Scriptures/");
        $html = HtmlDomParser::str_get_html($html_string);
        $bibles = $html->find("tr.redist");
        $this->info("Comparing: ".count($bibles). " Bibles");

        foreach ($bibles as $bible) {
            $id = $bible->find("td a")[2]->href;
            $eBible_equivalents[] = explode("?id=", $id)[1];
        }

        $recorded_equivalents = BibleEquivalent::whereIn('equivalent_id', $eBible_equivalents)->where('site', 'ebible.org')->get()->pluck('equivalent_id')->toArray();
        
        $unrecorded_equivalents = array_diff($eBible_equivalents, $recorded_equivalents);

        dd($unrecorded_equivalents);
    }
}
