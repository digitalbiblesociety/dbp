<?php

namespace App\Traits;

use Carbon\Carbon;
use Curl\Curl;
use Cache;
use SimpleXMLElement;

trait CallsBucketsTrait {

	public function signedUrl(string $file, string $bucket = 'dbp-prod')
	{
		$security_token = Cache::remember('iam_assumed_role', 60, function () {
			$role_call  = $this->assumeRole();
			if($role_call) {
				$response_xml   = simplexml_load_string($role_call->response,'SimpleXMLElement',LIBXML_NOCDATA);
				$response       = json_decode(json_encode($response_xml));
				return $response;
			}
		});

		return $this->aws_s3_link($security_token,$bucket,'/'.$file);
	}

	public function aws_s3_link($security_token, $bucket, $canonical_uri, $region = 'us-west-2', $extra_headers = array()) {
		$timestamp = Carbon::now()->addDay()->timestamp;

		$temp_session_token = $security_token->AssumeRoleResult->Credentials->SessionToken;
		$temp_secret_key    = $security_token->AssumeRoleResult->Credentials->SecretAccessKey;
		$temp_access_key    = $security_token->AssumeRoleResult->Credentials->AccessKeyId;

		$random_int = rand(0,10000000);
		$data = "GET\n\n\n$timestamp\nx-amz-security-token:$temp_session_token\nx-amz-transaction:$random_int\n/$bucket$canonical_uri";
		$hmac = hash_hmac("sha1", $data, $temp_secret_key, TRUE);
		$signature = base64_encode($hmac);


		return "https://$bucket.s3.amazonaws.com$canonical_uri?AWSAccessKeyId=".$temp_access_key."&Signature=".urlencode($signature)."&x-amz-security-token=".urlencode($temp_session_token)."&x-amz-transaction=$random_int&Expires=$timestamp";
	}

	private function assumeRole()
	{
		// Initialize timestamps
		$date        = date('Ymd');
		$timestamp   = str_replace([':','-'],'', Carbon::now()->toIso8601ZuluString());

		$form_params = [
			'Action'          => 'AssumeRole',
			'Version'         => '2011-06-15',
			'RoleArn'         => env('AWS_ARN_ROLE'),
			'DurationSeconds' => 43200,
			'RoleSessionName' => env('APP_SERVER_NAME').$timestamp,
		];
		$credentials  = $this->generateCreds('/', $timestamp, $form_params);

		$client = new Curl();
		$client->setHeader('Content-Type','application/x-www-form-urlencoded; charset=utf-8');
		$client->setHeader('X-Amz-Date',$timestamp);
		$client->setHeader('Authorization',"AWS4-HMAC-SHA256 Credential=".env('AWS_KEY')."/$date/us-east-1/sts/aws4_request, SignedHeaders=content-type;host;x-amz-date, Signature=$credentials");
		$client->setHeader('Accept','');
		$client->setHeader('Accept-Encoding','identity');
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
			if($request_key == 'RoleArn') $request_param = urlencode($request_param);
			$request_body .= $request_key.'='.$request_param.'&';
		}
		$request_body = rtrim($request_body,'&');
		$encrypt_body = hash('sha256',$request_body);

		$request        = "POST\n$canonical_uri\n\ncontent-type:application/x-www-form-urlencoded; charset=utf-8\nhost:sts.amazonaws.com\nx-amz-date:".$current_time."\n\ncontent-type;host;x-amz-date\n$encrypt_body";
		$string_to_sign = "$algorithm\n$current_time\n$scope\n" . hash('sha256', $request);
		$signature      = $this->encryptValues($string_to_sign, 'sts');
		return $signature;
	}

	private function encryptValues($string_to_sign, $service, $region = 'us-east-1')
	{
		$layer_1   = hash_hmac('sha256', date('Ymd'), 'AWS4'.env('AWS_SECRET'), true);
		$layer_2   = hash_hmac('sha256', $region, $layer_1, true);
		$layer_3   = hash_hmac('sha256', $service, $layer_2, true);
		$layer_4   = hash_hmac('sha256', 'aws4_request', $layer_3, true);
		$signature = hash_hmac('sha256', $string_to_sign, $layer_4);

		return $signature;
	}


}
