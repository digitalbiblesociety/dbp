<?php

namespace App\Models\Bible;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bible\Printable
 *
 * @mixin \Eloquent
 */
class Printable extends Model
{
    protected $table = "bible_print";
    protected $primaryKey = 'bible_id';
    public $incrementing = false;


    public function price()
    {
        $minimum_price = 230;
        $pagePrice = 1.3;
        $baseCost = 130;

        $cost = ($baseCost + ($pagePrice * $this->page_number)) * 1.25;
        if($cost < $minimum_price) $cost = $minimum_price;
        return ($cost / 100);
    }

}
