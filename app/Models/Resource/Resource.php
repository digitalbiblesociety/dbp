<?php

namespace App\Models\Resource;

use App\Models\Language\Language;
use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Resource\Resource
 *
 * @mixin \Eloquent
 * @property-read ResourceLink[] $links
 * @property-read ResourceLink[] $translations
 * @property-read Organization $organization
 * @property-read ResourceTranslation $currentTranslation
 *
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
 *
 * @method static Resource whereId($value)
 * @method static Resource whereIso($value)
 * @method static Resource whereOrganizationId($value)
 * @method static Resource whereSourceId($value)
 * @method static Resource whereCover($value)
 * @method static Resource whereCoverThumbnail($value)
 * @method static Resource whereDate($value)
 * @method static Resource whereType($value)
 * @method static Resource whereCreatedAt($value)
 * @method static Resource whereUpdatedAt($value)
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
    public $table = 'resources';

    protected static $rules = [
        'unicode_pdf'         => 'url|nullable',
        'slug'                => 'required|unique:dbp.resources,slug|string|maxLength:191|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
        'language_id'         => 'required|exists:dbp.languages,id',
        'organization_id'     => 'required|exists:dbp.organizations,id',
        'source_id'           => 'string|maxLength:191',
        'cover'               => 'string|maxLength:191',
        'cover_thumbnail'     => 'string|maxLength:191',
        'date'                => 'date',
        'type'                => 'string',
        'translations.*.name' => 'required|unique:dbp.resource_translations,title|maxLength:191',
        'translations.*.tag'  => 'boolean',
        'links.*.url'         => 'required|url',
        'links.*.title'       => 'string|maxLength:191'
    ];

    /**
     *
     * @OA\Property(
     *     title="id",
     *     description="The Resource's incrementing id",
     *     type="integer",
     *     minimum=0
     * )
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
     */
    protected $iso;

    /**
     *
     * @OA\Property(ref="#/components/schemas/Organization/properties/id")
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
     */
    protected $type;

    public function language()
    {
        return $this->belongsTo(Language::class);
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
        return $this->hasMany(ResourceTranslation::class)->where('tags', 1);
    }

    public function currentTranslation()
    {
        return $this->hasOne(ResourceTranslation::class)->where('language_id', $GLOBALS['i18n_id'])->where('tag', 0);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

}
