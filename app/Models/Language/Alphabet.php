<?php

namespace App\Models\Language;

use App\Models\Bible\Bible;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Language\Alphabet
 *
 * @property string $script
 * @property string $name
 * @property string|null $unicode_pdf
 * @property string|null $family
 * @property string|null $type
 * @property string|null $white_space
 * @property string|null $open_type_tag
 * @property bool $complex_positioning
 * @property bool $requires_font
 * @property bool $unicode
 * @property bool $diacritics
 * @property bool $contextual_forms
 * @property bool $reordering
 * @property bool $case
 * @property bool $split_graphs
 * @property string|null $status
 * @property string|null $baseline
 * @property string|null $ligatures
 * @property string|null $direction
 * @property string|null $direction_notes
 * @property string|null $sample
 * @property string|null $sample_img
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\Bible[] $bibles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\AlphabetFont[] $fonts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\Language[] $languages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Language\AlphabetNumber[] $numerals
 * @property-read \App\Models\Language\AlphabetFont $primaryFont
 * @property-read \App\Models\Language\AlphabetFont $regular
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereBaseline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereCase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereComplexPositioning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereContextualForms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereDiacritics($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereDirectionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereLigatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereOpenTypeTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereReordering($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereRequiresFont($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereSampleImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereScript($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereSplitGraphs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereUnicode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereUnicodePdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\Alphabet whereWhiteSpace($value)
 * @mixin \Eloquent
 * 
 * Class Alphabet
 * @OAS\Schema (
 *     type="object",
 *     description="Alphabet",
 *     title="Alphabet",
 *     @OAS\Xml(name="Alphabet")
 * )
 */
class Alphabet extends Model
{

    protected $table = "alphabets";
    protected $primaryKey = 'script';
    public $incrementing = false;

	protected $casts = [
		'complex_positioning' => 'boolean',
		'requires_font'       => 'boolean',
		'unicode'             => 'boolean',
		'diacritics'          => 'boolean',
		'contextual_forms'    => 'boolean',
		'reordering'          => 'boolean',
		'case'                => 'boolean',
		'split_graphs'        => 'boolean',
	];

    protected $hidden = ["created_at","updated_at","directionNotes","requiresFont"];
    protected $fillable = [ "script", "name", "unicode_pdf", "family", "type", "white_space", "open_type_tag", "complex_positioning", "requires_font", "unicode", "diacritics", "contextual_forms", "reordering", "case", "split_graphs", "status", "baseline", "ligatures", "direction", "sample", "sample_img"];

	/**
	 * @OAS\Property(
	 *     title="Alphabet Script",
	 *     description="The Script ID for the alphabet aligning with the iso 15924 standard",
	 *     format="string",
	 *     example="Cans",
	 *     @OAS\ExternalDocumentation(
	 *         description="For more info please refer to the Unicode Consortium",
	 *         url="https://http://www.unicode.org/iso15924/"
	 *     ),
	 * )
	 *
	 * @var string $script
	 */
	protected $script;

	/**
	 * @OAS\Property(
	 *   title="Alphabet Script",
	 *   type="string",
	 *   description="The name of the alphabet in English",
	 *   example="Unified Canadian Aboriginal"
	 * )
	 *
	 * @var string $name
	 */
	protected $name;

	/**
	 * @OAS\Property(
	 *     title="Unicode PDF",
	 *     description="A url to a reference PDF for the alphabet",
	 *     format="string",
	 *     nullable=true,
	 *     example="https://unicode.org/charts/PDF/U1400.pdf"
	 * )
	 *
	 * @var string $unicode_pdf
	 */
	protected $unicode_pdf;

	/**
	 * @OAS\Property(
	 *     title="Family",
	 *     description="The linguistic family the alphabet can be categorized within",
	 *     format="string",
	 *     example="American"
	 * )
	 *
	 * @var string $family
	 */
	protected $family;

	/**
	 * @OAS\Property(
	 *     title="Type",
	 *     description="The type of alphabet be described",
	 *     format="string",
	 *     example="abugida"
	 * )
	 *
	 * @var string $type
	 */
	protected $type;

	/**
	 * @OAS\Property(
	 *     title="White Space",
	 *     description="The usage white space within the alphabet",
	 *     format="string",
	 *     example="between words"
	 * )
	 *
	 * @var string $white_space
	 */
	protected $white_space;

	/**
	 * @OAS\Property(
	 *     title="Open Type Tag",
	 *     description="The open type tag of the alphabet",
	 *     format="string",
	 *     example="cans"
	 * )
	 *
	 * @var string $open_type_tag
	 */
	protected $open_type_tag;

	/**
	 * @OAS\Property(
	 *     title="Open Type Tag",
	 *     description="The open type tag of the alphabet",
	 *     format="string",
	 *     example="no"
	 * )
	 *
	 * @var string $complex_positioning
	 */
	protected $complex_positioning;

	/**
	 * @OAS\Property(
	 *     title="Requires Font",
	 *     description="If the Alphabet generally requires the use of a font to display correctly online",
	 *     format="boolean",
	 *     example=false
	 * )
	 *
	 * @var boolean $requires_font
	 */
	protected $requires_font;

	/**
	 * @OAS\Property(
	 *     title="Unicode",
	 *     description="If the Alphabet is fully supported by the unicode spec",
	 *     format="boolean",
	 *     example=true
	 * )
	 *
	 * @var boolean $unicode
	 */
	protected $unicode;

	/**
	 * @OAS\Property(
	 *     title="Diacritics",
	 *     description="If the Alphabet contains diacritics",
	 *     format="boolean",
	 *     example=true
	 * )
	 *
	 * @var boolean $diacritics
	 */
	protected $diacritics;

	/**
	 * @OAS\Property(
	 *     title="Contextual Forms",
	 *     description="If the Alphabet contains contextual forms",
	 *     format="boolean",
	 *     example=false
	 * )
	 *
	 * @var boolean $contextual_forms
	 */
	protected $contextual_forms;

	/**
	 * @OAS\Property(
	 *     title="Reordering",
	 *     description="If the Alphabet contains reordering",
	 *     format="boolean",
	 *     nullable=true,
	 *     example=false
	 * )
	 *
	 * @var boolean|null $reordering
	 */
	protected $reordering;

	/**
	 * @OAS\Property(
	 *     title="Case",
	 *     description="If the Alphabet utilizes different cases",
	 *     format="boolean",
	 *     nullable=true,
	 *     example=false
	 * )
	 *
	 * @var boolean|null $case
	 */
	protected $case;

	/**
	 * @OAS\Property(
	 *     title="Split Graphs",
	 *     description="If the Alphabet contains letters that are written using two separate distinct elements.",
	 *     format="boolean",
	 *     example=false,
	 *     @OAS\ExternalDocumentation(
	 *         description="For more info please refer to the Script Source Feature Definitions",
	 *         url="http://scriptsource.org/cms/scripts/page.php?item_id=entry_detail&uid=cq3q4pwuah#50d6cb6e"
	 *     ),
	 * )
	 *
	 * @var boolean $split_graphs
	 */
	protected $split_graphs;

	/**
	 * @OAS\Property(
	 *     title="Status",
	 *     description="The status of the alphabet",
	 *     format="string",
	 *     example="Current",
	 *     enum={"Current","Historical","Fictional","Unclear"},
	 *     @OAS\ExternalDocumentation(
	 *         description="For more info please refer to the Script Source Feature Definitions",
	 *         url="http://scriptsource.org/cms/scripts/page.php?item_id=entry_detail&uid=cq3q4pwuah#71259eae"
	 *     ),
	 * )
	 *
	 * @var string $status
	 */
	protected $status;

	/**
	 * @OAS\Property(
	 *     title="Baseline",
	 *     description="The alignment of the text within the alphabet",
	 *     format="string",
	 *     enum={"Hanging","Centered","Bottom","Vertical"},
	 *     example="Bottom",
	 *     @OAS\ExternalDocumentation(
	 *         description="For more info please refer to the Script Source Feature Definitions",
	 *         url="http://scriptsource.org/cms/scripts/page.php?item_id=entry_detail&uid=cq3q4pwuah#a4a32c47"
	 *     ),
	 * )
	 *
	 * @var string $baseline
	 */
	protected $baseline;

	/**
	 * @OAS\Property(
	 *     title="Ligatures",
	 *     description="Indicates on if letters may be or are required to be joined as a single glyph",
	 *     format="string",
	 *     enum={"required","optional","none"},
	 *     example="none",
	 *     @OAS\ExternalDocumentation(
	 *         description="For more information please refer to the Script Source Feature Definitions",
	 *         url="http://scriptsource.org/cms/scripts/page.php?item_id=entry_detail&uid=cq3q4pwuah#3e655409"
	 *     ),
	 *     @OAS\ExternalDocumentation(
	 *         description="For more information about Ligatures",
	 *         url="https://en.wikipedia.org/wiki/Typographic_ligature"
	 *     ),
	 * )
	 *
	 * @var string $ligatures
	 */
	protected $ligatures;

	/**
	 * @OAS\Property(
	 *     title="Direction",
	 *     description="The direction that the alphabet is read",
	 *     format="string",
	 *     enum={"rtl","ltr"},
	 *     example="ltr",
	 *     @OAS\ExternalDocumentation(
	 *         description="For more information please refer to the Script Source Feature Definitions",
	 *         url="http://scriptsource.org/cms/scripts/page.php?item_id=entry_detail&uid=cq3q4pwuah#02674a4e"
	 *     )
	 * )
	 *
	 * @var string $direction
	 */
	protected $direction;

	/**
	 * @OAS\Property(
	 *     title="Sample",
	 *     description="A sample section of text for the alphabet",
	 *     format="string"
	 * )
	 *
	 * @var string $sample
	 */
	protected $sample;

	/**
	 * @OAS\Property(
	 *     title="Sample",
	 *     description="A url to an image of the sample section of text for the alphabet",
	 *     format="string"
	 * )
	 *
	 * @var string $sample_img
	 */
	protected $sample_img;

    public function languages()
    {
        return $this->BelongsToMany(Language::class,'alphabet_language','script_id');
    }

    public function fonts()
    {
        return $this->HasMany(AlphabetFont::class,'script_id','script');
    }

    public function primaryFont()
    {
        return $this->HasOne(AlphabetFont::class,'script_id','script');
    }

    public function regular()
    {
        return $this->HasOne(AlphabetFont::class,'script_id','script')->where('fontWeight',400);
    }

	public function bibles()
	{
		return $this->HasMany(Bible::class,'script','script');
	}

	public function numerals()
	{
		return $this->HasMany(AlphabetNumber::class,'script_id');
	}

}
