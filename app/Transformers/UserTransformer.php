<?php

namespace App\Transformers;

class UserTransformer extends BaseTransformer
{

	public function transform($user)
	{
		switch ($this->version) {
			case 2:
			case 3: return $this->transformForV2($user);
			case 4:
			default: return $this->transformForV4($user);
		}
	}

	public function transformForV4($user)
	{
		switch($this->route) {
			/**
			 * @OA\Schema (
			*	type="array",
			*	schema="v4_user_index",
			*	description="The v4 user index response",
			*	title="v4_user_index",
			*	@OA\Xml(name="v4_user_index"),
			*	@OA\Items(              @OA\Property(property="id",       ref="#/components/schemas/User/properties/id"),
			 *              @OA\Property(property="name",     ref="#/components/schemas/User/properties/name"),
			 *              @OA\Property(property="nickname", ref="#/components/schemas/User/properties/nickname"),
			 *              @OA\Property(property="avatar",   ref="#/components/schemas/User/properties/avatar"),
			 *              @OA\Property(property="email",    ref="#/components/schemas/User/properties/email")
			 *     )
			 *   )
			 * )
			 */
			case 'v4_user.index': {
				return [
					'id'        => $user->id,
					'name'      => $user->name,
					'email'     => $user->email
				];
			}

			case "v4_user.show": {
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
