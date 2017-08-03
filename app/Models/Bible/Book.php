<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;
use App\Models\Language\Language;

class Book extends Model
{
    protected $table = "books";
    public $incrementing = false;
    public $timestamps = false;


    /**
     *
     * Titles and descriptions for every text can be translated into any language.
     * This relationship returns those translations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */

    public function text()
    {
        return $this->HasMany('App\Models\Bible\Text');
    }

	public function codes()
	{
		return $this->HasMany('App\Models\Bible\BookCode');
	}

	public function osis()
	{
		return $this->HasOne('App\Models\Bible\BookCode')->where('type','osis');
	}

    public function translations()
    {
        return $this->HasMany('App\Models\Bible\BookTranslation', 'book_id');
    }

    public function currentTranslation()
    {
        $language = Language::where('iso',\i18n::getCurrentLocale())->first();
        return $this->HasOne('App\Models\Bible\BookTranslation', 'book_id')->where('glotto_id',$language->id);
    }

    public function vernacularTranslation($iso)
    {
        $language = Language::where('iso',$iso)->first();
        return $this->HasOne('App\Models\Bible\BookTranslation', 'book_id')->where('glotto_id',$language->id);
    }

}
