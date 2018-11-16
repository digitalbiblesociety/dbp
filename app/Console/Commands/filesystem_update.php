<?php

namespace App\Console\Commands;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleFileset;
use App\Models\Country\Country;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class filesystem_update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filesystem:update {section=all} {driver=local} {organization=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the JSONs in s3 to match the database';
    protected $driver;
    protected $section;

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
        $this->driver = $this->argument('driver');
        $this->section = $this->argument('section');
        $this->organization = $this->argument('organization');

        switch ($this->section) {
            case "languages": {
                $this->handleLanguages();
                break;
            }
            case "countries": {
                $this->handleCountries();
                break;
            }
            case "alphabets": {
                $this->handleAlphabets();
                break;
            }
            case "bibles":    {
                $this->handleBibles();
                break;
            }
            case "all":       {
                $this->handleLanguages();
                $this->handleCountries();
                $this->handleAlphabets();
                $this->handleBibles();
            }
        }
    }

    public function handleLanguages()
    {
        $this->info("\n Languages Section Started");
        $languages = Language::select('iso', 'name', 'glotto_id', 'id')->get();
        $bar       = $this->output->createProgressBar(count($languages));
        Storage::disk($this->driver)->put('languages.json', json_encode($languages));

        foreach ($languages as $language) {
            $language = Language::with('translations', 'countries', 'region', 'alphabets.fonts', 'bibles')->find($language->id);
            Storage::disk($this->driver)->put("/languages/$language->id.json", json_encode($language, JSON_PRETTY_PRINT));
            $bar->advance();
        }
        $bar->finish();
        $this->info("\n Language Section Finished");
    }

    public function handleCountries()
    {
        $this->info("\n Countries Section Started");
        $countries = json_encode(Country::select('id', 'name')->get());
        $bar       = $this->output->createProgressBar(count($countries));
        Storage::disk($this->driver)->put('countries.json', $countries);

        $countries = Country::with('languages')->get();
        foreach ($countries as $country) {
            Storage::disk($this->driver)->put("/alphabets/$country->id.json", json_encode($country, JSON_PRETTY_PRINT));
            $bar->advance();
        }
        $bar->finish();
        $this->info("\n Countries Section Finished");
    }

    public function handleAlphabets()
    {
        $this->info("\n Alphabets Section Started");
        $alphabets = Alphabet::select('script', 'name')->get();
        $bar    = $this->output->createProgressBar(count($alphabets));
        Storage::disk($this->driver)->put('alphabets.json', json_encode($alphabets, JSON_PRETTY_PRINT));
        $alphabets = Alphabet::with('fonts', 'languages', 'bibles')->get();
        foreach ($alphabets as $alphabet) {
            Storage::disk($this->driver)->put("/alphabets/$alphabet->id.json", json_encode($alphabet, JSON_PRETTY_PRINT));
            $bar->advance();
        }
        $bar->finish();
        $this->info("\n Alphabets Section Finished");
    }

    public function handleBibles()
    {
        $this->info("\n Bibles Section Started");

        // Check the organization Argument and if it exists Filter the Bibles by that organization's filesets
        if ($this->organization != "all") {
            $organization = Organization::where('slug', $this->organization)->first();
            if (!$organization) {
                return $this->error("Organization slug not found for".$this->organization);
            }
            $filesets = BibleFileSet::with('bible')->where('organization_id', $organization->id)->get();
            $bibles = $filesets->pluck('bible.iso', 'bible.id');
        } else {
            // Otherwise just get all Bibles
            $bibles = Bible::select('iso', 'id')->get()->pluck('iso', 'id');
        }

        // Initialize Progress Bar
        $bar = $this->output->createProgressBar(count($bibles));
        Storage::disk($this->driver)->put('bibles.json', json_encode($bibles, JSON_PRETTY_PRINT));

        // Save Individual Bible Files
        foreach ($bibles as $id => $iso) {
            $bible = Bible::with('translations', 'language.parent', 'alphabet.fonts', 'equivalents', 'filesets.files', 'organizations', 'links')->find($id);
            Storage::disk($this->driver)->put("/bibles/$id/info.json", json_encode($bible, JSON_PRETTY_PRINT));
            foreach ($bible->filesets as $fileset) {
                Storage::disk($this->driver)->put("/bibles/$id/$fileset->id.json", json_encode($fileset, JSON_PRETTY_PRINT));
            }
            $bar->advance();
        }
        $bar->finish();
        $this->info("\n Bibles Section Finished");
    }
}
