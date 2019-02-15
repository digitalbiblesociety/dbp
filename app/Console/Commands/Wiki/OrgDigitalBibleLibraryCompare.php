<?php

namespace App\Console\Commands\Wiki;

use Illuminate\Console\Command;

use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationRelationship;
use App\Models\Organization\OrganizationTranslation;
use TomLingham\Searchy\Facades\Searchy;

class OrgDigitalBibleLibraryCompare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orgDigitalBibleLibrary:compare';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $organizations;

    protected $dbl_id;

    public function __construct()
    {
        $organizations = json_decode(file_get_contents(storage_path('data/organizations/organizations_dbl.json')));
        $duplicates = ['55dfef9e5117ad36e0f362c9'];

        foreach ($organizations->orgs as $org) {
            if (!in_array($org->id, $duplicates)) {
                $this->organizations[] = $org;
            }
        }

        $this->dbl_id = Organization::where('slug', 'digital-bible-library')->first()->id;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->handlePreMatchedOrgs();

        foreach ($this->organizations as $dbl_org) {
            if (!$this->organizationExists($dbl_org)) {
                $this->fuzzySearchOrgs($dbl_org);
            }
        }
    }

    private function organizationExists($dbl_org)
    {
        $translationMatchExists    = OrganizationTranslation::where('name', $dbl_org->full_name)->first();
        $relationshipAlreadyExists = OrganizationRelationship::where('organization_parent_id', $this->dbl_id)
                                                             ->where('relationship_id', $dbl_org->id)->first();
        if ($relationshipAlreadyExists || $this->dbl_id === $dbl_org->id) {
            return true;
        }

        if ($translationMatchExists) {
            OrganizationRelationship::firstOrCreate([
                'type'                   => 'Member',
                'organization_child_id'  => $translationMatchExists->organization->id,
                'organization_parent_id' => $this->dbl_id,
                'relationship_id'        => $dbl_org->id
            ]);
            return true;
        }

        return false;
    }

    private function fuzzySearchOrgs($dbl_org)
    {
        // Otherwise Fuzzy Search for Provider Name
        $organizations = Searchy::driver('ufuzzy')->search(config('database.connections.dbp.database').'.organization_translations')->fields('name')->query($dbl_org->full_name)->getQuery()->limit(5)->get();
        if (!isset($organizations)) {
            $missing[] = $dbl_org->full_name;
            return false;
        }
        if ($organizations->count() === 0) {
            $missing[] = $dbl_org->full_name;
            return false;
        }

        // Present Data to User
        $this->comment("\n\n==========$dbl_org->full_name==========");
        $this->info(json_encode($organizations->pluck('name', 'organization_id'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Get User Input
        $organization_id = $this->ask('Please enter the number of the Closest Match, if none just hit 0');
        if ((int) $organization_id === 0) {
            $missing[$dbl_org->id] = $dbl_org->full_name;
            return false;
        }

        OrganizationRelationship::firstOrCreate([
            'type'                   => 'Member',
            'organization_child_id'  => $organization_id,
            'organization_parent_id' => $this->dbl_id,
            'relationship_id'        => $dbl_org->id
        ]);
    }

    private function handlePreMatchedOrgs()
    {
        OrganizationRelationship::where('organization_parent_id', $this->dbl_id)->delete();

        $pre_matched_path = 'https://gist.githubusercontent.com/jonBitgood/500214188481e868cb3b06e87f33e06e/raw/ae4ca98978d04d5c0d6927003a3f4f55ceae699c/dbl-orgs-to-dbp-orgs.json';
        $pre_matched = json_decode(file_get_contents($pre_matched_path), true);
        foreach ($pre_matched['data'] as $pre_matched_org) {
            OrganizationRelationship::create($pre_matched_org);
        }
    }
}
