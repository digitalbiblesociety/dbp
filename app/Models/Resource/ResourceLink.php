<?php

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Resource\ResourceLink
 *
 * @OAS\Schema (
 *     type="object",
 *     description="ResourceLink",
 *     title="Resource Link",
 *     @OAS\Xml(name="ResourceLink")
 * )
 *
 * @mixin \Eloquent
 */
class ResourceLink extends Model
{
	protected $hidden = ['created_at','updated_at', 'resource_id'];

	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Resource/properties/id")
	 *
	 * @method static ResourceLink whereResourceId($value)
	 * @property int $resource_id
	 *
	 */
	protected $resource_id;

	/**
	 *
	 * @OAS\Property(
	 *   title="type",
	 *   type="string",
	 *   description="The type of media the resource can be categorized as",
	 *   nullable=true
	 * )
	 *
	 * @method static ResourceLink whereTitle($value)
	 * @property string $title
	 *
	 */
	protected $title;

	/**
	 *
	 * @OAS\Property(
	 *   title="size",
	 *   type="string",
	 *   description="The size of the resource measured in kilobytes",
	 *   nullable=true
	 * )
	 *
	 * @method static ResourceLink whereSize($value)
	 * @property string|null $size
	 *
	 */
	protected $size;

	/**
	 *
	 * @OAS\Property(
	 *   title="type",
	 *   type="string",
	 *   description="The destination type for the url",
	 *   nullable=true
	 * )
	 *
	 * @method static ResourceLink whereType($value)
	 * @property string $type
	 *
	 */
	protected $type;

	/**
	 *
	 * @OAS\Property(
	 *   title="url",
	 *   type="string",
	 *   description="The link for the url"
	 * )
	 *
	 * @method static ResourceLink whereUrl($value)
	 * @property string $url
	 *
	 */
	protected $url;

	/**
	 *
	 *
	 * @method static ResourceLink whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	 *
	 */
	protected $created_at;

	/**
	 *
	 * @method static ResourceLink whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 *
	 */
	protected $updated_at;

}
