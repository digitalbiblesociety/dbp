<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class processBible implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $output_type;
    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($output_type, $id)
    {
	    $this->output = $output_type;
	    $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	    $operations = ['usfm2epub','usfm2html','usfm2inscript','usfm2dbp','usfm2json','usfm2pdf'];
	    if(!in_array($this->output_type,$operations)) report("Output Type not in Accepted Operations");

	    // Paths
	    $process_url = base_path("aletheia/processing/$this->output_type.py");
	    $storage = storage_path("bibles");
	    $storage_input = storage_path("bibles/input");
	    $storage_output = storage_path("bibles/output");

	    // Command
		exec("python $process_url $this->id $storage_input $storage_output $storage");
    }
}
