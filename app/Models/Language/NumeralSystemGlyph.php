<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

class NumeralSystemGlyph extends Model
{
	protected $connection = 'dbp';
	protected $table = 'numeral_system_glyphs';
	protected $primaryKey = 'numeral_system_id';
	protected $keyType = 'string';
	public $incrementing = false;
	public $hidden = ['created_at','updated_at','numeral_system_id'];


	public function numeralSystem()
	{
		return $this->belongsTo(NumeralSystem::class);
	}

	public function alphabet()
	{
		return $this->hasManyThrough(Alphabet::class,AlphabetNumeralSystem::class);
	}

}
