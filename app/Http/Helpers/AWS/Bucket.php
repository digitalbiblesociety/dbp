<?php

namespace App\Helpers\AWS;

use Illuminate\Support\Facades\Storage;

class Bucket {

	// $url = Bucket::signedUrl('s3_fcbh','dbp_dev','basha.png',1);
	// return $url;
	public static function signedUrl(string $file, string $name = 's3_fcbh', string $bucket = 'dbp_dev', int $expiry = 5)
	{
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

}