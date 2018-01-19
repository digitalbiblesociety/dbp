<?php

namespace App\Helpers\AWS;

use Illuminate\Support\Facades\Storage;

class Bucket {
	public static function signedUrl(string $file, string $name = 's3_fcbh', string $bucket = 'dbp_dev', int $expiry = 5)
	{
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
		return $base_url.str_replace($bucket.'/','',$request->getUri()->getPath())."?".$request->getUri()->getQuery();
	}

}