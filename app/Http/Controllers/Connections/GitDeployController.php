<?php

namespace App\Http\Controllers\Connections;

use App\Http\Controllers\APIController;
use Symfony\Component\Process\Process;

class GitDeployController extends APIController
{

	public function deploy()
	{
		$github_payload = request()->getContent();
		$github_hash    = request()->header('X-Hub-Signature');

		$local_hash = 'sha1=' . hash_hmac('sha1', $github_payload, env('APP_DEPLOY_SECRET'), false);
		if (hash_equals($github_hash, $local_hash)) {
			$process = new Process(env('APP_DEPLOY_SCRIPT_PATH'));
			$process->run(function ($buffer) {
				echo $buffer;
			});
		}
	}

}
