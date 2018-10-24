<?php

namespace App\Transformers;

use App\Models\User\Project;

class ProjectTransformer extends BaseTransformer
{

	/**
	 * @param Project $project
	 *
	 * @return mixed
	 */
	public function transform(Project $project)
    {
	    switch ($this->version) {
		    case 4:
		    default: return $this->transformForV4($project);
	    }
    }

	/**
	 * @OA\Schema (
			*	type="array",
			*	schema="v4_projects_index",
			*	description="The minimized alphabet return for the all alphabets route",
			*	title="v4_projects_index",
			*	@OA\Xml(name="v4_projects_index"),
			*	@OA\Items(        @OA\Property(property="id",                ref="#/components/schemas/Project/properties/id"),
	 *        @OA\Property(property="name",              ref="#/components/schemas/Project/properties/name"),
	 *        @OA\Property(property="url_avatar",        ref="#/components/schemas/Project/properties/url_avatar"),
	 *        @OA\Property(property="url_avatar_icon",   ref="#/components/schemas/Project/properties/url_avatar_icon"),
	 *        @OA\Property(property="url_site",          ref="#/components/schemas/Project/properties/url_site"),
	 *        @OA\Property(property="description",       ref="#/components/schemas/Project/properties/description"),
	 *        @OA\Property(property="created_at",        ref="#/components/schemas/Project/properties/created_at"),
	 *        @OA\Property(property="updated_at",        ref="#/components/schemas/Project/properties/updated_at"),
	 *        @OA\Property(property="members",           @OA\Schema(type="object"))
	 *     )
	 *   )
	 * )
	 */
	public function transformForV4($project)
	{
		return [
			'id'              => $project->id,
            'name'            => $project->name,
            'url_avatar'      => $project->url_avatar,
            'url_avatar_icon' => $project->url_avatar_icon,
            'url_site'        => $project->url_site,
            'description'     => $project->description,
            'created_at'      => $project->created_at->toDateTimeString(),
            'updated_at'      => $project->updated_at->toDateTimeString()
		];
	}

}
