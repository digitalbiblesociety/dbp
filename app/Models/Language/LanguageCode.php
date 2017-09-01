<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

class LanguageCode extends Model
{

    protected $table = 'languages_codes';
    protected $fillable = ['code', 'source', 'glotto_id'];
	protected $hidden = ['language_id'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

}
