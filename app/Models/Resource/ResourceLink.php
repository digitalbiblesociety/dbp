<?php

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Resource\ResourceLink
 *
 * @property int $resource_id
 * @property string $title
 * @property string|null $size
 * @property string $type
 * @property string $url
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @method static ResourceLink whereResourceId($value)
 * @method static ResourceLink whereTitle($value)
 * @method static ResourceLink whereSize($value)
 * @method static ResourceLink whereType($value)
 * @method static ResourceLink whereUrl($value)
 * @method static ResourceLink whereCreatedAt($value)
 * @method static ResourceLink whereUpdatedAt($value)
 *
 * @OA\Schema (
 *     type="object",
 *     description="ResourceLink",
 *     title="Resource Link",
 *     @OA\Xml(name="ResourceLink")
 * )
 *
 * @mixin \Eloquent
 */
class ResourceLink extends Model
{
	protected $connection = 'dbp';
	protected $hidden = ['created_at','updated_at', 'resource_id'];

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Resource/properties/id")
	 *
	 */
	protected $resource_id;

	/**
	 *
	 * @OA\Property(
	 *   title="type",
	 *   type="string",
	 *   description="The type of media the resource can be categorized as",
	 *   nullable=true
	 * )
	 *
	 */
	protected $title;

	/**
	 *
	 * @OA\Property(
	 *   title="size",
	 *   type="string",
	 *   description="The size of the resource measured in kilobytes",
	 *   nullable=true
	 * )
	 *
	 */
	protected $size;

	/**
	 *
	 * @OA\Property(
	 *   title="type",
	 *   type="string",
	 *   description="The destination type for the url",
	 *   nullable=true
	 * )
	 *
	 */
	protected $type;

	/**
	 *
	 * @OA\Property(
	 *   title="url",
	 *   type="string",
	 *   description="The link for the url"
	 * )
	 *
	 */
	protected $url;

	public function resource()
	{
		return $this->belongsTo(Resource::class);
	}

}
