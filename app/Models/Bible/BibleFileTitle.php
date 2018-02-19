<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleFileTitle
 *
 * @property int $file_id
 * @property string $iso
 * @property string $title
 * @property string|null $description
 * @property string|null $key_words
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Bible\BibleFile $file
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTitle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTitle whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTitle whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTitle whereIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTitle whereKeyWords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTitle whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleFileTitle whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BibleFileTitle extends Model
{
    public $table = "bible_file_titles";

    public function file()
    {
    	return $this->BelongsTo(BibleFile::class);
    }

}
