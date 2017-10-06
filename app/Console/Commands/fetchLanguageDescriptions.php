<?php

namespace App\Console\Commands;

use App\Models\Language\Language;
use database\seeds\SeederHelper;
use Illuminate\Console\Command;

class fetchLanguageDescriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:languageDescriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Language Descriptions';

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

    	// Base Paths
	    $base_iso2 = "es";
	    $base_iso3 = "spa";
	    if(!file_exists(storage_path("data/languages/descriptions/$base_iso3"))) mkdir(storage_path("data/languages/descriptions/$base_iso3"));
	    if(!file_exists(storage_path("data/languages/descriptions/$base_iso3/missing.csv"))) {
		    $missing_file = fopen(storage_path("data/languages/descriptions/$base_iso3/missing.csv"), "w") or die("couldn't create missing.json");
		    fwrite($missing_file, "codes\n");
		    fclose($missing_file);
	    }

    	$base_url = "https://$base_iso2.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles=";
		$missing_url = storage_path("data/languages/descriptions/$base_iso3/missing.csv");
	    $alreadyFetched[] = "eng";

    	// Don't fetch languages already fetched
		$json_files = glob(storage_path("data/languages/descriptions/$base_iso3/*.json"));
		foreach($json_files as $json_file) $alreadyFetched[] = substr($json_file,47,3);
		$csv_parser = new SeederHelper();
		$missing = collect($csv_parser->csv_to_array($missing_url))->pluck('codes');

		// fetch the right languages
    	$languages = Language::whereNotNull('iso')->whereNotIn('iso',$alreadyFetched)->whereNotIn('iso',$missing)->limit(2000)->get();
    	if($languages->count() == 0) $this->info("All Languages Fetched");

        foreach($languages as $language) {
        	$url = $base_url.str_replace(' ','_',trim($language->name));

        	$description = @json_decode(file_get_contents($url."_language"));
        	$description = $this->checkExtract($description);

	        if(!$description) {
	        	$description = @json_decode(file_get_contents($url."_Language"));
		        $description = $this->checkExtract($description);
		        echo "\nAlternative Attempting :". $url."_Language";
	        }

        	if(!$description) {
	        	$description = @json_decode(file_get_contents($url));
		        $description = $this->checkExtract($description);
		        echo "\nAttempting :". $url;
	        }

        	// If Neither option works add it to the missing file
        	if(!$description) {
        		$missing[] = $language->iso;
		        $handle = fopen($missing_url, 'a') or die('Cannot open Missing File:');
		        fwrite($handle, "\n$language->iso");
		        fclose($handle);
        		continue;
		        $this->info("\n Sorrow! For $language->name");
	        }

	        file_put_contents(storage_path("data/languages/descriptions/$base_iso3/$language->iso.json"), json_encode($description));
	        $this->info("\n Success! For $language->name");
        }

    }

    public function checkExtract($json) {
    	if(!isset($json)) return null;
    	$extract = $json->query->pages;
    	if(!isset($extract)) return null;

	    $extract = collect($extract)->first();
	    if(!isset($extract->extract)) return null;
    	return $extract->extract;
    }

}
