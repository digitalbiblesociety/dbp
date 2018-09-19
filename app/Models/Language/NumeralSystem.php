<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
/**
 *
 * @OA\Schema (
 *     type="object",
 *     description="NumeralSystem",
 *     title="Numeral System",
 *     @OA\Xml(name="NumeralSystem")
 * )
 *
 */
class NumeralSystem extends Model
{
	protected $connection = 'dbp';
	protected $table = 'numeral_systems';
	public $incrementing = false;
	protected $keyType = 'string';
	protected $hidden = ['created_at','updated_at'];

	/**
	 * @property string id
	 * @method static Builder|NumeralSystem whereId($value)
	 *
	 * @OA\Property(
	 *     title="Numeral System Id",
	 *     description="The url to the font file",
	 *     type="string",
	 *     maxLength=20
	 * )
	 *
	 */
	protected $numeral_system_id;

	/**
	 * @property string numeral
	 * @method static Builder|NumeralSystem whereNumeral($value)
	 *
	 * @OA\Property(
	 *     title="The integer value of the glyph",
	 *     description="The url to the font file",
	 *     type="integer"
	 * )
	 *
	 */
	protected $value;
	/**
	 * @property string numeral_vernacular
	 * @method static Builder|NumeralSystem whereNumeralVernacular($value)
	 *
	 * @OA\Property(
	 *     title="The Vernacular Numeral",
	 *     description="The numeral in the vernacular of the writing system",
	 *     type="string",
	 *     maxLength=12
	 * )
	 *
	 */
	protected $glyph;
	/**
	 * @property string numeral_written
	 * @method static Builder|NumeralSystem whereNumeralWritten($value)
	 *
	 * @OA\Property(
	 *     title="Alphabet Numeral Written",
	 *     description="The word for the numeral in the vernacular of the writing system",
	 *     type="string",
	 *     maxLength=24
	 * )
	 *
	 */
	protected $numeral_written;

	public function alphabets()
	{
		return $this->hasManyThrough(Alphabet::class,AlphabetNumeralSystem::class,'numeral_system_id','script','id','script_id')->select('script','name');
	}

	public function numerals()
	{
		return $this->hasMany(NumeralSystemGlyph::class,'numeral_system_id','id');
	}


}
