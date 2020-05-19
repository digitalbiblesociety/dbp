<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteTemporaryZipFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:temporaryZipFiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete temporary zip files on downloads public folder';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->alert(Carbon::now() . ': temporary zip files deletion started.');
        $public_dir = public_path() . '/downloads';


        if (File::exists($public_dir)) {
            $files = collect(File::files($public_dir));

            $paths = $files->filter(function ($file) {
                return Carbon::createFromTimestamp(($file->getATime()))->lt(Carbon::now()->addHours(-1)) && $file->getExtension() === 'zip';
            })->map(function ($file) {
                return $file->getPathname();
            })->toArray();

            $this->info(Carbon::now() . ': ' . sizeof($paths) . ' files deleted.');
            File::delete($paths);
        }

        $this->alert(Carbon::now() . ': temporary zip files deletion finalized.');
    }
}
