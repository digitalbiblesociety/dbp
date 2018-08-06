<?php

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Resource\ResourceTranslation
 *
 * @OA\Schema (
 *     type="object",
 *     description="Resource Translation",
 *     title="Resource Translation",
 *     @OA\Xml(name="ResourceTranslation")
 * )
 *
 * @mixin \Eloquent
 */
class ResourceTranslation extends Model
{
	protected $connection = 'dbp';
    protected $hidden = ['created_at','updated_at', 'resource_id','vernacular','tag','iso'];

	/**
	 *
	 * @OA\Property(
	 *     title="iso",
	 *     description="The iso code for the resource's translations",
	 *     type="string"
	 * )
	 *
	 * @method static ResourceTranslation whereIso($value)
	 * @property string $iso
	 *
	 */
    protected $iso;
	/**
	 *
	 * @OA\Property(
	 *     title="resource_id",
	 *     description="The id for the resource that the translations describe",
	 *     type="string"
	 * )
	 *
	 * @method static ResourceTranslation whereResourceId($value)
	 * @property int $resource_id
	 *
	 */
    protected $resource_id;
	/**
	 *
	 * @OA\Property(
	 *     title="vernacular",
	 *     description="Determines if the current translations being described is in the vernacular of the resource",
	 *     type="boolean"
	 * )
	 *
	 * @method static ResourceTranslation whereVernacular($value)
	 * @property int $vernacular
	 *
	 */
    protected $vernacular;

	/**
	 *
	 * @OA\Property(
	 *     title="tag",
	 *     description="Determines if the current translation being described is an ancillary bit of meta data rather than a title of the resource",
	 *     type="boolean"
	 * )
	 *
	 * @method static ResourceTranslation whereTag($value)
	 * @property int $tag
	 *
	 */
    protected $tag;

	/**
	 *
	 * @OA\Property(
	 *     title="title",
	 *     description="Serves as the title of the current translation or the name of the tag",
	 *     type="string",
	 *     maxLength=191,
	 *     example="Understanding Biblical Hebrew Verb Forms"
	 * )
	 *
	 * @method static ResourceTranslation whereTitle($value)
	 * @property string $title
	 *
	 */
    protected $title;

	/**
	 *
	 * @OA\Property(
	 *     title="description",
	 *     description="Serves as the description of the current translation",
	 *     type="string",
	 *     maxLength=191,
	 *     example="Understanding Biblical Hebrew Verb Forms"
	 * )
	 *
	 * @method static ResourceTranslation whereDescription($value)
	 * @property string|null $description
	 *
	 */
    protected $description;
	/**
	 *
	 * @method static ResourceTranslation whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	 *
	 */
    protected $created_at;
	/**
	 *
	 * @method static ResourceTranslation whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 *
	 */
    protected $updated_at;

}
