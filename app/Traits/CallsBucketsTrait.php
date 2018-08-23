<?php

namespace App\Traits;

use Carbon\Carbon;
use Curl\Curl;
use Aws\S3\S3Client;
use Cache;
use SimpleXMLElement;
trait CallsBucketsTrait {

	function __construct() {
		$this->key     = env('AWS_KEY');
		$this->secret  = env('AWS_SECRET');
		$this->arnRole = env('AWS_ARN_ROLE');
	}

	public function signedUrl(string $file, string $bucket = 'dbp-dev', int $expiry = 5)
	{
		$prefix = 'DBS_';
		$bucket = 'dbs-web';
		$expiry = $expiry * 60;

		$security_token = Cache::remember('iam_assumed_role', 1200, function () {
			$role_call  = $this->assumeRole();
			if($role_call) {
				$response_xml   = simplexml_load_string($role_call->response,'SimpleXMLElement',LIBXML_NOCDATA);
				$response       = json_decode(json_encode($response_xml));
				$ar_token       = $response->AssumeRoleResult->Credentials->SessionToken;
				return $ar_token;
			}
		});

		return $this->aws_s3_link($security_token,$bucket,'/'.$file,$expiry * 600,$region = 'us-west-2');
	}

	public function aws_s3_link($security_token, $bucket, $canonical_uri, $expires = 3000, $region = 'us-east-1', $extra_headers = array()) {
		$encoded_uri = str_replace('%2F', '/', rawurlencode($canonical_uri));
		$signed_headers = array();
		foreach ($extra_headers as $key => $value) {
			$signed_headers[strtolower($key)] = $value;
		}
		if (!array_key_exists('host', $signed_headers)) {
			$signed_headers['host'] = ($region == 'us-east-1') ? "$bucket.s3.amazonaws.com" : "$bucket.s3-$region.amazonaws.com";
		}
		ksort($signed_headers);
		$header_string = '';
		foreach ($signed_headers as $key => $value) {
			$header_string .= $key . ':' . trim($value) . "\n";
		}
		$signed_headers_string = implode(';', array_keys($signed_headers));
		$timestamp = time();
		$date_text = gmdate('Ymd', $timestamp);
		$time_text = $date_text . 'T000000Z';
		$algorithm = 'AWS4-HMAC-SHA256';
		$scope = "$date_text/$region/s3/aws4_request";
		$x_amz_params = array(
			'X-Amz-Algorithm' => $algorithm,
			'X-Amz-Credential' => $this->key . '/' . $scope,
			'X-Amz-Date' => $time_text,
			'X-Amz-Transaction' => rand(0,10000000),
			'X-Amz-Security-Token' => $security_token,
			'X-Amz-SignedHeaders' => $signed_headers_string
		);
		if ($expires > 0) $x_amz_params['X-Amz-Expires'] = $expires;
		ksort($x_amz_params);
		$query_string_items = array();
		foreach ($x_amz_params as $key => $value) {
			$query_string_items[] = rawurlencode($key) . '=' . rawurlencode($value);
		}
		$query_string = implode('&', $query_string_items);
		$canonical_request = "GET\n\n$timestamp\n\nx-amz-security-token:$security_token\n";
		dd($canonical_request);

		$string_to_sign = "$algorithm\n\n$timestamp\n$scope\n" . hash('sha256', $canonical_request, false);

		$signature = $this->encryptValues($string_to_sign,'s3',$region);
		$url = 'https://' . $signed_headers['host'] . $encoded_uri . '?' . $query_string . '&X-Amz-Signature=' . $signature;
		return $url;
	}

	private function assumeRole()
	{
		$date        = date('Ymd');
		$timestamp   = str_replace([':','-'],'', Carbon::now()->toIso8601ZuluString());

		$form_params = [
			'Action'          => 'AssumeRole',
			'Version'         => '2011-06-15',
			'RoleArn'         => $this->arnRole,
			'DurationSeconds' => 43200,
			'RoleSessionName' => env('APP_SERVER_NAME').$timestamp,
		];
		$credentials  = $this->generateCreds('/', $timestamp, $form_params);

		$client = new Curl();
		$client->setHeader('Content-Type','application/x-www-form-urlencoded; charset=utf-8');
		$client->setHeader('X-Amz-Date',$timestamp);
		$client->setHeader('Authorization',"AWS4-HMAC-SHA256 Credential=$this->key/$date/us-east-1/sts/aws4_request, SignedHeaders=content-type;host;x-amz-date, Signature=$credentials");
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
		$layer_1   = hash_hmac('sha256', date('Ymd'), 'AWS4'.$this->secret, true);
		$layer_2   = hash_hmac('sha256', $region, $layer_1, true);
		$layer_3   = hash_hmac('sha256', $service, $layer_2, true);
		$layer_4   = hash_hmac('sha256', 'aws4_request', $layer_3, true);
		$signature = hash_hmac('sha256', $string_to_sign, $layer_4);

		return $signature;
	}


}
