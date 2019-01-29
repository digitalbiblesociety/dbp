<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncGlobalRecordingsNetwork extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:grn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs the Listed Resources from the GRN Partner Organization';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }

    public function index()
    {
        return json_decode(storage_path('data/connections/grn.json'));
    }

    public function sync()
    {
        set_time_limit(0);
        ini_set('memory_limit', '500M');
        $storage = Storage::disk('data');
        $pages   = json_decode($storage->get('connections/grn.json'), true);

        foreach ($pages as $page) {
            if ($storage->exists('connections/grn/' . basename($page) . '.json')) {
                continue;
            }

            $dom = HtmlDomParser::file_get_html($page, false, null, 0);
            foreach ($dom->find('.common-list div') as $collection) {
                $albums[] = [
                    'title'       => $collection->find('h3.title', 0)->plaintext,
                    'link'        => $collection->find('a', 0)->href,
                    'description' => $collection->find('p', 0)->plaintext,
                ];
            }

            $storage->put('/connections/grn/' . basename($page) . '.json', json_encode($albums, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            unset($albums, $dom);
        }
    }
}
