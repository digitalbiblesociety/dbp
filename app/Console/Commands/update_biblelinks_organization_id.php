<?php

namespace App\Console\Commands;

use App\Models\Bible\BibleLink;
use App\Models\Organization\OrganizationTranslation;
use Illuminate\Console\Command;
use TomLingham\Searchy\Facades\Searchy;

class update_bible_links extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:bible_links {focus}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Preform Operations on the Bible Links Table';

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
        $focus = $this->arguments('focus');

        switch ($focus['focus']) {
            case "organization": {
                $this->organizationFocus();
                break;
            }
        }
    }

    public function organizationFocus()
    {
        $update_count = 0;
        $bible_links = BibleLink::where('organization_id', null)->get();
        $organization_translations = OrganizationTranslation::all();
        $skippedProviders = [];

        // Direct Matches
        foreach ($bible_links as $bible_link) {
            $organization = $organization_translations->where('name', $bible_link->provider)->first();
            if ($organization) {
                $bible_link->organization_id = $organization->organization_id;
                $bible_link->save();
                $update_count++;
                $skippedProviders[] = $bible_link->provider;
            }
        }

        // Fuzzy Matches
        foreach ($bible_links as $bible_link) {
            // If Already Processed Skip
            $skippedProviders = array_unique($skippedProviders);
            if (in_array($bible_link->provider, $skippedProviders)) {
                continue;
            }

            // Otherwise Fuzzy Search for Provider Name
            $organizations = Searchy::search('dbp.organization_translations')->fields('name')->query($bible_link->provider)->getQuery()->limit(5)->get();
            if ($organizations->count() == 0) {
                continue;
            }

            // Present Data to User
            $this->comment("\n\n==========$bible_link->provider==========");
            $this->info(json_encode($organizations->pluck('name', 'organization_id'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            // Get User Input
            $confirmed = false;
            $organization_id = $this->ask('Please enter the number of the Closest Match, if none just hit enter');
            if ($organization_id == 0) {
                $skippedProviders[] = $bible_link->provider;
                continue;
            }

            while ($confirmed == false) {
                // Validate Input
                if (!in_array($organization_id, $organizations->pluck('organization_id')->toArray())) {
                    if ($this->confirm("Your selection $organization_id is not in the recommendations, is that your intention? [yes|no]")) {
                        $confirmed = true;
                    }
                }

                // Save organization_id
                if ($organization_id) {
                    $links = $bible_links->where('provider', $bible_link->provider)->all();
                    foreach ($links as $link) {
                        $link->organization_id = $organization_id;
                        $link->save();
                        $update_count++;
                    }
                    $skippedProviders[] = $bible_link->provider;
                    $confirmed = true;
                }
            }
        }
    }
}
