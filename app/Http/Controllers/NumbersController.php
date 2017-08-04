<?php

namespace App\Http\Controllers;

use App\Models\Language\AlphabetNumber;
use App\Transformers\NumbersTransformer;
use Illuminate\Http\Request;

class NumbersController extends APIController
{
    public function index()
    {
    	if(\Route::currentRouteName() == 'v2_library_numbers') {
    		$iso = checkParam('iso');
		    $start_number = checkParam('start');
		    $end_number = checkParam('end');
		    if(($end_number - $start_number) > 2000) return $this->replyWithError("The Selection has a max size of 2000");

		    $numbers = AlphabetNumber::where('script_varient_iso',$iso)->get()->keyBy('numeral')->ToArray();

		    $current_number = $start_number;
		    while($end_number >= $current_number) {
			    $number_vernacular = "";
			    if(!isset($numbers[$current_number])) {
				    foreach(str_split($current_number) as $i) $number_vernacular .= $numbers[$i]['numeral_vernacular'];
				    $out_numbers[] = [
					    "numeral" => $current_number,
					    "numeral_vernacular" => $number_vernacular
				    ];
			    } else {
				    $out_numbers[] = [
					    "numeral" => $current_number,
					    "numeral_vernacular" => $numbers[$current_number]['numeral_vernacular']
				    ];
			    }
			    $current_number++;
		    }
		    return $this->reply($out_numbers);
		    return fractal()->collection($numbers)->transformWith(new NumbersTransformer())->addMeta(['start' => $start_number, 'end' => $end_number]);
	    }
    	//$iso = $_GET['iso'];
    	//$numbers = AlphabetNumber::where('eng')->
    	//return
    }
}
