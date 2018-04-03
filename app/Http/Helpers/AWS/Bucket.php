<?php

namespace App\Helpers\AWS;

use App\Models\Bible\BibleFile;
use Illuminate\Support\Facades\Storage;
use App\Helpers\AWS\S3StreamZip;

class Bucket {
	public static function signedUrl(string $file, string $bucket = 'dbp_dev', int $expiry = 5)
	{
		$name =  ($bucket == 'dbp_dev') ? 's3_fcbh' : 's3_dbs';
		$base_url = Storage::disk($name)->getConfig()->get('url');
		if(!isset($base_url)) return Storage::disk($name)->temporaryUrl($file, now()->addMinutes($expiry));

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