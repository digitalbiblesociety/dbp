<?php

namespace App\Http\Controllers;

use App\Models\Language\Alphabet;
use App\Models\Language\AlphabetNumber;

use App\Models\Language\Language;
use App\Transformers\AlphabetTransformer;
use App\Transformers\NumbersTransformer;

class NumbersController extends APIController
{
	/**
	 * V2|V4
	 *
	 * This route returns the vernacular numbers for a set range. The range for a single call is limited to 2000 numbers.
	 *
	 * @return mixed
	 */
	public function customRange()
    {
		$iso = checkParam('iso', null, true);
		$start_number = checkParam('start');
		$end_number = checkParam('end');
		if(($end_number - $start_number) > 2000) return $this->replyWithError("The Selection has a max size of 2000");
	    $out_numbers = [];

		// Fetch Numbers By Iso Or Script Code
		$numbers = AlphabetNumber::where('script_variant_iso', $iso)->get()->keyBy('numeral')->ToArray();

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

    public function index()
    {
	    $alphabets = Alphabet::has('numerals')->get();
	    if($this->api) return $this->reply(fractal()->collection($alphabets)->transformWith(new AlphabetTransformer())->serializeWith($this->serializer));
	    return view('languages.alphabets.numerals.index', compact('alphabets'));
    }

    public function show($system)
    {
	    $numerals = AlphabetNumber::where('script_id', $system)->orderBy('numeral')->get();
	    if($this->api) return $this->reply(fractal()->collection($numerals)->transformWith(new NumbersTransformer())->serializeWith($this->serializer));

    	$alphabet = Alphabet::with('languages.bibles.translations')->find($system);
		return view('languages.alphabets.numerals.show',compact('numerals','alphabet'));
    }

    public function create()
    {
    	$alphabets = Alphabet::select('script')->get();
    	$languages = Language::select(['iso','name'])->get();
		return view('languages.alphabets.numerals.create', compact('alphabets','languages'));
    }

	public function store()
	{
		request()->validate([
			'numerals.*.script_id'             => 'exists:alphabets,script|required',
			'numerals.*.script_variant_iso'    => 'exists:languages,iso',
			'numerals.*.numeral'               => 'required|integer',
			'numerals.*.numeral_vernacular'    => 'string|max:191|required',
			'numerals.*.numeral_written'       => 'string|max:191',
		]);

		foreach (request()->numerals as $input_numeral) {
			$alphabetExists = AlphabetNumber::where([['script_id',$input_numeral['script_id']], ['script_variant_iso',$input_numeral['script_variant_iso']], ['numeral',$input_numeral['numeral']], ['numeral_vernacular',$input_numeral['numeral_vernacular']], ['numeral_written',$input_numeral['numeral_written']]])->first();
			if($alphabetExists == NULL) continue;
			$alphabet_number = new AlphabetNumber();
			$alphabet_number->create($input_numeral);
		}
		$script_id = collect(request()->numerals)->pluck('script_id')->first();
		return redirect()->route('view_numbers.show', ['system' => $script_id]);
	}

    public function edit($system)
    {
	    $alphabets = Alphabet::select('script')->get();
	    $languages = Language::select(['iso','name'])->get();
	    $numbers = AlphabetNumber::where('script_id', $system)->get();
		if($this->api) return $numbers;
		return view('languages.alphabets.numerals.edit',compact('alphabets','languages','numbers','system'));
    }

    public function update()
    {

    }


}
