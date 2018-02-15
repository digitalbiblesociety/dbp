<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Highlight
 *
 * @mixin \Eloquent
 */
class Highlight extends Model
{
    public $table = 'user_highlights';
    protected $fillable = ['user_id','bible_id','book_id','chapter','verse_start','verse_end','highlight_start','highlighted_words'];

}
