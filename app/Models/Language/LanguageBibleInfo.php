<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Language\LanguageBibleInfo
 *
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="Information regarding the publication of bibles in the various languages around the world",
 *     title="Language Bible Info",
 *     @OAS\Xml(name="LanguageBibleInfo")
 * )
 *
 */
class LanguageBibleInfo extends Model
{
    public $incrementing = false;
    public $table = 'language_bibleInfo';

    /**
	 * @property int $language_id
     * @method static LanguageBibleInfo whereLanguageId($value)
     *
     * @OAS\Property(
     *     title="Language ID",
     *     description="The foreign key matching the incrementing language ID",
     *     format="integer",
     *     minimum=0
     * )
     *
    */
	protected $language_id;

     /**
     * @property int|null $bible_status
     * @method static LanguageBibleInfo whereBibleStatus($value)
     *
     * @OAS\Property(
     *     title="Bible Status",
     *     description="The numeral written out the vernacular translations",
     *     format="integer",
     *     minimum=0
     * )
     */
	protected $bible_status;

    /**
     *
     * @property int|null $bible_translation_need
     * @method static LanguageBibleInfo whereBibleTranslationNeed($value)
     *
     * @OAS\Property(
     *     title="Bible Status",
     *     description="The numeral written out the vernacular translations",
     *     format="integer",
     *     minimum=0,
     *     nullable=true
     * )
     *
	*/
	protected $bible_translation_need;

	/**
     *
     * @property int|null $bible_year
     * @method static LanguageBibleInfo whereBibleYear($value)
	 *
	 * @OAS\Property(
	 *     title="Bible Year",
	 *     description="The year a full Bible was published",
	 *     format="integer",
	 *     minimum=0,
	 *     nullable=true
	 * )
     *
	*/
	protected $bible_year;
     /**
      *
      * @property int|null $bible_year_newTestament
      * @method static LanguageBibleInfo whereBibleYearNewTestament($value)
      *
      * @OAS\Property(
      *     title="Bible Year New Testament",
      *     description="The year a new testament Bible was published",
      *     format="integer",
      *     minimum=0,
      *     nullable=true
      * )
      *
	*/
	protected $bible_year_newTestament;
     /**
      * @property int|null $bible_year_portions
      * @method static LanguageBibleInfo whereBibleYearPortions($value)
      *
      * @OAS\Property(
      *     title="Bible Year Portions",
      *     description="The year portions of a Bible were published",
      *     format="integer",
      *     minimum=0,
      *     nullable=true
      * )
      *
      *
    */
	protected $bible_year_portions;

	/**
	 *
	 *
	 * @property string|null $bible_sample_text
	 * @method static LanguageBibleInfo whereBibleSampleText($value)
	 *
	 * @OAS\Property(
	 *     title="Bible Sample Text",
	 *     description="A selection of sample text",
	 *     format="string",
	 *     maxLength=191,
	 *     nullable=true
	 * )
	 *
    */
	protected $bible_sample_text;

     /**
      *
      * @property string|null $bible_sample_img
      * @method static LanguageBibleInfo whereBibleSampleImg($value)
      *
      * @OAS\Property(
      *     title="Bible Sample Image",
      *     description="A sample image of the bible text for comparison",
      *     format="string",
      *     maxLength=191,
      *     nullable=true
      * )
      *
    */
	protected $bible_sample_img;
	/**
	 * @property \Carbon\Carbon|null $created_at
	 * @method static LanguageBibleInfo whereCreatedAt($value)
	 *
	 * @OAS\Property(
	 *     title="Bible Info Creation Timestamp",
	 *     description="The timestamp for the creation of the language bible information model",
	 *     format="string",
	 *     maxLength=191,
	 *     nullable=true
	 * )
	 *
	 *
	*/
	protected $created_at;

     /**
      * @property \Carbon\Carbon|null $updated_at
      * @method static LanguageBibleInfo whereUpdatedAt($value)
      *
      * @OAS\Property(
      *     title="Bible Info Updated Timestamp",
      *     description="The timestamp of the last update for the language bible information model",
      *     format="string",
      *     maxLength=191,
      *     nullable=true
      * )
      *
    */
	protected $updated_at;

}
