<?php

namespace App\Console\Commands;

use App\Traits\CallsBucketsTrait;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Storage;
use Log;
use Aws\Ec2\Ec2Client;

class S3LogBackup extends Command
{

    use CallsBucketsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 's3log:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the logs to s3';
    protected $log_string;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->addGeoData();
        $current_time = Carbon::now();
        $files = Storage::disk('data')->files('api-node-logs');

        // remove any none log files from processing
        foreach ($files as $key => $file) {
            if (substr($file, -4) !== '.log') {
                unset($files[$key]);
            }
        }

        // If no files exist
        if (\count($files) === 0) {
            $starting_string = 'timestamp:::server_name:::status_code:::path:::user_agent:::params:::ip_address:::s3_signatures:::lat:::lon:::country:::city:::state_name:::postal_code';
            Storage::disk('logs')->put('api-node-logs/' . $current_time->getTimestamp() . '-' . config('app.server_name') . '.log', $starting_string);
            $current_file_time = Carbon::now();
            $files = Storage::disk('api');
            $current_file = end($files);
        } else {
            $current_file = end($files);
            $current_file_time = (int) Carbon::createFromTimestamp(substr($current_file, 12, -10));
        }

        Storage::disk('logs')->append($current_file, $this->log_string);

        // Push to S3 every couple minutes, delete the latest file and create a new one
        if ($current_time->diffInMinutes($current_file_time) > 2) {
            $log_contents = Storage::disk('data')->get($current_file);
            try {
                $this->pushToS3($current_file, $log_contents);
                Storage::disk('s3_dbs_log')->put($current_file, $log_contents);
                Storage::disk('data')->delete($current_file);
            } catch (\Exception $e) {
                Log::error('s3 log PUT operation failed');
            }
        }
    }

    private function pushToS3($current_file, $log_contents)
    {
        if (config('app.env') === 'local') {
            Cache::forget('iam_assumed_role');
        }
        $security_token = Cache::remember('iam_assumed_role', 60, function () {
            $role_call  = $this->assumeRole();
            if ($role_call) {
                $response_xml   = simplexml_load_string($role_call->response, 'SimpleXMLElement', LIBXML_NOCDATA);
                $response       = json_decode(json_encode($response_xml));
                return $response;
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

        try {
            // Upload data.
            $result = $s3->putObject([
                'Bucket' => 'dbp-log',
                'Key'    => 'srv/'.$current_file,
                'Body'   => 'Hello, world!'
            ]);
            // Print the URL to the object.
        } catch (S3Exception $e) {
            Log::error($e);
        }
    }

    private function addGeoData()
    {
        $log_array = explode(':::', $this->log_string);
        $ip_address = $log_array[6] ?? null;
        if ($ip_address) {
            $geo_ip = geoip($ip_address);
            $geo_array = [$geo_ip->lat, $geo_ip->lon, $geo_ip->country, $geo_ip->city, $geo_ip->state_name, $geo_ip->postal_code];
            $this->log_string = implode(':::', array_merge($log_array, $geo_array));
        }
    }
}
