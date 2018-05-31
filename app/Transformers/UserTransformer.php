<?php

namespace App\Transformers;

use App\Models\User\User;

class UserTransformer extends BaseTransformer
{

	public function transform(User $user)
	{
		switch ($this->version) {
			case "2":
			case "3": return $this->transformForV2($user);
			case "4":
			default: return $this->transformForV4($user);
		}
	}

	public function transformForV4($user)
	{
		switch($this->route) {

			/**
			 * @OAS\Schema (
			*	type="array",
			*	schema="v4_user_index",
			*	description="The v4 user index response",
			*	title="v4_user_index",
			*	@OAS\Xml(name="v4_user_index"),
			*	@OAS\Items(              @OAS\Property(property="id",       ref="#/components/schemas/User/properties/id"),
			 *              @OAS\Property(property="name",     ref="#/components/schemas/User/properties/name"),
			 *              @OAS\Property(property="nickname", ref="#/components/schemas/User/properties/nickname"),
			 *              @OAS\Property(property="avatar",   ref="#/components/schemas/User/properties/avatar"),
			 *              @OAS\Property(property="email",    ref="#/components/schemas/User/properties/email")
			 *     )
			 *   )
			 * )
			 */
			case "v4_user.index": {
				return [
					'id'        => $user->id,
					'name'      => $user->name,
					'nickname'  => $user->nickname,
					'avatar'    => $user->avatar,
					'email'     => $user->email
				];
			}

		}
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
