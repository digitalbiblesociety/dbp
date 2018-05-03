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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\ArticleTranslation[] $translations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Article whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Article whereCoverThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Article whereIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Article whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Article whereUserId($value)
 * @mixin \Eloquent
 */
class Article extends Model
{
    protected $fillable = ['title','description','cover','cover_thumbnail'];

    public function translations()
    {
    	return $this->hasMany(ArticleTranslation::class);
    }

}
