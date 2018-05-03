<?php

namespace App\Models\Bible;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleLink
 *
 * @property-read \App\Models\Organization\Organization $organization
 * @mixin \Eloquent
 * @method static BibleLink whereBibleId($value)
 * @method static BibleLink whereCreatedAt($value)
 * @method static BibleLink whereId($value)
 * @method static BibleLink whereOrganizationId($value)
 * @method static BibleLink whereProvider($value)
 * @method static BibleLink whereTitle($value)
 * @method static BibleLink whereType($value)
 * @method static BibleLink whereUpdatedAt($value)
 * @method static BibleLink whereUrl($value)
 */
class BibleLink extends Model
{
    /**
     * BibleLinks will only be called from the Bibles Model. So we don't need ID or Abbr.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at','id','abbr'];
    /**
     * Values the User can Edit
     *
     * @var array
     */
    protected $fillable = ['link', 'type', 'organization_id','url','title'];


    /**
	 *
	 *
	 * @method static BibleLink whereId($value)
	 * @property int $id
	 */
	protected $id;

	/**
	 *
	 *
	 * @method static BibleLink whereBibleId($value)
	 * @property string|null $bible_id
	 */
	protected $bible_id;

	/**
	 *
	 *
	 * @method static BibleLink whereType($value)
	 * @property string $type
	 */
	protected $type;

	/**
	 *
	 *
	 * @method static BibleLink whereUrl($value)
	 * @property string $url
	 */
	protected $url;

	/**
	 *
	 *
	 * @method static BibleLink whereTitle($value)
	 * @property string $title
	 */
	protected $title;

	/**
	 *
	 *
	 * @method static BibleLink whereOrganizationId($value)
	 * @property int|null $organization_id
	 */
	protected $organization_id;

	/**
	 *
	 *
	 * @method static BibleLink whereCreatedAt($value)
	 * @property \Carbon\Carbon $created_at
	 */
	protected $created_at;

	/**
	 *
	 *
	 * @method static BibleLink whereUpdatedAt($value)
	 * @property \Carbon\Carbon $updated_at
	 */
	protected $updated_at;

	/**
	 *
	 *
	 * @method static BibleLink whereProvider($value)
	 * @property string|null $provider
	 */
	protected $provider;

    /**
     * The Organization who Provides that link [not necessarily the publisher]
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function organization()
    {
        return $this->HasOne(Organization::class, 'id');
    }
}