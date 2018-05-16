<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Alphabet;

/**
 * App\Models\Language\AlphabetFont
 *
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="Alphabet Font",
 *     title="Alphabet Font",
 *     @OAS\Xml(name="AlphabetFont")
 * )
 *
 */
class AlphabetFont extends Model
{
    protected $table = 'alphabet_fonts';
    protected $hidden = ['iso'];

	/*
	* @property int $id
	* @method static AlphabetFont whereId($value)
	*/
	protected $id;

	/*
	* @property string $script_id
	* @method static AlphabetFont whereScriptId($value)
	*/
	protected $script_id;

	/**
	* @property string $fontName
	* @method static AlphabetFont whereFontName($value)
	*
	* @OAS\Property(
	*     title="Alphabet Font Name",
	*     description="The Font Name",
	*     format="string",
	*     maxLength=191,
	*     example="Noto Naskh Arabic"
	* )
	*
	*/
	protected $fontName;

	/**
	* @property string $fontName
	* @method static AlphabetFont whereFontName($value)
	*
	* @OAS\Property(
	*     title="Alphabet Font File Name",
	*     description="The File name for the font",
	*     format="string",
	*     maxLength=191,
	*     example="NotoNaskhArabic-Regular"
	* )
	*
	*/
	protected $fontFileName;

	/**
	* @property int|null $fontWeight
	* @method static AlphabetFont whereFontWeight($value)
	*
	* @OAS\Property(
	*     title="Alphabet Font Weight",
	*     description="The boldness of the font",
	*     nullable=true,
	*     format="integer",
	*     minimum=100,
	*     example=400
	* )
	*
	*/
	protected $fontWeight;

	/**
	* @property string|null $copyright
	* @method static AlphabetFont whereCopyright($value)
	*
	* @OAS\Property(
	*     title="Alphabet copyright",
	*     description="The copyright of the font if any",
	*     format="string",
	*     nullable=true,
	*     maxLength=191,
	*     example="Creative Commons"
	* )
	*
	*/
	protected $copyright;

	/**
	* @property string|null $url
	* @method static AlphabetFont whereUrl($value)
	*
	* @OAS\Property(
	*     title="Alphabet URL",
	*     description="The url to the font file",
	*     format="string",
	*     example="https://cdn.example.com/resources/fonts/NotoNaskhArabic-Regular.ttf"
	* )
	*
	*/
	protected $url;

	/**
	* @property string|null $notes
	* @method static AlphabetFont whereNotes($value)
	*
	* @OAS\Property(
	*     title="Notes",
	*     description="Any notes for the font file name",
	*     format="string",
	*     nullable=true
	* )
	*
	*/
	protected $notes;

	/**
	* @property int $italic
	* @method static AlphabetFont whereItalic($value)
	*
	* @OAS\Property(
	*     title="Italic",
	*     description="Determines if the font file contains or supports italics",
	*     format="boolean",
	*     nullable=true,
	*     example=false
	* )
	*
	*/
	protected $italic;

    public function alphabet()
    {
        return $this->belongsTo(Alphabet::class);
    }

}