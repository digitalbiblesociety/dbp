<?php

namespace App\Console\Commands;

use App\Models\Country\Country;
use Illuminate\Console\Command;

class generate_worldFactbook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:world-factbook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create JSON files of the world factbook information';

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
		$countries = Country::with('communications','economy','energy','geography','government','issues','people','ethnicities','religions','transportation')->get();
		foreach ($countries as $country) {
			file_put_contents(storage_path('/data/countries/factbook-parsed/'.$country->id.'.json'),json_encode($country, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_UNICODE));
		}

    }
}
