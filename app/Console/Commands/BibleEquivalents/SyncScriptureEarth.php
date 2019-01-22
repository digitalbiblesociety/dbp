<?php

namespace App\Console\Commands\BibleEquivalents;

use App\Models\Language\Language;
use Illuminate\Console\Command;
use Sunra\PhpSimple\HtmlDomParser;

class SyncScriptureEarth extends Command
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
        $this->base_path = 'https://www.scriptureearth.org/00i-Scripture_Index.php';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->fetchRows() as $language) {
            foreach ($this->fetchLanguagePage($language) as $language_page_item) {
                $this->buildCurrentLanguage($language_page_item->innertext, $language);
            }
        }
    }

    /**
     * Gets the List of Rows that corespond to languages
     *
     * @return mixed
     */
    private function fetchRows()
    {
        $index = file_get_contents($this->base_path.'?sortby=country&name=all');
        $index = HtmlDomParser::str_get_html($index);
        return $index->find('#CountryTable tr');
    }

    /**
     *
     * Selects and parses individual language pages
     *
     * @param $language
     *
     * @return mixed
     */
    private function fetchLanguagePage($language)
    {
        $rows          = $language->find('td');
        $iso           = $rows[2]->plaintext ?? 'certainly-not-an-iso-code';
        $language      = Language::where('iso', $iso)->first();
        $language_page = file_get_contents($this->base_path.'?sortby=lang&name='.$language->iso);
        $language_page = HtmlDomParser::str_get_html($language_page);

        return $language_page->find('#individualLanguage td');
    }

    /**
     *
     * 
     *
     * @param $link_text
     */
    private function buildCurrentLanguage($link_text, $language)
    {
        $current_language = '';
        preg_match("/LinkedCounter\(\"\w+\",\s+?\"(.*?)\"/", $link_text, $output_array);
        if (isset($output_array[1])) {
            $current_language .= "\n".$output_array[1];
        }
        preg_match("/<a href='(.*?)'/", $link_text, $generic_links);
        if (isset($generic_links[1])) {
            $current_language .= "\n".$generic_links[1];
        }

        preg_match('/<option.*?value="(.*?)">/', $link_text, $select_links);
        if (isset($generic_links[1])) {
            $current_language .= "\n".implode(',',$select_links);
        }

        if (isset($current_language)) {
            file_put_contents(storage_path('data/ScriptureEarth/' . $language->iso . '.json'), $current_language, FILE_APPEND);
        }
    }


}
