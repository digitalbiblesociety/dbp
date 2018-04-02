<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Storage;

class send_api_logs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $log_string;
	/**
	 * Create a new job instance.
	 *
	 * @param $log_string
	 */
	// Status Code, Headers, Params, Body, Time
    public function __construct($log_string)
    {
        $this->log_string = $log_string;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    	$file = '/srv-dbp-dev/'.date('Y-m-d').'.log';
		$fileExists = Storage::disk('s3_dbs_log')->exists($file);
		if($fileExists) {
			Storage::append($file, $this->log_string);
		} else {
			Storage::put($file,$this->log_string);
		}

    }
}
