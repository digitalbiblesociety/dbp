<?php

namespace App\Traits;

use App\Models\Organization\Asset;
use Aws\CloudFront\CloudFrontClient;
use Carbon\Carbon;
use Curl\Curl;
use Cache;

trait CallsBucketsTrait
{
    public function authorizeAWS($source)
    {
        $security_token = Cache::remember('iam_assumed_role', 60, function () {
            $role_call  = $this->assumeRole();
            if ($role_call) {
                $response_xml   = simplexml_load_string($role_call->response, 'SimpleXMLElement', LIBXML_NOCDATA);
                return json_decode(json_encode($response_xml));
            }
        });

        if (!optional($security_token)->AssumeRoleResult) {
            Cache::forget('iam_assumed_role');
            throw new \Exception('Iam role denied', 424);
        }

        if ($source === 'cloudfront') {
            return new CloudFrontClient([
                'version' => 'latest',
                'region'  => 'us-west-2',
                'credentials' => [
                    'key' => $security_token->AssumeRoleResult->Credentials->AccessKeyId,
                    'secret' => $security_token->AssumeRoleResult->Credentials->SecretAccessKey,
                    'token' =>  $security_token->AssumeRoleResult->Credentials->SessionToken
                ]
            ]);
        } else {
            return $security_token;
        }
    }

    public function signedUrl(string $file_path, $asset_id, int $transaction)
    {
        $asset = Asset::where('id', $asset_id)->first();
        $client = $this->authorizeAWS($asset->asset_type);

        // Return Either CloudFront Signed Urls
        if ($asset->asset_type === 'cloudfront') {
            $request_array = [
                'url'         => 'https://content.cdn.'.$asset_id.'.dbp4.org/'.$file_path.'?x-amz-transaction='.$transaction,
                'key_pair_id' => config('filesystems.disks.cloudfront.key'),
                'private_key' => storage_path('app/'.config('filesystems.disks.cloudfront.secret')),
                'expires'     => Carbon::now()->addHour()->timestamp,
            ];
            return $client->getSignedUrl($request_array);
        }

        // Or return S3 Urls
        if ($asset->asset_type === 's3') {
            $temp_session_token = $client->AssumeRoleResult->Credentials->SessionToken;
            $temp_secret_key    = $client->AssumeRoleResult->Credentials->SecretAccessKey;
            $temp_access_key    = $client->AssumeRoleResult->Credentials->AccessKeyId;

            $bucket = $asset->id;

            $timestamp = Carbon::now()->addDay()->timestamp;
            $data = "GET\n\n\n$timestamp\nx-amz-security-token:$temp_session_token\nx-amz-transaction:$transaction\n/$bucket".'/'.$file_path;
            $signature = base64_encode(hash_hmac('sha1', $data, $temp_secret_key, true));
            return "https://$bucket.s3.amazonaws.com/$file_path?AWSAccessKeyId=$temp_access_key&Signature=".urlencode($signature).'&x-amz-security-token='.urlencode($temp_session_token)."&x-amz-transaction=$transaction&Expires=$timestamp";
        }
    }

    private function assumeRole()
    {
        // Initialize timestamps
        $date        = date('Ymd');
        $timestamp   = str_replace([':','-'], '', Carbon::now()->toIso8601ZuluString());

        $form_params = [
            'Action'          => 'AssumeRole',
            'Version'         => '2011-06-15',
            'RoleArn'         => config('filesystems.disks.s3.arn'),
            'DurationSeconds' => 43200,
            'RoleSessionName' => config('app.server_name').$timestamp,
        ];
        $credentials  = $this->generateCreds('/', $timestamp, $form_params);

        $client = new Curl();
        $client->setHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
        $client->setHeader('X-Amz-Date', $timestamp);
        $client->setHeader('Authorization', 'AWS4-HMAC-SHA256 Credential='.config('filesystems.disks.s3.key')."/$date/us-east-1/sts/aws4_request, SignedHeaders=content-type;host;x-amz-date, Signature=$credentials");
        $client->setHeader('Accept', '');
        $client->setHeader('Accept-Encoding', 'identity');
        $response = $client->post('https://sts.amazonaws.com/', $form_params);

        return $response;
    }

    /*
     * Generate the signature for the assumeRole function
     *
     */
    private function generateCreds($canonical_uri, $current_time, $request_params)
    {
        $region = 'us-east-1';
        $algorithm = 'AWS4-HMAC-SHA256';
        $service = 'sts';

        $scope = date('Ymd')."/$region/$service/aws4_request";

        $request_body = '';
        foreach ($request_params as $request_key => $request_param) {
            if ($request_key == 'RoleArn') {
                $request_param = urlencode($request_param);
            }
            $request_body .= $request_key.'='.$request_param.'&';
        }
        $request_body = rtrim($request_body, '&');
        $encrypt_body = hash('sha256', $request_body);

        $request        = "POST\n$canonical_uri\n\ncontent-type:application/x-www-form-urlencoded; charset=utf-8\nhost:sts.amazonaws.com\nx-amz-date:".$current_time."\n\ncontent-type;host;x-amz-date\n$encrypt_body";
        $string_to_sign = "$algorithm\n$current_time\n$scope\n" . hash('sha256', $request);
        $signature      = $this->encryptValues($string_to_sign, 'sts');
        return $signature;
    }

    private function encryptValues($string_to_sign, $service, $region = 'us-east-1')
    {
        $layer_1   = hash_hmac('sha256', date('Ymd'), 'AWS4'.config('filesystems.disks.s3.secret'), true);
        $layer_2   = hash_hmac('sha256', $region, $layer_1, true);
        $layer_3   = hash_hmac('sha256', $service, $layer_2, true);
        $layer_4   = hash_hmac('sha256', 'aws4_request', $layer_3, true);
        $signature = hash_hmac('sha256', $string_to_sign, $layer_4);

        return $signature;
    }
}
