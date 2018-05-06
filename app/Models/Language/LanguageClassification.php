<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

/**
 * App\Models\Language\LanguageClassification
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="Information regarding alternative language classification systems",
 *     title="Language Codes",
 *     @OAS\Xml(name="LanguageClassification")
 * )
 *
 */
class LanguageClassification extends Model
{

    protected $table = 'language_classifications';
    protected $fillable = ['language_id', 'classification_id', 'order', 'name'];
    protected $hidden = ['language_id','id'];


	/**
	 *
	 *
	 * @OAS\Property(
	 *   title="id",
	 *   description="Incrementing ID for the Language Classification",
	 *   format="integer",
	 *   minimum=0
	 * )
	 *
	 * @property int $id
	 * @method static LanguageClassification whereId($value)
	 *
	 */
	protected $id;

	/**
	 *
	 *
	 * @OAS\Property(
	 *   title="language_id",
	 *   description="The foreign key matching the incrementing language ID",
	 *   format="integer",
	 *   minimum=0
	 * )
	 *
	 * @property int $language_id
	 * @method static LanguageClassification whereLanguageId($value)
	 *
	 */
    protected $language_id;

	/**
	 *
	 *
	 * @OAS\Property(
	 *   title="classification_id",
	 *   description="The foreign key matching the incrementing language ID",
	 *   format="integer",
	 *   minimum=0
	 * )
	 *
	 * @property string $classification_id
	 * @method static LanguageClassification whereClassificationId($value)
	 *
	 */
    protected $classification_id;

	/**
	 *
	 *
	 * @OAS\Property(
	 *   title="order",
	 *   description="Creates an increasing level of specificity for the classification of the language dialect",
	 *   format="integer",
	 *   minimum=0
	 * )
	 *
	 * @property int $order
	 * @method static LanguageClassification whereOrder($value)
	 *
	 */
    protected $order;

	/**
	 *
	 * @OAS\Property(
	 *   title="name",
	 *   description="The name of the classification for the language",
	 *   format="string",
	 *   example="Afro-Asiatic"
	 * )
	 *
	 * @property string $name
	 * @method static LanguageClassification whereName($value)
	 *
	 */
    protected $name;

	/**
	 *
	 * @property Carbon $created_at
	 * @method static LanguageClassification whereCreatedAt($value)
	 *
	 */
    protected $created_at;

	/**
	 *
	 * @property Carbon $updated_at
	 * @method static LanguageClassification whereUpdatedAt($value)
	 *
	 */
    protected $updated_at;

    /*
     * @property-read Language $language
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

}
