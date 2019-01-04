<?php

namespace App\Console\Commands\Wiki;

use Illuminate\Console\Command;
use Sunra\PhpSimple\HtmlDomParser;

class SyncAlphabets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:alphabets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches Alphabets from the Script Source Website';

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

        $string_html = file_get_contents('http://scriptsource.org/cms/scripts/page.php?item_id=script_overview');
        $dom = HtmlDomParser::str_get_html($string_html);
        $contents = $dom->find('.dDataViewTable a');

        foreach ($contents as $content) {
            $skippedUrls = [
                '/cms/scripts/page.php?item_id=script_overview&sort_scripts_current=script_family',
                '/cms/scripts/page.php?item_id=script_overview&sort_scripts_current=script_name',
                '/cms/scripts/page.php?item_id=script_overview&_sc=1&sort_scripts_current=script_name',
                'cms/scripts/page.php?item_id=script_overview&_sc=1&sort_scripts_current=script_family'
            ];

            $alphabetUrl = $content->getAttribute('href');
            if (in_array($alphabetUrl, $skippedUrls)) {
                continue;
            }
            if (strpos($alphabetUrl, 'item_id=script_detail') === false) {
                continue;
            }

            $dom = new Dom;
            $alphabetUrl = "http://scriptsource.org/cms/scripts/".$alphabetUrl;
            $alphabet = @file_get_contents($alphabetUrl);
            $alphabetUrl = parse_url($alphabetUrl);
            parse_str($alphabetUrl['query'], $query);

            if (file_exists(storage_path('data/languages/alphabets/'.$query['key'].'.json'))) {
                continue;
            }
            if (!$alphabet) {
                echo "missing:". $alphabetUrl;
                continue;
            }
            $dom->load($alphabet);

            // Parse and match the features
            $featuresTitlesDom = $dom->find('.scr_features dt');
            $featuresDescriptionsDom = $dom->find('.scr_features dd');
            foreach ($featuresTitlesDom as $key => $featureTitle) {
                $features[$featureTitle->innerHtml] = $featuresDescriptionsDom[$key]->innerHTML;
            }

            // Create the Current Alphabet Array and insert it into a JSON file
            $currentAlphabet['title'] = $dom->find('#page_heading h1')[0]->text;
            $currentAlphabet['title_key'] = $dom->find('#page_heading_alt')[0]->text;
            $currentAlphabet['features'] = $features;
            $currentAlphabet['description'] = $dom->find('.contentBody')[0]->innerHTML;
            file_put_contents(storage_path('data/languages/alphabets/'.$query['key'].'.json'), json_encode($currentAlphabet, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            echo "\n Saved: ".$query['key'];
        }
    }
}
