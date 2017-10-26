<?php

namespace App\Console\Commands;

use App\Models\Bible\Bible;
use App\Models\Country\Country;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use Illuminate\Console\Command;

class s3_update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 's3:update {section} {driver?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the JSONs in s3 to match the database';

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
    	if(!$this->driver) $this->driver = 'local';
		switch($this->argument('section')) {
			case "languages": { $this->handleLanguages(); }
			case "countries": { $this->handleCountries(); }
			case "alphabets": { $this->handleAlphabets(); }
			case "bibles":    { $this->handleLanguages(); }
		}
    }

    public function handleLanguages()
    {
    	$languages = json_encode(Language::select('iso','name','glotto_id')->get());
	    Storage::disk($this->driver)->put('languages.json', $languages);
    }

    public function handleCountries()
    {
    	$countries = json_encode(Country::select('id','name')->get());
	    Storage::disk($this->driver)->put('countries.json', $countries);
    }

    public function handleAlphabets()
    {
	    $alphabets = json_encode(Alphabet::select('script','name')->get());
	    Storage::disk($this->driver)->put('alphabets.json', $alphabets);

	    foreach($alphabets as $alphabet) {
		    $alphabet = Alphabet::find($bible->id);
		    Storage::disk($this->driver)->put("/bibles/$bible->id.json", json_encode($bible));
	    }

    }

	public function handleBibles()
	{
		$bibles = json_encode(Bible::select('iso','id')->get());
		Storage::disk($this->driver)->put('bibles.json', $bibles);
		foreach($bibles as $bible) {
			$bible = Bible::with('translations', 'language.parent', 'alphabet.fonts', 'equivalents', 'filesets', 'organizations', 'links')->find($bible->id);
			Storage::disk($this->driver)->put("/bibles/$bible->id.json", json_encode($bible));
		}
	}

}
