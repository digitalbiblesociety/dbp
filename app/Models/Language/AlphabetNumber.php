<?php

namespace App\Models\Language;

use Illuminate\Database\Eloquent\Model;

class AlphabetNumber extends Model
{
    protected $table = "alphabet_numbers";
    protected $hidden = ["created_at","updated_at","id"];
    protected $fillable = [
    	"script_id",
	    "numeral",
	    "numeral_vernacular",
	    "numeral_written"
    ];

}
