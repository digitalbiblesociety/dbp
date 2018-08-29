<?php

namespace App\Console\Commands;

use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationRelationship;
use Illuminate\Console\Command;

class dbl_sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbl:sync {aspect}';

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
	    if($command = 'sync') $this->organizations();

    }

    private function organizations()
    {
		// Fetch Local Organizations
	    $organizations = Organization::with('relationships')->get();
	    $dbl = $organizations->where('slug','digital-bible-library')->first();

    	// Fetch Organizations from the DBL
	    $dbl_organization_entries = Cache::remember('dbl_organizations', 1600, function () {
		    return json_decode(file_get_contents('https://thedigitalbiblelibrary.org/api/orgs'));
	    });

	    // Sync them
	    foreach ($dbl_organization_entries as $dbl_organization_entry) {

		    //OrganizationRelationship::create([
		    //	''
		    //]);
	    }

    }

}
