<?php

namespace App\Console\Commands;

use App\Models\Country\Country;
use App\Models\Language\Language;
use App\Models\Language\LanguageTranslation;
use App\Models\Organization\Organization;
use App\Models\Resource\Resource;
use Illuminate\Console\Command;
use Sunra\PhpSimple\HtmlDomParser;

class fetch_fcbh_apk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:fcbh_apk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the APKs from the table on apk.fcbh.org and imports them into the database.';

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
        // Find and delete current
        $apps = \Cache::remember("fcbh_apk_list", 1600, function () {
            $app = [];
            $html_string = file_get_contents('https://apk.fcbh.org/');
            $html = HtmlDomParser::str_get_html($html_string);
            $table_rows = $html->find('tr');
            foreach ($table_rows as $key => $table_row) {
                foreach ($table_row->find('td') as $table_value) {
                    $app[$key][] = $table_value->innertext();
                }
            }
            return $app;
        });

        // Find and delete current APK references
        $fcbh_id = Organization::where('slug', 'faith-comes-by-hearing')->first()->id;
        Resource::where('organization_id', $fcbh_id)->whereHas('tags', function ($query) {
            $query->where('title', 'scripture-app-builder');
        })->delete();

        /*
         *
         * 0 => "South Sudan"
         * 1 => "Zande"
         * 2 => "<a href="Zande_BSO?">Zande BSO 2011</a>"
         * 3 => "&#160;"
         * 4 => "&#160;"
         * 5 => "&#10003;"
         * 6 => "&#10003;"
         * 7 => "214"
         * 8 => "Africa"
         *
         */


        //
        foreach ($apps as $app) {
            // Country::where('name',$app[0])->first();
            $language = Language::where('name', $app[1])->first();
            if (!$language) {
                $language = LanguageTranslation::where('name', $app[1])->first();
            }
            // if(!$language)
            dd($app);
        }
        return $apps;
    }
}
