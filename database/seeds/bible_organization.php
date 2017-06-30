<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;
use App\Models\Bible\Bible;

class bible_organization extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bible_organization')->delete();
        $seederhelper = new SeederHelper();
        $bible_organizations = $seederhelper->csv_to_array(storage_path().'/data/organizations/bible_publisher.csv');
        foreach($bible_organizations as $connection) {
            $organization = \App\Models\Organization\Organization::where('id',$connection['publisher_id'])->first();
            if(!Bible::find($connection['bible_id'])) {
                echo "\n Missing:". $connection['bible_id'];
                continue;
            }

            DB::table('bible_organization')->insert([
                'bible_abbr'       => $connection['bible_id'],
                'organization_id'  => $organization->slug,
                'contributionType' => 2
            ]);
        }
    }
}
