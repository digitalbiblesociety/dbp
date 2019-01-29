<?php

namespace App\Console\Commands\Wiki;

use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationRelationship;
use App\Models\Organization\OrganizationTranslation;
use Doctrine\Common\Cache\Cache;
use Illuminate\Console\Command;
use TomLingham\Searchy\Facades\Searchy;

class UpdateOrganizationsDblStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'organizations:dbl {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update the organization DBL status';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $arguments = $this->arguments('action');

        switch ($arguments['action']) {
            case 'compare':
                $this->compareOrganizations();
                break;
            case 'update':
                $this->updateOrganizations();
                break;
        }
    }

    public function updateOrganizations()
    {
        $organizations = json_decode(file_get_contents(storage_path('data/organizations/organizations_dbl.json')));
        $organizations = collect($organizations->orgs);
        $relationships = OrganizationRelationship::with('childOrganization')->whereIn('relationship_id', $organizations->pluck('id'))->get();
        foreach ($organizations as $organization) {
            //$relationship = $relationships->where('relationship_id',)
        }
    }


    public function compareOrganizations()
    {
        $organizations = json_decode(file_get_contents(storage_path('data/organizations/organizations_dbl.json')));
        $dbl_id = Organization::where('slug', 'digital-bible-library')->first()->id;
        $missing = [];
        $pre_matched = [
            'Bible Society of El Salvador' => '535',
            'Voice of Christ Media Ministries' => '514',
            'Swedish Bible Society' => '129',
            'Crossway / Good News Publishers' => '5 ',
            'Samaritan’s Purse' => '560',
            'iMatt Solutions Limited' => '570',
            '"Bible Society in Guatemala"' => '73',
            '"Bible Society in Italy"' => '404',
            'Short Sands, LLC' => '573',
            'Bible Society in Lebanon' => '87',
            'German Bible Society' => '71',
            'Bible Society in the Central African Republic' => '133',
            'Bible Society in the Netherlands Antilles' => '575',
            'Argentine Bible Society' => '37',
            'Danish Bible Society' => '170',
            'John the Forerunner Church of Christians of Evangelical Faith of Minsk City' => '984',
            'Peruvian Bible Society' => '112',
            'Colombian Bible Society' => '334',
            'Salem Web Network (BibleStudyTools.com)' => '581',
            'Through the Word, Inc' => '582',
            'z-bible.org' => '583',
            'Galcom Int’l' => '585',
            'Dominican Republic Bible Society' => '136',
            'United Bible Societies in Venezuela' => '335',
            'Power to Change  (formerly Campus Crusade for Christ Australia)' => '587',
            'Bible Society in Namibia' => '102',
            'Aramaic Bible Translation, Inc.' => '588',
            'Google, Inc.' => '589',
            'Bible Society in Mali' => '93',
            'Pakistan Bible Society' => '108',
            'Olive Tree Bible Software' => '590',
            'Bible Society in Georgia' => '70',
            'Bible Society in Northern Ireland' => '593',
            'Honduras, Bible Society in' => '76',
            'Seychelles Bible Society' => '326',
            'Bible Society of the West Indies' => '147',
            'Millennial Apps, LLC' => '600',
            'STEP Bible of Tyndale House Cambridge' => '361',
            'Malta Bible Society' => '606',
            'Biblia Plus, The UBS Bible App' => '607',
            'Truth For Today World Mission School, Inc.' => '611',
            'Bible Society in Gabon' => '68',
            'Bible Society in Senegal' => '118',
            'The Nigeria Bible Translation Trust' => '249',
            'Norwegian Bible Society' => '107',
            'Davar Partners International' => '440',
            'Bible Society of the Republic of Macedonia' => '554',
            'Ukrainian Bible Society' => '145',
            'Renewed Vision LLC' => '553',
            'Torch Trust for the Blind' => '552',
            'Icelandic Bible Society' => '79',
            'Institute for Bible Translation, Russia' => '201',
            'Société Biblique de Genève' => '69',
            'Bible Society in Algeria' => '550',
            'OneSheep, a charity registered in England and Wales, number 1151906' => '617',
            'United Bible Societies in Ecuador' => '866',
            //'Gobaith i Gymru' => '1054',
            'Alpha International' => '1055',
            'Bible Society of Congo (Republic)' => '135',
            'Bible Society in Guatemala' => '73',
            'Bible Society in Italy' => '404',
            'Museum of the Bible' => '1056',
            'Bible League - Bulgaria' => '1059',
            'Mission Evangélique Réformée Néerlandaise' => '1058',
            'Bible Translation Institute at Zaoksky, Russia' => '1059',
            'United Bible Societies in Ecuador' => '866',
            'Bible Society of Côte d\'Ivoire' => '62'
        ];

        foreach ($organizations->orgs as $dbl_organization) {
            $translationMatchExists    = OrganizationTranslation::where('name', $dbl_organization->full_name)->first();
            $relationshipAlreadyExists = OrganizationRelationship::where('organization_parent_id', $dbl_id)->where('relationship_id', $dbl_organization->id)->first();
            if ($relationshipAlreadyExists || ($dbl_id === $dbl_organization->id)) {
                continue;
            }

            // First handle manually matched
            if (isset($pre_matched[$dbl_organization->full_name])) {
                OrganizationRelationship::create([
                    'type'                   => 'Member',
                    'organization_child_id'  => $pre_matched[$dbl_organization->full_name],
                    'organization_parent_id' => $dbl_id,
                    'relationship_id'        => $dbl_organization->id
                ]);
            }

            // Second handle direct equivalents
            if ($translationMatchExists) {
                $organization = $translationMatchExists->organization;
                OrganizationRelationship::create([
                    'type'                   => 'Member',
                    'organization_child_id'  => $organization->id,
                    'organization_parent_id' => $dbl_id,
                    'relationship_id'        => $dbl_organization->id
                ]);
            } else {
                // Otherwise Fuzzy Search for Provider Name
                $organizations = @Searchy::driver('ufuzzy')->search(config('database.connections.dbp.database').'.organization_translations')->fields('name')->query($dbl_organization->full_name)->getQuery()->limit(5)->get();
                if(!isset($organizations)) {
                    $missing[] = $dbl_organization->full_name;
                    continue;
                }
                if ($organizations->count() === 0) {
                    $missing[] = $dbl_organization->full_name;
                    continue;
                }

                // Present Data to User
                $this->comment("\n\n==========$dbl_organization->full_name==========");
                $this->info(json_encode($organizations->pluck('name', 'organization_id'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                // Get User Input
                $confirmed       = false;
                $organization_id = $this->ask('Please enter the number of the Closest Match, if none just hit 0');
                if ((int) $organization_id === 0) {
                    $missing[$dbl_organization->id] = $dbl_organization->full_name;
                    continue;
                }

                if (!OrganizationRelationship::where([
                    'type'                   => 'Member',
                    'organization_child_id'  => $organization_id,
                    'organization_parent_id' => $dbl_id,
                    'relationship_id'        => $dbl_organization->id
                ])->first()) {
                    OrganizationRelationship::create([
                        'type'                   => 'Member',
                        'organization_child_id'  => $organization_id,
                        'organization_parent_id' => $dbl_id,
                        'relationship_id'        => $dbl_organization->id
                    ]);
                }
            }
        }
        print_r($missing);
    }
}
