<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

use App\Models\Organization\Organization;

/**
 * App\Models\Bible\BibleEquivalent
 *
 * @property string $bible_id
 * @property string|null $bible_variation_id
 * @property string $equivalent_id
 * @property int $organization_id
 * @property string|null $type
 * @property string|null $site
 * @property string $suffix
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\Bible $bible
 * @property-read \App\Models\Organization\Organization $organization
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleEquivalent whereBibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleEquivalent whereBibleVariationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleEquivalent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleEquivalent whereEquivalentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleEquivalent whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleEquivalent whereSite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleEquivalent whereSuffix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleEquivalent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleEquivalent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BibleEquivalent extends Model
{
    protected $table = "bible_equivalents";
    protected $primaryKey = 'equivalent_id';
    protected $hidden = ['created_at','updated_at','bible_id'];
    protected $fillable = ['bible_id','equivalent_id','organization_id','type','suffix'];
    public $incrementing = false;

    public function bible()
    {
        return $this->BelongsTo(Bible::class,'bible_id','id');
    }

    public function organization()
    {
        return $this->HasOne(Organization::class,'id','organization_id');
    }

}
