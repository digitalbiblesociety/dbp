<?php

namespace App\Models\Resource;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Resource\Resource
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $iso
 * @property int $organization_id
 * @property string|null $source_id
 * @property string|null $cover
 * @property string|null $cover_thumbnail
 * @property string|null $date
 * @property string $type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resource\ResourceLink[] $links
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Resource\ResourceLink[] $translations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\Resource whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\Resource whereCoverThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\Resource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\Resource whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\Resource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\Resource whereIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\Resource whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\Resource whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\Resource whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Resource\Resource whereUpdatedAt($value)
 * @property-read \App\Models\Organization\Organization $organization
 * @property-read \App\Models\Resource\ResourceTranslation $currentTranslation
 */
class Resource extends Model
{

	protected $hidden = ['created_at','updated_at'];

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
