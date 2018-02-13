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
		    case "jQueryDataTable": return $this->transformForDataTables($project);
		    case "4":
		    default: return $this->transformForV4($project);
	    }
    }

	public function transformForDataTables($project)
	{
		return [
			"<a href='/dashboard/projects/$project->id'>$project->name</a>",
			$project->url_avatar_icon,
			$project->url_site
		];
	}

	public function transformForV4($project)
	{
		return [
			"id"              => $project->id,
            "name"            => $project->name,
            "url_avatar"      => $project->url_avatar,
            "url_avatar_icon" => $project->url_avatar_icon,
            "url_site"        => $project->url_site,
            "description"     => $project->description,
            "created_at"      => $project->created_at->toDateTimeString(),
            "updated_at"      => $project->updated_at->toDateTimeString(),
            "members"         => $project->members->pluck('name','id')
		];
	}

}
