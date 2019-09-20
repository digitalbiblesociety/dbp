<?php

namespace App\Console\Commands\BibleEquivalents;

use App\Models\Bible\BibleLink;
use App\Models\Bible\BibleTranslation;
use App\Models\Organization\Organization;
use App\Models\User\User;
use App\Notifications\BibleNeedsCreation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SyncIBT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ibt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $user = User::where('email', config('app.contact'))->first();
        $response = \Cache::remember('ibt_russian_bibles', now()->addMonth(), function () {
            return json_decode(file_get_contents('http://ibt.org.ru/sites/default/files/media_manifest.json'));
        });

        $organization_id = Organization::where('slug', 'institute-for-bible-translation')->first()->id;
        BibleLink::where('organization_id', $organization_id)->delete();

        foreach ($response as $language => $bibles) {
            foreach ($bibles as $bible) {
                foreach ($bible as $type => $linkData) {
                    switch ($type) {
                        case 'ebook':
                            $type_equivalent = 'epub';
                            break;

                        case 'apps':
                            $type_equivalent = 'app';
                            break;

                        default:
                        case 'text':
                            $type_equivalent = 'web';
                            break;
                    }

                    $bibleTranslation = BibleTranslation::where('name', $linkData->name)->first();
                    if (!isset($bibleTranslation)) {
                        // Notification::send(config('app.contact'), new BibleNeedsCreation($bible));
                        continue;
                    }

                    BibleLink::create([
                        'bible_id'        => $bibleTranslation->bible_id,
                        'organization_id' => $organization_id,
                        'provider'        => 'IBT',
                        'title'           => $linkData->category,
                        'url'             => $linkData->url_en,
                        'type'            => $type_equivalent,
                    ]);
                }
            }
        }
    }
}
