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

		DB::connection('dbp')->table('organization_logos')->delete();
		DB::connection('dbp')->table('organization_translations')->delete();
		DB::connection('dbp')->table('organizations')->delete();
		$organizations = $seederHelper->csv_to_array("https://docs.google.com/spreadsheets/d/$googleSheetID/export?format=csv&id=$googleSheetID&gid=0");
		foreach ($organizations as $key => $data) {
			$organization = new Organization();
			foreach($data as $subkey => $value) {
				if($value === '') $data[$subkey] = null;
			}
			if(!$data['slug']) {continue;}
			unset($data['fobai'], $data['vernacularTitle'], $data['dba'], $data['globalContributor'], $data['libraryContributor'], $data['organization'], $data['country']);

			$organization->create($data);
		}

		$languages = \App\Models\Language\Language::where('iso','eng')->orWhere('iso','nep')->orWhere('iso','spa')->first();
		$organizationTranslations = $seederHelper->csv_to_array("https://docs.google.com/spreadsheets/d/$googleSheetID/export?format=csv&id=$googleSheetID&gid=557153729");
		foreach($organizationTranslations as $key => $data) {
			$language = $languages->where('iso',$data['language_iso'])->first();
			$organizationTranslation = new OrganizationTranslation();
			$data['vernacular'] = (bool) $data['vernacular'];
			$data['language_id'] = $language->id;
			if($data['alt'] === '') $data['alt'] = 0;
			$organizationTranslation->create($data);
		}

		$organizationLogos = $seederHelper->csv_to_array("https://docs.google.com/spreadsheets/d/$googleSheetID/export?format=csv&id=$googleSheetID&gid=1154991446");
		foreach($organizationLogos as $key => $data) {
			$organizationLogo = new OrganizationLogo();
			if($data['url'] === '') { continue; }
			$organizationLogo->create($data);
		}

		$organizations = Organization::with('translations')->get();
		$dbpOrgs = $seederHelper->csv_to_array(storage_path('data/dbp2/organization.csv'));
		foreach($dbpOrgs as $dbpOrg) {
			foreach($organizations as $organization) {
				// Organization
				$translationExists = $organization->translations('eng')->first();
				if($translationExists) {
					$name = $translationExists->name;
					if(($name === $dbpOrg['english_description']) && ($name === $dbpOrg['english_name']) && ($name === $dbpOrg['name'])) {
						// Organization
						if($dbpOrg['donation_url'] != 'NULL') {
							$organization->url_donate = $dbpOrg['donation_url'];
							$organization->save();
						}
					}
				}

			}
		}
	}

}
