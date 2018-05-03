<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

/**
 * App\Models\Language\LanguageClassification
 *
 * @OAS\Schema (
 *     type="object",
 *     description="Information regarding language classifications",
 *     title="Language Bible Classification",
 *     @OAS\Xml(name="LanguageBibleInfo")
 * )
 *
 * @mixin \Eloquent
 */
class LanguageClassification extends Model
{

    protected $table = 'language_classifications';
    protected $fillable = ['language_id', 'classification_id', 'order', 'name'];
    protected $hidden = ['language_id','id'];



	/**
	 *
	 * @property int $id
	 * @method static LanguageClassification whereId($value)
	 *
	 * @OAS\Property(
	 *     title="Incrementing ID",
	 *     description="Incrementing ID for the Language Classification",
	 *     format="integer",
	 *     minimum=0
	 * )
	 *
	 */
    protected $id;

	/**
	 *
	 * @property int $language_id
	 * @method static LanguageClassification whereLanguageId($value)
	 *
	 * @OAS\Property(
	 *     title="Language Classification ID",
	 *     description="The foreign key matching the incrementing language ID",
	 *     format="integer",
	 *     minimum=0
	 * )
	 *
	 */
    protected $language_id;

	/**
	 *
	 * @property string $classification_id
	 * @method static LanguageClassification whereClassificationId($value)
	 *
	 * @OAS\Property(
	 *     title="Language Classification ID",
	 *     description="The foreign key matching the incrementing language ID",
	 *     format="integer",
	 *     minimum=0
	 * )
	 *
	 */
    protected $classification_id;

	/**
	 *
	 * @property int $order
	 * @method static LanguageClassification whereOrder($value)
	 *
	 * @OAS\Property(
	 *     title="Language Order ID",
	 *     description="Creates an increasing level of specificity for the classification of the language dialect",
	 *     format="integer",
	 *     minimum=0
	 * )
	 *
	 */
    protected $order;

	/**
	 *
	 * @property string $name
	 * @method static LanguageClassification whereName($value)
	 *
	 * @OAS\Property(
	 *     title="Language Name",
	 *     description="The name of the classification for the language",
	 *     format="string",
	 *     example="Afro-Asiatic"
	 * )
	 *
	 */
    protected $name;

	/**
	 *
	 * @property Carbon $created_at
	 * @method static LanguageClassification whereCreatedAt($value)
	 *
	 * @OAS\Property(
	 *     title="Language created_at",
	 *     description="The timestamp at which the language classification was created",
	 *     format="string",
	 *     example="Afro-Asiatic"
	 * )
	 *
	 */
    protected $created_at;

	/**
	 *
	 * @property Carbon $updated_at
	 * @method static LanguageClassification whereUpdatedAt($value)
	 *
	 * @OAS\Property(
	 *     title="Language created_at",
	 *     description="The timestamp at which the language classification was updated",
	 *     format="string"
	 * )
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
