<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;
use App\Models\Organization\OrganizationRelationship;
use App\Models\Organization\Organization;
class organizations_relationships extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\DB::table('organization_relationships')->delete();
	    $sheet_id = '1f5Vhhu7llkg3kI6Ga011Sb-OhwZIbfla4bwuN5Xcins';
	    $gid = '1690882696';

	    $seederHelper = new SeederHelper();
	    $relationships = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/'.$sheet_id.'/export?format=csv&id='.$sheet_id.'&gid='.$gid);
        foreach ($relationships as $relationship) {
        	$parent_id = Organization::where('slug',$relationship['organization_parent_id'])->first();
	        $child_id = Organization::where('slug',$relationship['organization_child_id'])->first();
        	if($parent_id) {$relationship['organization_parent_id'] = $parent_id->id;} else {echo "\n Missing: ".$relationship['organization_parent_id'];continue;}
	        if($child_id) {$relationship['organization_child_id']  = $child_id->id;} else {echo "\n Missing: ".$relationship['organization_child_id'];continue;}
        	OrganizationRelationship::create($relationship);
        }
    }
}
