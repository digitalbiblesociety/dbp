<?php

namespace App\Console\Commands;

use App\Models\Language\Language;
use Illuminate\Console\Command;
use Sunra\PhpSimple\HtmlDomParser;

class sync_scriptureEarth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:scriptureEarth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $index = file_get_contents('https://www.scriptureearth.org/00i-Scripture_Index.php?sortby=country&name=all');
        $index = HtmlDomParser::str_get_html($index);
        $language_rows = $index->find('#CountryTable tr');


        foreach ($language_rows as $language_row) {
            $items = $language_row->find('td');
            $language = Language::where('iso', $items[2]->plaintext)->first();
            if (!isset($items[2]->plaintext)) {
                echo "Missing: ".$items[2]->plaintext;
                continue;
            }
            if (!isset($language)) {
                echo "Not Found: ".$items[2]->plaintext;
                continue;
            }
            $language_page = file_get_contents('https://www.scriptureearth.org/00i-Scripture_Index.php?sortby=lang&name='.$items[2]->plaintext.'&ROD_Code=00000&Variant_Code=');
            $language_page = HtmlDomParser::str_get_html($language_page);
            $language_page_items = $language_page->find('#individualLanguage td');

            foreach ($language_page_items as $language_page_item) {
                $current_language = false;
                $link_text = $language_page_item->innertext;
                if (str_contains($link_text, ['JESUS Film','JESUSFilm','Magdalena','Check SIL.org',"href='#'"])) {
                    continue;
                }
                if (!str_contains($link_text, 'href')) {
                    continue;
                }

                preg_match("/LinkedCounter\(\"\w+\",\s+?\"(.*?)\"/", $link_text, $output_array);
                if (isset($output_array[1])) {
                    $current_language = "\n".$output_array[1];
                }
                preg_match("/<a href='(.*?)'/", $link_text, $generic_links);
                if (isset($generic_links[1])) {
                    $current_language = "\n".$generic_links[1];
                }

                preg_match("/<option.*?value=\"(.*?)\">/", $link_text, $select_links);
                if (isset($generic_links[1])) {
                    $current_language = "\n".implode(',', $select_links);
                }

                if ($current_language) {
                    file_put_contents(storage_path("data/ScriptureEarth/". $items[2]->plaintext .'.json'), $current_language, FILE_APPEND);
                }
            }
        }
    }
}
