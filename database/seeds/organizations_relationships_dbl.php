<?php

use Illuminate\Database\Seeder;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationRelationship;
class organizations_relationships_dbl extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$seederHelper = new \database\seeds\SeederHelper();
	    $organizations = Organization::with('translations')->get();
	    $dblOrgs = json_decode(file_get_contents(storage_path('/data/organizations/organizations_dbl.json')));

	    $dbl = Organization::where('slug','the-digital-bible-library')->first();

	    foreach($dblOrgs as $dblOrg) {
		    foreach($organizations as $organization) {
			    // Organization
			    $translationExists = $organization->translations("eng")->first();
			    if($translationExists) {
				    $name = $translationExists->name;
				    if(($name == $dblOrg->full_name)) {
					    OrganizationRelationship::create([
					    	'organization_child_id' => $organization->id,
						    'organization_parent_id' => $dbl->id,
						    'type' => 'member',
						    'relationship_id' => $dblOrg->id
					    ]);
					    $added[] = $dblOrg->id;
				    }
			    }

		    }
	    }

	    foreach ($dblOrgs as $dbl_org) {
	    	if(!in_array($dbl_org->id,$added)) echo "\n Missing: ".$dbl_org->full_name;
	    }


    }
}
