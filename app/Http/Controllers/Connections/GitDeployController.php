<?php

namespace App\Http\Controllers\Connections;

use App\Http\Controllers\APIController;
use Symfony\Component\Process\Process;

class GitDeployController extends APIController
{

	public function deploy()
	{
		$payload = request()->getContent();
		$hash    = request()->header('X-Hub-Signature');

		$local_hash = 'sha1=' . hash_hmac('sha1', $payload, env('APP_DEPLOY_SECRET'), false);
		if (hash_equals($hash, $local_hash)) {
			$process = new Process(env('APP_DEPLOY_SCRIPT_PATH'));
			$process->run(function ($buffer) {
				echo $buffer;
			});
		}
		return $this->reply('deployment begun');
	}

}
