<?php

namespace App\Helpers\AWS;

use Illuminate\Support\Facades\Storage;

class Bucket {

	public static function signedUrl(string $file, string $bucket = 'dbp-dev', int $expiry = 5)
	{
		$prefix = 'DBS_';
		$bucket = 'dbs-web';

		$parsed['X-Amz-Algorithm'] = 'AWS4-HMAC-SHA256';
		$parsed['X-Amz-Random'] = rand(0,1000000000);
		$parsed['X-Amz-Content-Sha256'] = 'UNSIGNED-PAYLOAD';
		$parsed['X-Amz-Credential'] = env($prefix.'AWS_KEY').'%2F'.date('Ymd').'%2F'.env($prefix.'AWS_REGION').'%2Fs3%2Faws4_request';
		$parsed['X-Amz-Date'] = gmdate('Ymd\THis\Z', now()->timestamp);
		$parsed['X-Amz-SignedHeaders'] = 'host';
		$parsed['X-Amz-Expires'] = $expiry * 60;
		$parsed['X-Amz-Signature'] = self::createSignature($file,$prefix,$expiry);

		$parsed = implode('&', array_map(
			function ($v, $k) { return sprintf("%s=%s", $k, $v); },
			$parsed,
			array_keys($parsed)
		));

		$path = "https://$bucket.s3.".env($prefix.'AWS_REGION').".amazonaws.com/".$file.'?'.$parsed;
		//return $path;

		$name =  ($bucket == 'dbp-dev') ? 's3_fcbh' : 's3_dbs';
		$s3 = Storage::disk($name);

		$client = $s3->getDriver()->getAdapter()->getClient();
		$expiry = "+" . $expiry . " minutes";

		$command = $client->getCommand('GetObject', [
			'Bucket' => $bucket,
			'Key'    => $file
		]);

		$request = $client->createPresignedRequest($command, $expiry);
		return (string) $request->getUri();
	}

	public static function createSignature($file,$prefix,$expiry)
	{
		$longDate        = str_replace('-','',str_replace(':','',now()->toIso8601ZuluString()));
		$shortDate       = substr($longDate, 0, 8);
		$region          = env($prefix.'AWS_REGION');
		$service         = 's3';
		$publicKey       = env($prefix.'AWS_KEY');
		$secretKey       = env($prefix.'AWS_KEY_SECRET');
		$bucket          = env($prefix.'AWS_BUCKET');
		$credentialScope = $shortDate.'/'.env($prefix.'AWS_REGION').'/s3/aws4_request';

$canonicalRequest = "GET
/$file
X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=".$publicKey."%2F".$shortDate."%2F".$region."%2Fs3%2Faws4_request&X-Amz-Date=".$shortDate."T000000Z&X-Amz-Expires=".($expiry*60)."&X-Amz-SignedHeaders=host
host:$bucket.s3.".$region.".amazonaws.com

host
UNSIGNED-PAYLOAD";

//dd($canonicalRequest);

			// CREATE STRING TO SIGN
			$hash         = hash('sha256', $canonicalRequest);
$stringToSign = "AWS4-HMAC-SHA256
$longDate
$credentialScope
$hash";
			$dateKey      = hash_hmac('sha256', $shortDate, "AWS4{$secretKey}", true);
			$regionKey    = hash_hmac('sha256', $region, $dateKey, true);
			$serviceKey   = hash_hmac('sha256', $service, $regionKey, true);
			$cachedKey    = hash_hmac('sha256', 'aws4_request', $serviceKey, true);
			return hash_hmac('sha256', $stringToSign, $cachedKey);
	}

	// public static function signedUrl(string $file, string $name = 's3_fcbh', string $bucket = 'dbp_dev', int $expiry = 5)

	public static function download($files, string $name = 's3_fcbh', string $bucket = 'dbp_dev', int $expiry = 5, $books = null)
	{
		$fileset_id = $files->first()->fileset->id;
		$bible_id = $files->first()->fileset->bible->id;
		$stream = new S3StreamZip([
			'key'    => env('FCBH_AWS_KEY'),
			'secret' => env('FCBH_AWS_SECRET'),
			'bucket' => env('FCBH_AWS_BUCKET'),
			'region' => env('FCBH_AWS_REGION'),
		]);

		try {
			$stream->bucket('dbp-dev')->prefix("audio/$bible_id/$fileset_id")->send($fileset_id."_".$books->implode("_").".zip",$books->toArray());
		} catch (InvalidParameterException $e) {
			echo $e->getMessage();
		} catch (S3Exception $e) {
			echo $e->getMessage();
		}
	}

	public static function upload($files, string $name = 's3_fcbh', string $bucket = 'dbp_dev', int $expiry = 5, $books = null)
	{
		$s3 = Storage::disk($name);
		$client = $s3->getDriver()->getAdapter()->getClient();
		$expiry = "+" . $expiry . " minutes";

	}

}