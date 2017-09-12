<?php

namespace App\Http\Controllers;

use App\Models\Language\AlphabetNumber;

use Illuminate\Http\Request;

class NumbersController extends APIController
{
	/**
	 * V2:
	 *
	 * This route returns the vernacular numbers for a set range. The range for a single call is limited to 2000 numbers.
	 *
	 * @return JSON|View
	 */
	public function index()
    {
		$iso = checkParam('iso', null, true);
		$start_number = checkParam('start');
		$end_number = checkParam('end');
		if(($end_number - $start_number) > 2000) return $this->replyWithError("The Selection has a max size of 2000");

		// Fetch Numbers By Iso Or Script Code
		$numbers = AlphabetNumber::where('script_varient_iso', $iso)->get()->keyBy('numeral')->ToArray();

		// Run through the numbers and return the vernaculars
		$current_number = $start_number;
		while($end_number >= $current_number) {
		    $number_vernacular = "";
		    // If it's not supported by the system return "normal" numbers
		    if(empty($numbers)) {
			    $number_vernacular = $current_number;
		    } else {
			    foreach(str_split($current_number) as $i) $number_vernacular .= $numbers[$i]['numeral_vernacular'];
		    }
			$out_numbers[] = [
			    "numeral"            => intval($current_number),
			    "numeral_vernacular" => $number_vernacular
			];
		    $current_number++;
		}
		return $this->reply($out_numbers);
    }


}
