<?php

namespace App\Transformers;

use App\Models\User\User;

class UserTransformer extends BaseTransformer
{

	public function transform(User $user)
	{
		switch ($this->version) {
			case "jQueryDataTable": return $this->transformForDataTables($user);
			case "2":
			case "3": return $this->transformForV2($user);
			case "4":
			default: return $this->transformForV4($user);
		}
	}


	public function transformForV4($user)
	{
		//switch($this->route) {}
	}

	public function transformForDataTables($user)
	{
		return [
			"<a href='/dashboard/users/".$user->id."'>".$user->name.'</a>',
			$user->email,
			$user->nickname ?? "",
			$user->organizations->pluck('currentTranslation.name')->implode(','),
		];
	}

}
