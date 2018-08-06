<?php

namespace App\Models\Resource;

use App\Models\Language\Language;
use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Resource\Resource
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resource\ResourceLink[] $links
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resource\ResourceLink[] $translations
 * @property-read \App\Models\Organization\Organization $organization
 * @property-read \App\Models\Resource\ResourceTranslation $currentTranslation
 *
 * @OA\Schema (
 *     type="object",
 *     description="Resource",
 *     title="Resource",
 *     @OA\Xml(name="Resource")
 * )
 *
 */
class Resource extends Model
{
	protected $connection = 'dbp';
	protected $hidden = ['created_at','updated_at'];
	public $table = "resources";

	/**
	 *
	 * @OA\Property(
	 *     title="id",
	 *     description="The Resource's incrementing id",
	 *     type="integer",
	 *     minimum=0
	 * )
	 *
	 * @method static Resource whereId($value)
	 * @property int $id
	 *
	 */
	protected $id;

	/**
	 *
	 * @OA\Property(
	 *     title="iso",
	 *     description="The Resource's iso",
	 *     type="string",
	 *     minLength=3
	 * )
	 *
	 * @method static Resource whereIso($value)
	 * @property string $iso
	 *
	 */
	protected $iso;

	/**
	 *
	 * @OA\Property(ref="#/components/schemas/Organization/properties/id")
	 *
	 * @method static Resource whereOrganizationId($value)
	 * @property int $organization_id
	 *
	 */
	protected $organization_id;

	/**
	 *
	 * @OA\Property(
	 *   title="source_id",
	 *   type="string",
	 *   description="The owning organization's tracking id for the resource",
	 *   nullable=true
	 * )
	 *
	 * @method static Resource whereSourceId($value)
	 * @property string|null $source_id
	 *
	 */
	protected $source_id;
	/**
	 *
	 * @OA\Property(
	 *   title="cover",
	 *   type="string",
	 *   description="The url to the main cover art for the resource",
	 *   nullable=true
	 * )
	 *
	 * @method static Resource whereCover($value)
	 * @property string|null $cover
	 *
	 */
	protected $cover;

	/**
	 *
	 * @OA\Property(
	 *   title="cover_thumbnail",
	 *   type="string",
	 *   description="The url to the thumbnail cover art for the resource",
	 *   nullable=true
	 * )
	 *
	 * @method static Resource whereCoverThumbnail($value)
	 * @property string|null $cover_thumbnail
	 *
	 */
	protected $cover_thumbnail;

	/**
	 *
	 * @OA\Property(
	 *   title="date",
	 *   type="string",
	 *   description="The date the resource was originally published",
	 *   nullable=true
	 * )
	 *
	 * @method static Resource whereDate($value)
	 * @property string|null $date
	 *
	 */
	protected $date;

	/**
	 *
	 * @OA\Property(
	 *   title="type",
	 *   type="string",
	 *   description="The type of media the resource can be categorized as",
	 *   nullable=true
	 * )
	 *
	 * @method static Resource whereType($value)
	 * @property string $type
	 *
	 */
	protected $type;

	/**
	 *
	 * @method static Resource whereCreatedAt($value)
	 * @property \Carbon\Carbon|null $created_at
	 *
	 */
	protected $created_at;

	/**
	 *
	 * @method static Resource whereUpdatedAt($value)
	 * @property \Carbon\Carbon|null $updated_at
	 *
	 */
	protected $updated_at;

	public function language()
	{
		return $this->BelongsTo(Language::class);
	}

    public function links()
    {
    	return $this->hasMany(ResourceLink::class);
    }

	public function translations()
	{
		return $this->hasMany(ResourceTranslation::class);
	}

	public function tags()
	{
		return $this->HasMany(ResourceTranslation::class)->where('tags',1);
	}

	public function currentTranslation()
	{
		return $this->hasOne(ResourceTranslation::class)->where('language_id',$GLOBALS['i18n_id'])->where('tag',0);
	}

	public function organization()
	{
		return $this->BelongsTo(Organization::class);
	}

}
