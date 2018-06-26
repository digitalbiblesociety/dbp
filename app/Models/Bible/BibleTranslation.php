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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereBibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereBibleVariationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereVernacular($value)
 * @property int $vernacular_trade
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleTranslation whereVernacularTrade($value)
 *
 * @OAS\Schema (
 *     type="object",
 *     description="BibleTranslation",
 *     title="BibleTranslation",
 *     @OAS\Xml(name="BibleTranslation")
 * )
 *
 */
class BibleTranslation extends Model
{
    protected $hidden = ["created_at","updated_at","bible_id","id"];
    protected $fillable = ['name','description','bible_id','iso'];

	/**
	 *
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
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
	 * @OAS\Property(
	 *   title="features",
	 *   type="string",
	 *   description=""
	 * )
	 *
	 * @method static BibleTranslation where($value)
	 * @property string $features
	 */
	protected $notes;

    public function bible()
    {
        return $this->belongsTo(Bible::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

}