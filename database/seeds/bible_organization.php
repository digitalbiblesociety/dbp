<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;
use App\Models\Bible\Bible;
use \App\Models\Bible\BibleOrganization;

class bible_organization extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bible_organizations')->delete();
        $seederhelper = new SeederHelper();
        $bible_organizations = $seederhelper->csv_to_array(storage_path().'/data/organizations/bible_publisher.csv');
        foreach($bible_organizations as $connection) {
            $organization = \App\Models\Organization\Organization::where('id',$connection['publisher_id'])->first();
            if(!Bible::find($connection['bible_id'])) {
                echo "\n Missing:". $connection['bible_id'];
                continue;
            }
			if(!$organization) { continue; }
            BibleOrganization::create([
                'bible_id'          => $connection['bible_id'],
                'organization_id'   => $organization->id,
                'relationship_type' => "publisher"
            ]);
        }
    }
}
