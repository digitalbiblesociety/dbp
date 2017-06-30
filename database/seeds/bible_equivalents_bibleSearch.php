<?php

use Illuminate\Database\Seeder;
use App\Models\Organization\Organization;
use App\Models\Bible\BibleEquivalent;
use database\seeds\SeederHelper;
class bible_equivalents_bibleSearch extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!file_exists(storage_path().'/data/bibles/equivalents/bible-search.json')) $this->fetchBibleSearch();
        $bibles = json_decode(file_get_contents(storage_path().'/data/bibles/equivalents/bible-search.json'));
        $seederHelper = new SeederHelper();
        $bibleEquivalents = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4/export?format=csv&id=1pEYc-iYGRdkPpCuzKf4x8AgYJfK4rbTCcrHfRD7TsW4&gid=573804558');
        $seederHelper->seedBibleEquivalents($bibleEquivalents,'american-bible-society','web-app','Bible Search API');
    }

    public function fetchBibleSearch() {
        $token = env('BIBLE_SEARCH_KEY');
        $url = 'https://bibles.org/v2/versions.js';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$token:X");
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        file_put_contents(storage_path().'/data/bibles/equivalents/bible-search.json', json_encode($response, JSON_PRETTY_PRINT | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
