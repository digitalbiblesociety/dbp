<?php

namespace App\Http\Controllers;

use App\Models\Language\Language;
use App\Transformers\LanguageTransformer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguagesController extends APIController
{
    /**
     * Display a listing of the resource.
     * Fetches the records from the database > passes them through fractal for transforming and
     *
     * @param code (optional): Get the entry for a three letter language code.
     * @param name (optional): Get the entry for a part of a language name in either native language or English.
     * @param full_word (optional): [true|false] Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string 'new' is anywhere in the language name, like in "Newari" and "Awa for Papua New Guinea". When true, it will only return volumes where the language name contains the full word 'new', like in "Awa for Papua New Guinea". Default is false.
     * @param sort_by (optional): [code|name|english] Primary criteria by which to sort. 'name' refers to the native language name. The default is 'english'.
     * @deprecated family_only (optional): [true|false] When set to true the returned list is of only legal language families. The default is false.
     * @deprecated possibilities (optional); [true|false] When set to true the returned list is a combination of DBP languages and ISO languages not yet defined in DBP that meet any of the criteria.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    if(!$this->api) return view('languages.index');
	    ini_set('memory_limit', '464M');

		$country = checkParam('country',null,'optional');
		$code = checkParam('code', null, 'optional');
	    $language_name_portion = checkParam('full_word', null, 'optional') ?? checkParam('language_name', null, 'optional') ;
	    $sort_by = checkParam('sort_by', null, 'optional');
	    $has_bibles = checkParam('has_bibles', null, 'optional');
	    $include_alt_names = checkParam('include_alt_names', null, 'optional');

		$languages = Language::select(['id','glotto_id','iso','name'])->withCount('bibles')
			->when($has_bibles, function ($query) use ($has_bibles) {
				return $query->has('bibles');
			})->when($country, function ($query) use ($country) {
				return $query->where('country_id', $country);
			})->when($code, function ($query) use ($code) {
				return $query->where('iso', $code);
			})->when($include_alt_names, function ($query) use ($has_bibles) {
				return $query->with('alternativeNames');
			})->when($language_name_portion, function ($query) use ($language_name_portion) {
				return $query->whereHas('alternativeNames', function ($query) use ($language_name_portion) {
					$query->where('name', $language_name_portion);
			})->orWhere('name', $language_name_portion);
			})->when($sort_by, function ($query) use ($sort_by) {
				return $query->orderBy($sort_by);
			})->get();

		return $this->reply(fractal()->collection($languages)->serializeWith($this->serializer)->transformWith(new LanguageTransformer())->toArray());
    }


	/**
	 * API V2:
	 * Returns a List of Languages that contain resources and if the
	 * language is a dialect, returns the parent language as well.
	 *
	 * @param root (optional): the native language or English language language name root. Can be used to restrict the response to only languages that start with 'Quechua' for example
	 * @param full_word (optional): [true|false] Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string 'new' is anywhere in the language name, like in "Newari" and "Awa for Papua New Guinea". When true, it will only return volumes where the language name contains the full word 'new', like in "Awa for Papua New Guinea". Default is false.
	 * @param language_code (optional): the three letter language code.
	 * @param media (optional): [text|audio|video] - the format of languages the caller is interested in. This specifies if you want languages available in text or languages available in audio.
	 * @deprecated delivery (optional): [streaming|web_streaming|download|download_text|mobile|sign_language|local_bundled|podcast|mp3_cd|digital_download|bible_stick|subsplash|any|none] a criteria for approved delivery method. It is possible to OR these methods together using '|', such as "delivery=streaming|mobile". 'any' means any of the supported methods (this list may change over time). 'none' means assets that are not approved for any of the supported methods. All returned by default.
	 * @param status (optional): [live|disabled|incomplete|waiting_review|in_review|discontinued] Publishing status of volume. The default is 'live'.
	 * @param resolution (optional): [lo|med|hi] Currently used for video volumes as they can be available in different resolutions, basically conforming to the loose general categories of low, medium, and high resolution. Low resolution is geared towards devices with smaller screens.
	 * @param organization_id: The id of an organization by which to filter the languages of available volumes.
	 *
	 *
	 * @return View|JSON
	 */
	public function volumeLanguage()
    {
	    ini_set('memory_limit', '464M');
	    // $delivery =  checkParam('delivery', null, 'optional');
	    $iso = checkParam('language_code', null, 'optional');
	    $root = checkParam('root', null, 'optional');
	    $media =  checkParam('media', null, 'optional');
	    $organization_id =  checkParam('organization_id', null, 'optional');

	    //$languages = \Cache::remember('volumeLanguage'.$root.$iso.$media.$organization_id, 2400, function () use($root,$iso,$media,$organization_id) {
		    $languages = Language::select( [ 'id', 'iso', 'iso2B', 'iso2T', 'iso1', 'name', 'autonym' ] )->with( 'parent' )
		                   ->when( $iso, function ( $query ) use ( $iso ) {
			                   return $query->where( 'iso', $iso );
		                   } )->when( $root, function ( $query ) use ( $root ) {
				    return $query->where( 'name', '%' . $root . '%' );
			    } )->when( $organization_id, function ( $query ) use ( $organization_id ) {
				    return $query->whereHas( 'filesets', function ( $q ) use ( $organization_id ) {
					    $q->where( 'organization_id', $organization_id );
				    } );
			    } )->when( $media, function ( $query ) use ( $media ) {
				    switch ( $media ) {
					    case "audio": {
						    return $query->has( 'bibles.filesetAudio' );
						    break;
					    }
					    case "video": {
						    return $query->has( 'bibles.filesetFilm' );
						    break;
					    }
					    case "text": {
						    return $query->has( 'bibles.filesets' );
						    break;
					    }
				    }
			    } )->get();
	    //});
		return $this->reply(fractal()->collection($languages)->serializeWith($this->serializer)->transformWith(new LanguageTransformer())->toArray());
    }


	/**
	 * API V2:
	 * Returns of List of Macro-Languages that contain resources and their dialects
	 *
	 * @param language_code (optional): the three letter language code.
	 * @param root (optional): the native language or English language language name root. Can be used to restrict the response to only languages that start with 'Quechua' for example
	 * @deprecated full_word (optional): [true|false] Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string 'new' is anywhere in the language name, like in "Newari" and "Awa for Papua New Guinea". When true, it will only return volumes where the language name contains the full word 'new', like in "Awa for Papua New Guinea". Default is false.
	 * @param media (optional): [text|audio|video] - the format of languages the caller is interested in. This specifies if you want languages available in text or languages available in audio.
	 * @param delivery (optional): [streaming|web_streaming|download|download_text|mobile|sign_language|local_bundled|podcast|mp3_cd|digital_download|bible_stick|subsplash|any|none] a criteria for approved delivery method. It is possible to OR these methods together using '|', such as "delivery=streaming|mobile". 'any' means any of the supported methods (this list may change over time). 'none' means assets that are not approved for any of the supported methods. All returned by default.
	 * @deprecated status (optional): [live|disabled|incomplete|waiting_review|in_review|discontinued] Publishing status of volume. The default is 'live'.
	 * @deprecated resolution (optional): [lo|med|hi] Currently used for video volumes as they can be available in different resolutions, basically conforming to the loose general categories of low, medium, and high resolution. Low resolution is geared towards devices with smaller screens.
	 * @param organization_id: The id of an organization by which to filter the languages of available volumes.
	 *
	 * @return mixed
	 */
	public function volumeLanguageFamily()
	{
		return json_decode(file_get_contents(public_path('/data/volumelanguagefamily.json')));
		if(!$this->api) return view('languages.volumes');

		// $full_word =  checkParam('full_word', null, 'optional');
		// $status =  checkParam('status', null, 'optional');
		// $resolution =  checkParam('resolution', null, 'optional');
		$iso = checkParam('language_code', null, 'optional');
		$root = checkParam('root', null, 'optional');
		$media =  checkParam('media', null, 'optional');
		$delivery =  checkParam('delivery', null, 'optional');
		$organization_id =  checkParam('organization_id', null, 'optional');

		$languages = Language::with('bibles')->with('dialects')
			->with(['dialects.childLanguage' => function($query) {$query->select(['id','iso']);}])
			->when($iso, function ($query) use ($iso) {
				return $query->where('iso', $iso);
			})->when($root, function ($query) use ($root) {
				return $query->where('name', 'LIKE', '%'.$root.'%');
			})->when($root, function ($query) use ($root) {
				return $query->where('name', 'LIKE', '%'.$root.'%');
			})
			->get();
		return $this->reply(fractal()->collection($languages)->serializeWith($this->serializer)->transformWith(new LanguageTransformer())->toArray());
	}

	/**
	 * WEB:
     * Show the form for creating a new resource.
     *
     * @return View
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
	    $language->load("translations","codes","alternativeNames","dialects","classifications","countries","bibles");
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
