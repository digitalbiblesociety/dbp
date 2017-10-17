<?php

namespace App\Http\Controllers;

use App\Models\Language\Language;
use App\Transformers\LanguageTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;

use Illuminate\View\View;
use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use Spatie\Fractalistic\ArraySerializer;

class LanguagesController extends APIController
{
    /**
     * Display a listing of the resource.
     * Fetches the records from the database > passes them through fractal for transforming and
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    if(!$this->api) return view('languages.index');
	    ini_set('memory_limit', '464M');

		$country = checkParam('country',null,'optional');
		$languages = Language::select('id','iso','name')->when($country, function ($query) use ($country) { return $query->where('country_id', $country); })->get();

		return $this->reply(fractal()->collection($languages)->serializeWith($this->serializer)->transformWith(new LanguageTransformer())->toArray());
    }


	/**
	 * API V2:
	 * Returns a List of Languages that contain resources and if the
	 * language is a dialect, returns the parent language as well.
	 *
	 * @return View|JSON
	 */
	public function volumeLanguage()
    {
		if(!$this->api) return view('languages.volumes');

		$languages = Language::select('id','iso','iso2B','iso2T','iso1','name','autonym')->with('bibles')->with('parent')->with('parent.language')->get();
		return $this->reply(fractal()->collection($languages)->serializeWith($this->serializer)->transformWith(new LanguageTransformer())->toArray());
    }


	/**
	 * API V2:
	 * Returns of List of Macro-Languages that contain resources and their dialects
	 *
	 * @return JSON|View
	 */
	public function volumeLanguageFamily()
	{
		if(!$this->api) return view('languages.volumes');

		$languages = Language::select('id','iso','iso2B','iso2T','iso1','name','autonym')->with('bibles')->with('dialects')->with(['dialects.childLanguage' => function($query) {$query->select(['id','iso']);}])->get();
		return $this->reply(fractal()->collection($languages)->serializeWith($this->serializer)->transformWith(new LanguageTransformer())->toArray());
	}

	/**
	 * WEB:
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$user = \Auth::user();
    	if(!$user->archivist) return $this->setStatusCode(401)->replyWithError("Sorry you must have Archivist Level Permissions");
	    $swagger = fetchSwaggerSchema('Language','V4');
        return view('languages.create',compact('swagger'));
    }

	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$latLongRegex = '^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$';
	    $this->validate($request, [
		    'glotto_id'             => 'alpha_num|unique:languages,glotto_id|max:8|required_if:iso,null|nullable',
		    'iso'                   => 'alpha|unique:languages,iso|max:3|required_if:glotto_code,null|nullable',
			'iso2B'                 => 'alpha|max:3|unique:languages,iso2B',
			'iso2T'                 => 'alpha|max:3|unique:languages,iso2T',
			'iso1'                  => 'alpha|max:2|unique:languages,iso1',
			'name'                  => 'required|string|max:191',
			'autonym'               => 'required|string|max:191',
			'level'                 => 'string|max:191|nullable',
			'maps'                  => 'string|max:191|nullable',
			'population'            => 'integer',
			'latitude'              =>  "regex:$latLongRegex",
			'longitude'             =>  "regex:$latLongRegex",
			'country_id'            =>  'alpha|max:2|exists:countries,id',
	    ]);
		Language::create($request->all());
	    redirect()->route('languages_show',['id' => $request->id]);
    }

	/**
	 * @param $id
	 * @param Language $language
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function show($id)
    {
	    $language = fetchLanguage($id);
	    $language->load("translations","codes","alternativeNames","dialects","classifications","countries");
	    if(!$language) return $this->setStatusCode(404)->replyWithError("Language not found for ID: $id");
    	if($this->api) return $this->reply(fractal()->item($language)->transformWith(new LanguageTransformer())->toArray());
        return view('languages.show',compact('language'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$language = Language::find($id);
	    return view('languages.edit',compact('language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

	/**
	 * Handle the Country Lang route for V2
	 *
	 * @return View|JSON
	 */
	public function CountryLang()
	{
		// If it's not an API route send them to the documentation
		if(!$this->api) return view('docs.v2.country_language');

		// Get and set variables from Params. Both are optional.
		$sort_by = checkParam('sort_by', null, 'optional');
		$country_additional = checkParam('country_additional', null, 'optional');

		// Fetch Languages and add conditional sorting / loading depending on params
		$languages = Language::has('primaryCountry')->with('primaryCountry.regions')->when($sort_by, function ($query) use ($sort_by) {
			return $query->orderBy($sort_by, 'desc');
		})->get();
		if($country_additional) $languages->load('countries');

		// Transform and return JSON
		return $this->reply(fractal()->collection($languages)->serializeWith($this->serializer)->transformWith(new LanguageTransformer()));
	}

}
