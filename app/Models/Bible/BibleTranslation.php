<?php

namespace App\Models\Bible;

use App\Models\Language\Language;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleTranslation
 *
 * @property int $id
 * @property string $iso
 * @property string $bible_id
 * @property string|null $bible_variation_id
 * @property int $vernacular
 * @property string $name
 * @property string|null $type
 * @property string|null $features
 * @property string|null $description
 * @property string|null $notes
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\Bible $bible
 * @property-read \App\Models\Language\Language $language
 * @mixin \Eloquent
 * @method static BibleTranslation whereBibleId($value)
 * @method static BibleTranslation whereBibleVariationId($value)
 * @method static BibleTranslation whereCreatedAt($value)
 * @method static BibleTranslation whereDescription($value)
 * @method static BibleTranslation whereFeatures($value)
 * @method static BibleTranslation whereId($value)
 * @method static BibleTranslation whereIso($value)
 * @method static BibleTranslation whereName($value)
 * @method static BibleTranslation whereNotes($value)
 * @method static BibleTranslation whereType($value)
 * @method static BibleTranslation whereUpdatedAt($value)
 * @method static BibleTranslation whereVernacular($value)
 * @property int $vernacular_trade
 * @method static BibleTranslation whereVernacularTrade($value)
 *
 * @OA\Schema (
 *     type="object",
 *     description="BibleTranslation",
 *     title="BibleTranslation",
 *     @OA\Xml(name="BibleTranslation")
 * )
 *
 */
class BibleTranslation extends Model
{
    protected $connection = 'dbp';
    protected $hidden = ['created_at','updated_at','bible_id','id','notes','pivot','language'];
    protected $fillable = ['name','description','bible_id','iso'];

    /**
     *
     * @OA\Property(
     *   title="id",
     *   type="string",
     *   description=""
     * )
     *
     * @method static BibleTranslation whereId($value)
     * @property increments $id
     */
    protected $id;
    /**
     *
     * @OA\Property(
     *   title="iso",
     *   type="string",
     *   description=""
     * )
     *
     * @method static BibleTranslation whereIso($value)
     * @property char $iso
     */
    protected $iso;
    /**
     *
     * @OA\Property(
     *   title="iso",
     *   type="string",
     *   description=""
     * )
     *
     * @method static BibleTranslation whereIso($value)
     * @property foreign $iso
     */
    protected $bible_id;
    /**
     *
     * @OA\Property(
     *   title="bible_id",
     *   type="string",
     *   description=""
     * )
     *
     * @method static BibleTranslation whereBibleId($value)
     * @property string $bible_id
     */
    protected $vernacular;
    /**
     *
     * @OA\Property(
     *   title="bible_id",
     *   type="string",
     *   description=""
     * )
     *
     * @method static BibleTranslation whereVernacular($value)
     * @property foreign $bible_id
     */
    protected $vernacular_trade;
    /**
     *
     * @OA\Property(
     *   title="vernacular",
     *   type="string",
     *   description=""
     * )
     *
     * @method static BibleTranslation whereVernacularTrade($value)
     * @property boolean $vernacular
     */
    protected $name;
    /**
     *
     * @OA\Property(
     *   title="vernacular_trade",
     *   type="string",
     *   description=""
     * )
     *
     * @method static BibleTranslation whereName($value)
     * @property boolean $vernacular_trade
     */
    protected $type;
    /**
     *
     * @OA\Property(
     *   title="name",
     *   type="string",
     *   description=""
     * )
     *
     * @method static BibleTranslation whereType($value)
     * @property string $name
     */
    protected $features;
    /**
     *
     * @OA\Property(
     *   title="type",
     *   type="string",
     *   description=""
     * )
     *
     * @method static BibleTranslation whereFeatures($value)
     * @property string $type
     */
    protected $description;
    /**
     *
     * @OA\Property(
     *   title="features",
     *   type="string",
     *   description=""
     * )
     *
     * @method static BibleTranslation where($value)
     * @property string $features
     */
    protected $notes;

    public function getIsoAttribute()
    {
        return $this->language->iso;
    }

    public function bible()
    {
        return $this->belongsTo(Bible::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class)->select(['iso','id']);
    }
}
