<?php

namespace App\Console\Commands;

use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationTranslation;
use Doctrine\Common\Cache\Cache;
use Illuminate\Console\Command;

class organizations_dbl_status extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'organizations:dbl_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update the organization DBL status';

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
	    $organizations = json_decode(file_get_contents(storage_path("data/organizations/organizations_dbl.json")));
        foreach($organizations->orgs as $dbl_organization) {
        	// First handle direct equivalents

	        $organizationExists = OrganizationTranslation::where('name',$dbl_organization->full_name)->first();
        	if($organizationExists) {
        		$organization = $organizationExists->organization;
        		dd($organization);
	        }

        }

    }
}
