<?php

namespace App\Models\Bible\Study;

use App\Models\Bible\Bible;
use App\Models\Bible\Book;
use App\Bible\VerseReference;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\Study\GlossaryPersonName
 * @mixin \Eloquent
 *
 * @OA\Schema (
 *     type="object",
 *     description="GlossaryPersonName",
 *     title="GlossaryPersonName",
 *     @OA\Xml(name="GlossaryPersonName")
 * )
 *
 */
class GlossaryPersonName extends Model
{

    protected $connection = 'dbp';

    protected $fillable = ['id', 'glossary_person_id', 'extended_strongs', 'vernacular'];


    protected $id;

    /**
     *
     * @OA\Property(
     *   title="person_id",
     *   type="string",
     *   description="The unique ID for the commentary, for example the English Treasure of Scripture Knowledge id is ENGTSK",
     *   minLength=6,
     *   maxLength=12,
     *   example="ENGTSK"
     * )
     */
    protected $person_id;


    protected $extended_strongs;


    protected $vernacular;

    public function translations()
    {
        return $this->belongsToMany(Bible::class, 'glossary_person_translations')->withPivot('name');
    }

    public function verseReference()
    {
        return $this->hasOne(VerseReference::class);
    }

}
