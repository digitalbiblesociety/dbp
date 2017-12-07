<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\BibleVariation
 *
 * @property string $variation_id
 * @property int $id
 * @property string $date
 * @property string|null $scope
 * @property string|null $script
 * @property string|null $derived
 * @property string|null $copyright
 * @property string|null $in_progress
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bible\BibleFile[] $files
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleVariation whereCopyright($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleVariation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleVariation whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleVariation whereDerived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleVariation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleVariation whereInProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleVariation whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleVariation whereScript($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleVariation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Bible\BibleVariation whereVariationId($value)
 */
class BibleVariation extends Model
{

	public function files()
	{
		return $this->HasMany(BibleFile::class);
	}

}
