<?php

namespace App\Models\User\Study;

use Illuminate\Database\Eloquent\Model;

class HighlightColor extends Model
{
	protected $connection = 'dbp_users';
	public $table = 'user_highlight_colors';
	protected $fillable = ['color', 'hex', 'red', 'green', 'blue', 'opacity'];

	public function highlight()
	{
		return $this->belongsTo(Highlight::class);
	}
}
