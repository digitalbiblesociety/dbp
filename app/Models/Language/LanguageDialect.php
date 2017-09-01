<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language\Language;

class LanguageDialect extends Model
{
    public $primaryKey = 'glotto_id';
    protected $table = 'languages_dialects';
	protected $hidden = ['language_id','id'];
    public $incrementing = false;

    public function parentLanguage()
    {
        return $this->belongsTo(Language::class);
    }

	public function childLanguage()
	{
		return $this->belongsTo(Language::class,'dialect_id');
	}

}
