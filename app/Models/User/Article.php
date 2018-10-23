<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User\Article
 *
 * @property int $id
 * @property string $iso
 * @property int $organization_id
 * @property string $user_id
 * @property string|null $cover
 * @property string|null $cover_thumbnail
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read ArticleTranslation[] $translations
 * @method static Article whereCover($value)
 * @method static Article whereCoverThumbnail($value)
 * @method static Article whereCreatedAt($value)
 * @method static Article whereId($value)
 * @method static Article whereIso($value)
 * @method static Article whereOrganizationId($value)
 * @method static Article whereUpdatedAt($value)
 * @method static Article whereUserId($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
	protected $connection = 'dbp_users';
    protected $fillable = ['title','description','cover','cover_thumbnail'];

    public function translations()
    {
    	return $this->hasMany(ArticleTranslation::class);
    }

    public function tags()
    {
    	return $this->hasMany(ArticleTag::class);
    }

    public function currentTranslation()
    {
    	return $this->hasOne(ArticleTranslation::class)->where('iso',\App::getLocale());
    }

}
