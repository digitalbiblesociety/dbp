<?php
namespace App\Jobs;
use App\Traits\CallsBucketsTrait;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Storage;
use Cache;
use Aws\S3\S3Client;

class send_api_logs implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CallsBucketsTrait;
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
		$this->log_string = str_replace(array("\n", "\r"), '', $this->log_string);
		$this->addGeoData();
		$current_time = Carbon::now();
		$files = Storage::disk('logs')->files('api');
		// remove any none log files from processing
		foreach($files as $key => $file) if(substr($file,-4) !== '.log') unset($files[$key]);
		// If no files exist
		if(\count($files) === 0) {
			$starting_string = ''; //'timestamp∞server_name∞status_code∞path∞user_agent∞params∞ip_addresss∞3_signatures∞lat∞lon∞country∞city∞state_name∞postal_code';
			Storage::disk('logs')->put('api/' . $current_time->getTimestamp() . '-' . env('APP_SERVER_NAME') . '.log', $starting_string);
			$current_file_time = Carbon::now();
			$files = Storage::disk('logs')->files('api');
			$current_file = end($files);
		} else {
			$current_file = end($files);
			$current_file_time = explode('-',$current_file);
			$current_file_time = substr($current_file_time[0],4);
			$current_file_time = Carbon::createFromTimestamp($current_file_time);
		}

		// Push to S3 every five minutes, delete the latest file and create a new one
		if($current_time->diffInMinutes($current_file_time) > 5) {
			$log_contents = Storage::disk('logs')->get($current_file);
			$this->pushToS3($current_file, $log_contents);
			Storage::disk('logs')->delete($current_file);
		} else {
			Storage::disk('logs')->append($current_file, $this->log_string);
		}
	}
	private function addGeoData()
	{
		$log_array = explode('∞', $this->log_string);
		$ip_address = $log_array[6] ?? null;
		if($ip_address) {
			$geo_ip = geoip($ip_address);
			$geo_array = [
				$geo_ip->getAttribute('lat'),
				$geo_ip->getAttribute('lon'),
				$geo_ip->getAttribute('country'),
				$geo_ip->getAttribute('city'),
				$geo_ip->getAttribute('state_name'),
				$geo_ip->getAttribute('postal_code')
			];
			$this->log_string = implode('∞', array_merge($log_array,$geo_array));
		}
	}

	private function pushToS3($current_file, $log_contents)
	{
		if(env('APP_ENV') === 'local') Cache::forget('iam_assumed_role');
		$security_token = Cache::remember('iam_assumed_role', 60, function () {
			$role_call  = $this->assumeRole();
			if($role_call) {
				$response_xml   = simplexml_load_string($role_call->response,'SimpleXMLElement',LIBXML_NOCDATA);
				return json_decode(json_encode($response_xml));
			}
		});

		$s3 = new S3Client([
			'version' => 'latest',
			'region'  => 'us-west-2',
			'credentials' => [
				'key' => $security_token->AssumeRoleResult->Credentials->AccessKeyId,
				'secret' => $security_token->AssumeRoleResult->Credentials->SecretAccessKey,
				'token' =>  $security_token->AssumeRoleResult->Credentials->SessionToken
			]
		]);

		$s3->putObject([
			'Bucket' => 'dbp-log',
			'Key'    => 'srv/'.substr($current_file,4),
			'Body'   => $log_contents
		]);
	}

}