<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFileTitle
 *
 * @property-read \App\Models\Bible\BibleFile $file
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="The Bible File Title model communicates information about generalized fileset sizes",
 *     title="BibleFileTitle",
 *     @OAS\Xml(name="BibleFileTitle")
 * )
 *
 */
class BibleFileTitle extends Model
{
    public $table = "bible_file_titles";


	 /*
	  *
	  * @OAS\Property(
	  *   title="file_id",
	  *   type="integer",
	  *   description="The incrementing id of the file timestamp"
	  * )
	  *
	  * @method static BibleFileTitle whereFileId($value)
	  * @property int $file_id
	  *
	  */
	 protected $file_id;
	 /*
	  *
	  * @OAS\Property(
	  *   title="iso",
	  *   type="string",
	  *   description="The incrementing id of the file timestamp"
	  * )
	  *
	  * @method static BibleFileTitle whereIso($value)
	  * @property string $iso
	  *
	  */
	 protected $iso;
	 /*
	  *
	  * @OAS\Property(
	  *   title="title",
	  *   type="string",
	  *   description="The incrementing id of the file timestamp"
	  * )
	  *
	  * @method static BibleFileTitle whereTitle($value)
	  * @property string $title
	  *
	  */
	 protected $title;
	 /*
	  *
	  * @OAS\Property(
	  *   title="description",
	  *   type="string",
	  *   description="The incrementing id of the file timestamp"
	  * )
	  *
	  * @method static BibleFileTitle whereDescription($value)
	  * @property string|null $description
	  *
	  */
	 protected $description;
	 /*
	  *
	  * @OAS\Property(
	  *   title="key_words",
	  *   type="integer",
	  *   description="The incrementing id of the file timestamp",
	  *   minimum=1
	  * )
	  *
	  * @method static BibleFileTitle whereKeyWords($value)
	  * @property string|null $key_words
	  *
	  */
	 protected $key_words;

    public function file()
    {
    	return $this->BelongsTo(BibleFile::class);
    }

}
