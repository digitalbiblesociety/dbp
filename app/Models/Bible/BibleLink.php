<?php

namespace App\Models\Bible;

use App\Models\Organization\Organization;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleLink
 *
 * @property int $id
 * @property string|null $bible_id
 * @property string $type
 * @property string $url
 * @property string $title
 * @property int|null $organization_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string|null $provider
 * @property-read \App\Models\Organization\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleLink whereBibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleLink whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleLink whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleLink whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleLink whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleLink whereUrl($value)
 * @mixin \Eloquent
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
     * The Organization who Provides that link [not necessarily the publisher]
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function organization()
    {
        return $this->HasOne(Organization::class, 'id');
    }
}