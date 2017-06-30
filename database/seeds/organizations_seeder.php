<?php

use Illuminate\Database\Seeder;
use \database\seeds\SeederHelper;
use \App\Models\Organization\Organization;
use \App\Models\Organization\OrganizationTranslation;

class organizations_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seederHelper = new SeederHelper();

        $organizations = $seederHelper->csv_to_array(storage_path() . '/data/organizations/organizations.csv');

        foreach ($organizations as $key => $data) {
            $organization = new Organization();
            if($data['inactive'] == '') $data['inactive'] = 0;
            $organization->create($data);
        }

        $organizationTranslations = $seederHelper->csv_to_array(storage_path() . '/data/organizations/organization_translations.csv');
        foreach($organizationTranslations as $key => $data) {
            $organizationTranslation = new OrganizationTranslation();
            $organizationTranslation->create($data);
        }




        $seederHelper = new SeederHelper();
        $contributors = $seederHelper->csv_to_array('https://docs.google.com/spreadsheets/d/1yQ00Jw6LyIf7epfANDSYUXzFg9WEM_4rgLma88E--c0/export?format=csv&id=1yQ00Jw6LyIf7epfANDSYUXzFg9WEM_4rgLma88E--c0&gid=0');
        foreach($contributors as $contributor) {
            $organization = Organization::where('slug',$seederHelper->slug($contributor['Name']))->first();
            if(!$organization) {
                $organization = new Organization();
                $organization->fobai = false;
                $organization->facebook = null;
                $organization->twitter = null;
                $organization->address = null;
                $organization->phone = null;
                $organization->email = null;
                $organization->slug = $seederHelper->slug($contributor['Name']);
                $organization->libraryContributor = $contributor['libraryContributor'];
                $organization->website = $contributor['URL'];
                $organization->save();
            } else {
                $organization->slug = $seederHelper->slug($contributor['Name']);
                $organization->libraryContributor = $contributor['libraryContributor'];
                $organization->website = $contributor['URL'];
                $organization->save();
            }
        }

    }

}
