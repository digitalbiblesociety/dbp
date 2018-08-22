<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

class NumberValues extends Model
{
	protected $connection = 'dbp';
	protected $table = 'numeral_system_glyphs';
}
