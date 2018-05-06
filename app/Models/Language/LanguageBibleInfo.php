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
     *
     * @OAS\Property(
     *     title="language_id",
     *     format="integer",
     *     description="The foreign key matching the incrementing language ID",
     *     minimum=0
     * )
     *
     * @property int $language_id
     * @method static LanguageBibleInfo whereLanguageId($value)
     *
    */
	protected $language_id;

     /**
      *
      * @OAS\Property(
      *     title="bible_status",
      *     format="integer",
      *     description="The numeral written out the vernacular translations",
      *     nullable=true,
      *     minimum=0
      * )
      *
      * @property int|null $bible_status
      * @method static LanguageBibleInfo whereBibleStatus($value)
      *
     */
	protected $bible_status;

    /**
     *
     *
     * @OAS\Property(
     *     title="bible_translation_need",
     *     format="integer",
     *     description="The numeral written out the vernacular translations",
     *     nullable=true,
     *     minimum=0
     * )
     *
     * @property int|null $bible_translation_need
     * @method static LanguageBibleInfo whereBibleTranslationNeed($value)
     *
	*/
	protected $bible_translation_need;

	/**
     *
	 *
	 * @OAS\Property(
	 *     title="bible_year",
	 *     format="integer",
	 *     description="The year a full Bible was published",
	 *     minimum=0,
	 *     nullable=true
	 * )
     *
	 * @property int|null $bible_year
	 * @method static LanguageBibleInfo whereBibleYear($value)
	 *
	*/
	protected $bible_year;
     /**
      *
      * @OAS\Property(
      *     title="bible_year_newTestament",
      *     format="integer",
      *     description="The year a new testament Bible was published",
      *     minimum=0,
      *     nullable=true
      * )
      *
      * @property int|null $bible_year_newTestament
      * @method static LanguageBibleInfo whereBibleYearNewTestament($value)
      *
	*/
	protected $bible_year_newTestament;
     /**
      *
      * @OAS\Property(
      *     title="bible_year_portions",
      *     format="integer",
      *     description="The year portions of a Bible were published",
      *     minimum=0,
      *     nullable=true
      * )
      *
      * @property int|null $bible_year_portions
      * @method static LanguageBibleInfo whereBibleYearPortions($value)
      *
    */
	protected $bible_year_portions;

	/**
	 *
	 *
	 * @OAS\Property(
	 *     title="bible_sample_text",
	 *     format="string",
	 *     description="A selection of sample text",
	 *     maxLength=191,
	 *     nullable=true
	 * )
	 *
	 * @property string|null $bible_sample_text
	 * @method static LanguageBibleInfo whereBibleSampleText($value)
	 *
    */
	protected $bible_sample_text;

     /**
      *
      *
      * @OAS\Property(
      *     title="bible_sample_img",
      *     format="string",
      *     description="A sample image of the bible text for comparison",
      *     maxLength=191,
      *     nullable=true
      * )
      *
      * @property string|null $bible_sample_img
      * @method static LanguageBibleInfo whereBibleSampleImg($value)
      *
    */
	protected $bible_sample_img;
	/**
	 *
	 * @OAS\Property(
	 *     title="created_at",
	 *     format="string",
	 *     description="The timestamp for the creation of the language bible information model",
	 *     maxLength=191,
	 *     nullable=true
	 * )
	 *
	 * @property \Carbon\Carbon|null $created_at
	 * @method static LanguageBibleInfo whereCreatedAt($value)
	 *
	*/
	protected $created_at;

     /**
      *
      * @OAS\Property(
      *     title="updated_at",
      *     format="string",
      *     description="The timestamp of the last update for the language bible information model",
      *     maxLength=191,
      *     nullable=true
      * )
      *
      * @property \Carbon\Carbon|null $updated_at
      * @method static LanguageBibleInfo whereUpdatedAt($value)
      *
    */
	protected $updated_at;

}
