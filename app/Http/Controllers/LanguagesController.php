<?php

namespace App\Http\Controllers;

use App\Models\Language\Language;
use App\Transformers\LanguageTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController;

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
    	if($this->api) {
		    $country = $_GET['country'] ?? false;
			$languages = Language::with("translations","primaryCountry","iso639_2")->withCount('bibles')
				->when($country, function ($query) use ($country) {
					return $query->where('country_id', $country);
				})->get();
    		return $this->reply(fractal()->collection($languages)->serializeWith($this->serializer)->transformWith(new LanguageTransformer())->toArray());
	    }
        return view('languages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('languages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    $this->validate($request, [
		    'iso'         => 'unique:languages|max:3',
		    'glotto_code' => 'unique:languages|max:8',
		    'name'        => 'required|max:191',
		    'level'       => 'max:191',
		    'maps'        => 'max:191',
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
	    $language = new Language();
	    $language = $language->fetchByID($id);
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
        //
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
}
