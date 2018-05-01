<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title','description','cover','cover_thumbnail'];

    public function translations()
    {
    	return $this->hasMany(ArticleTranslation::class);
    }

}
