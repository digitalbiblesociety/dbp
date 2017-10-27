<?php

namespace App\Console\Commands;

use App\Models\Bible\BibleFileset;
use Illuminate\Console\Command;

class filesystem_check extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filesystem:check {driver=local}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks to verify that the file paths in the database point to real files';
	protected $driver;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
	    $this->driver = $this->argument('driver');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $filesets = BibleFileset::with('files')->get();
        foreach($filesets as $fileset) {
        	foreach ($filesets->files as $file) {
		        $exists = Storage::disk($this->driver)->exists("bibles/$fileset->bible_id/$fileset->set_id/$file->name");

        		if($exists) {
        			$this->info("Found: bibles/$fileset->bible_id/$fileset->set_id/$file->name");
		        } else {
			        $this->alert("Missing: bibles/$fileset->bible_id/$fileset->set_id/$file->name");
		        }

	        }
        }
    }
}
