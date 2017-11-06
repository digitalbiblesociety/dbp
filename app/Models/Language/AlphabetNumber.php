<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Language\AlphabetNumber
 *
 * @property int $id
 * @property string $script_id
 * @property string|null $script_variant_iso
 * @property int $numeral
 * @property string $numeral_vernacular
 * @property string $numeral_written
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetNumber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetNumber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetNumber whereNumeral($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetNumber whereNumeralVernacular($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetNumber whereNumeralWritten($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetNumber whereScriptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetNumber whereScriptVarientIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language\AlphabetNumber whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AlphabetNumber extends Model
{
    protected $table = "alphabet_numbers";
    protected $hidden = ["created_at","updated_at","id"];
    protected $fillable = [
    	"script_id",
	    "numeral",
	    "numeral_vernacular",
	    "numeral_written"
    ];

}
