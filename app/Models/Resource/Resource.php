<?php

namespace App\Models\Resource;

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
 * @OAS\Schema (
 *     type="object",
 *     description="Resource",
 *     title="Resource",
 *     @OAS\Xml(name="Resource")
 * )
 *
 */
class Resource extends Model
{

	protected $hidden = ['created_at','updated_at'];

	/**
	 *
	 * @OAS\Property(
	 *     title="id",
	 *     description="The Resource's incrementing id",
	 *     format="int",
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
	 * @OAS\Property(
	 *     title="iso",
	 *     description="The Resource's iso",
	 *     format="string",
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
	 * @OAS\Property(ref="#/components/schemas/Organization/properties/id")
	 *
	 * @method static Resource whereOrganizationId($value)
	 * @property int $organization_id
	 *
	 */
	protected $organization_id;

	/**
	 *
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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


    public function links()
    {
    	return $this->hasMany(ResourceLink::class);
    }

	public function translations()
	{
		return $this->hasMany(ResourceTranslation::class);
	}

	public function currentTranslation()
	{
		return $this->hasOne(ResourceTranslation::class)->where('iso',\i18n::getCurrentLocale())->where('tag',0);
	}

	public function organization()
	{
		return $this->BelongsTo(Organization::class);
	}

}
