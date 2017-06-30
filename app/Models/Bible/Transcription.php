<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bible\Bible;
class Transcription extends Model
{
    protected $table = "bible_transcriptions";
    protected $fillable = [
        'abbr',
        'book_id',
        'chapter',
        'verseStart',
        'verseEnd',
        'text'
    ];

    public function bible()
    {
        return $this->HasOne(Bible::class, 'abbr','abbr');
    }

}