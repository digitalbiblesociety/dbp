<?php

use Illuminate\Database\Seeder;
use database\seeds\SeederHelper;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationTranslation;
use App\Models\Organization\OrganizationLogo;

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
		$googleSheetID = '1f5Vhhu7llkg3kI6Ga011Sb-OhwZIbfla4bwuN5Xcins';

		DB::table('organization_logos')->delete();
		DB::table('organization_translations')->delete();
		DB::table('organization_services')->delete();
		DB::table('organizations')->delete();
		$organizations = $seederHelper->csv_to_array("https://docs.google.com/spreadsheets/d/".$googleSheetID."/export?format=csv&id=".$googleSheetID."&gid=0");
		foreach ($organizations as $key => $data) {
			$organization = new Organization();
			foreach ($data as $key => $value) {
				if($value == '') $data[$key] = null;
			}
			unset($data['fobai']);
			unset($data['vernacularTitle']);
			unset($data['dba']);
			unset($data['globalContributor']);
			unset($data['libraryContributor']);

			$organization->create($data);
		}

		$organizationTranslations = $seederHelper->csv_to_array("https://docs.google.com/spreadsheets/d/$googleSheetID/export?format=csv&id=$googleSheetID&gid=557153729");
		foreach($organizationTranslations as $key => $data) {
			$organizationTranslation = new OrganizationTranslation();
			if($data['alt'] == '') $data['alt'] = 0;
			$organizationTranslation->create($data);
		}

		$organizationLogos = $seederHelper->csv_to_array("https://docs.google.com/spreadsheets/d/$googleSheetID/export?format=csv&id=$googleSheetID&gid=1154991446");
		foreach($organizationLogos as $key => $data) {
			$organizationLogo = new OrganizationLogo();
			if($data['logo'] == '') { continue; }
			$language = new \App\Models\Language\Language();
			$language = $language->fetchByID($data['language_code']);
			unset($data['language_code']);
			$data['language_id'] = $language->id;
			$organizationLogo->create($data);
		}

		$organizations = Organization::with('translations')->get();
		$dbpOrgs = $seederHelper->csv_to_array(storage_path('data/dbp2/organization.csv'));
		foreach($dbpOrgs as $dbpOrg) {
			foreach($organizations as $organization) {
				// Organization
				$translationExists = $organization->translations("eng")->first();
				if($translationExists) {
					$name = $translationExists->name;
					if(($name == $dbpOrg['english_description']) or ($name == $dbpOrg['english_name']) or ($name == $dbpOrg['name'])) {
						// Organization
						if($dbpOrg['donation_url'] != "NULL") {
							$organization->url_donate = $dbpOrg['donation_url'];
							$organization->save();
						}
					}
				}

			}
		}


		/*
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
		*/
	}

}
