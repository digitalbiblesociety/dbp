<?php

namespace App\Jobs;

use Carbon\Carbon;
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
		$this->addGeoData();
		$current_time = Carbon::now();
		$current_time_name = $current_time->timestamp;
		$files = Storage::disk('data')->files('srv-dbp-dev');
		foreach($files as $key => $file) if(substr($file,-4) != ".log") unset($files[$key]);

	    if(count($files) == 0) {
		    Storage::disk('data')->put('srv-dbp-dev/'.$current_time_name.'.log', 'timestamp:::status_code:::path:::user_agent:::params:::ip_address:::lat:::lon:::country:::city:::state_name:::postal_code');
		    $current_file_time = Carbon::now();
		    $files = Storage::disk('data')->files('srv-dbp-dev');
		    $current_file = end($files);
	    } else {
		    $current_file = end($files);
		    $current_file_time = Carbon::createFromTimestamp(intval(substr($current_file,0,-4)));
	    }

	    // Push to S3 every five minutes, delete the latest file and create a new one
	    if(Carbon::now()->diffInMinutes($current_file_time) > 5) {
			$log_contents = Storage::disk('data')->get($current_file);
			Storage::disk('s3_dbs_log')->put($current_file, $log_contents);
		    Storage::disk('data')->delete($current_file);
		    Storage::disk('data')->put('srv-dbp-dev/'.$current_time_name.'.log');
	    } else {
		    Storage::disk('data')->append($current_file, $this->log_string);
	    }
    }

    private function addGeoData()
    {
	    $log_array = explode(':::',$this->log_string);
	    $ip_address = isset($log_array[4]) ? $log_array[4] : null;
	    if($ip_address) {
		    $geo_ip = geoip($ip_address);
		    $geo_array = [$geo_ip->lat,$geo_ip->lon,$geo_ip->country,$geo_ip->city,$geo_ip->state_name,$geo_ip->postal_code];
		    $this->log_string = implode(':::',array_merge($log_array,$geo_array));
	    }
    }

}
