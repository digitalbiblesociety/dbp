<?php

namespace App\Console\Commands\BibleEquivalents;

use App\Models\Bible\BibleEquivalent;
use App\Models\Organization\Organization;
use Illuminate\Console\Command;

class SyncTalkingBibles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:talkingBibles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bible_equivalents = cacheRememberForever('talking_bibles', function () {
            $opts = [
                'http' => [
                    'method' => 'GET',
                    'header' => 'Authorization:Token token=' . config('services.talkingBibles.key')
                ]
            ];
            $context   = stream_context_create($opts);
            $baseurl   = 'https://listen.talkingbibles.org/api/v1/';
            $page      = 1;
            $count     = 25;

            while ($count === 25) {
                $bibles = json_decode(file_get_contents($baseurl . 'recordings.json?page=' . $page, false, $context));
                $count = count($bibles);
                $bible_equivalents[] = $bibles;
                $page++;
            }

            return $bible_equivalents;
        });

        $bible_equivalents = collect($bible_equivalents)->flatten();

        $organization = Organization::whereSlug('talking-bibles-international')->first();
        $recorded_equivalents = BibleEquivalent::whereIn('equivalent_id', $bible_equivalents->pluck('id'))
            ->where('organization_id', $organization->id)->get()->pluck('equivalent_id');

        foreach ($bible_equivalents as $bible_equivalent) {
            if (!$recorded_equivalents->contains($bible_equivalent->id)) {
                BibleEquivalent::create([
                    'bible_id'        => null,
                    'site'            => $bible_equivalent->href,
                    'organization_id' => $organization->id,
                    'equivalent_id'   => $bible_equivalent->id,
                    'type'            => 'website',
                    'suffix'          => null
                ]);
            }
        }
    }
}
