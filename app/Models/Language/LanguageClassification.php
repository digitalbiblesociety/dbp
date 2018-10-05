<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Language\LanguageClassification
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="Information regarding alternative language classification systems",
 *     title="Language Codes",
 *     @OA\Xml(name="LanguageClassification")
 * )
 *
 */
class LanguageClassification extends Model
{
	protected $connection = 'dbp';
    protected $table = 'language_classifications';
    protected $fillable = ['language_id', 'classification_id', 'order', 'name'];
    protected $hidden = ['language_id','id'];


	/**
	 *
	 *
	 * @OA\Property(
	 *   title="id",
	 *   description="Incrementing ID for the Language Classification",
	 *   type="integer",
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
	 * @OA\Property(
	 *   title="language_id",
	 *   description="The foreign key matching the incrementing language ID",
	 *   type="integer",
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
	 * @OA\Property(
	 *   title="classification_id",
	 *   description="The foreign key matching the incrementing language ID",
	 *   type="integer",
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
	 * @OA\Property(
	 *   title="order",
	 *   description="Creates an increasing level of specificity for the classification of the language dialect",
	 *   type="integer",
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
	 * @OA\Property(
	 *   title="name",
	 *   description="The name of the classification for the language",
	 *   type="string",
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
	 * @property \Carbon\Carbon $created_at
	 * @method static LanguageClassification whereCreatedAt($value)
	 *
	 */
    protected $created_at;

	/**
	 *
	 * @property \Carbon\Carbon $updated_at
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
