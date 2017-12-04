<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleEquivalent;
use App\Models\Bible\Book;
use App\Models\Language\Alphabet;
use App\Models\Language\Language;
use App\Models\Organization\OrganizationTranslation;
use App\Transformers\BibleTransformer;
use App\Transformers\BooksTransformer;
use Illuminate\Support\Facades\Cache;

class BiblesController extends APIController
{


	/**
	 *
	 * Display a listing of the bibles.
	 *
	 * @deprecated status (optional): [live|disabled|incomplete|waiting_review|in_review|discontinued] Publishing status of volume. The default is 'live'.
	 * @deprecated dbp_agreement (optional): [true|false] Whether or not a DBP Agreement has been executed between FCBH and the organization to whom the volume belongs.
	 * @deprecated expired (optional): [true|false] Whether the volume as passed its expiration or not.
	 * @deprecated resolution (optional): [lo|med|hi] Currently used for video volumes as they can be available in different resolutions, basically conforming to the loose general categories of low, medium, and high resolution. Low resolution is geared towards devices with smaller screens.
	 * @deprecated delivery (optional): [web|web_streaming|download|download_text|mobile|sign_language|streaming_url|local_bundled|podcast|mp3_cd|digital_download| bible_stick|subsplash|any|none] a criteria for approved delivery method. It is possible to OR these methods together using '|', such as "delivery=streaming_url|mobile".  'any' means any of the supported methods (this list may change over time) i.e. approved for something. 'none' means volumes that are not approved for any of the supported methods. All volumes are returned by default.
	 * @param dam_id (optional): the volume internal DAM ID. Can be used to restrict the response to only DAM IDs that contain with 'N2' for example
	 * @param fcbh_id (optional): the volume FCBH DAM ID. Can be used to restrict the response to only FCBH DAM IDs that contain with 'N2' for example
	 * @param media (optional): [text|audio|video] the format of assets the caller is interested in. This specifies if you only want volumes available in text or volumes available in audio.
	 * @param language (optional): Filter the versions returned to a specified native or English language language name. For example return all the 'English' volumes.
	 * @param full_word (optional): [true|false] Consider the language name as being a full word. For instance, when false, 'new' will return volumes where the string 'new' is anywhere in the language name, like in "Newari" and "Awa for Papua New Guinea". When true, it will only return volumes where the language name contains the full word 'new', like in "Awa for Papua New Guinea". Default is false.
	 * @param language_code (optional): the three letter language code.
	 * @param language_family_code (optional): the three letter language code for the language family.
	 * @param updated (optional): YYYY-MM-DD. This is used to get volumes that were modified since the specified date.
	 * @param organization_id (optional): Organization id of volumes to return.
	 * @param sort_by (optional): [ dam_id | volume_name | language_name | language_english | language_family_code | language_family_name | version_code | version_name | version_english ] Primary criteria by which to sort.  The default is 'dam_id'.
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function index()
    {
	    // Return the documentation if it's not an API request
	    if(!$this->api) return view('bibles.index');

	    // $delivery = checkParam('delivery', null,'optional');
	    // $expired = checkParam('expired', null,'optional');
	    // $status = checkParam('status', null,'optional');
	    // $dbp_agreement = checkParam('dbp_agreement', null,'optional');
	    // $resolution = checkParam('resolution', null,'optional');
	    $dam_id = checkParam('dam_id', null, 'optional') ?? checkParam('fcbh_id', null,'optional');
	    $media = checkParam('media', null, 'optional');
	    $language = checkParam('language', null, 'optional');
	    $full_word = checkParam('full_word', null, 'optional');
	    $iso = checkParam('language_family_code', null, 'optional') ?? checkParam('language_code', null, 'optional');
	    $updated = checkParam('updated', null, 'optional');
	    $organization = checkParam('organization_id', null, 'optional');
		$sort_by = checkParam('sort_by', null, 'optional');

	    //Cache::remember("bibles_$dam_id.$media.$language.$full_word.$iso.$updated.$organization.$sort_by", 1900, function () use ($dam_id, $media, $language, $full_word, $iso, $updated, $organization, $sort_by) {
	    $bibles = Bible::with('currentTranslation','vernacularTranslation','language.parent')->has('filesets')->when($language, function ($query) use ($language, $full_word) {
			    if(!$full_word) return $query->where('name', 'LIKE', "%".$language."%");
			    return $query->where('name', $language);
		    })->when($organization, function($q) use ($organization) {
			    $q->where('organization_id', '>=', $organization);
		    })->when($dam_id, function($q) use ($dam_id) {
			    $q->where('id', '=', $dam_id);
		    })->when($media, function($q) use ($media) {
			    switch ($media) {
				    case "video": {$q->has('filesetFilm'); break;}
				    case "audio": {$q->has('filesetAudio');break;}
			    }
		    })->when($updated, function($q) use ($updated) {
			    $q->where('updated_at', '>', $updated);
		    })->when($iso, function($q) use ($iso){
			    $q->where('iso', $iso);
		    })->when($sort_by, function($q) use ($sort_by){
			    $q->orderBy($sort_by);
		    })->get();

	    if(($this->v == 2) OR ($this->v == 3)) $bibles->load('alphabet','filesets');

	    return $this->reply(fractal()->collection($bibles)->transformWith(new BibleTransformer())->serializeWith($this->serializer)->toArray());
    }


	/**
	 *
	 * A Route to Review The Last 500 Recent Changes to The Bible Resources
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function history()
    {
    	if(!$this->api) return view('bibles.history');

		$limit = checkParam('limit', null, 'optional') ?? 500;
		$bibles = Bible::select(['id','updated_at'])->take($limit)->get();
		return $this->reply(fractal()->collection($bibles)->transformWith(new BibleTransformer())->serializeWith($this->serializer)->toArray());
    }

	/**
	 *
	 * Get the list of versions defined in the system
	 *
	 * @param code (optional): Get the entry for a three letter version code.
	 * @param name (optional): Get the entry for a part of a version name in either native language or English.
	 * @param sort_by (optional): [code|name|english] Primary criteria by which to sort. 'name' refers to the native language name. The default is 'english'.
	 *
	 * @return json
	 */
	public function libraryVersion()
	{
		$code = checkParam('code', null, 'optional');
		$name = checkParam('name', null, 'optional');
		$sort = checkParam('sort_by', null, 'optional');
		$versions = collect(json_decode(file_get_contents(public_path('static/version_listing.json'))));
		if(isset($code)) $versions = $versions->where('version_code',$code)->flatten();
		if(isset($name)) $versions = $versions->filter(function ($item) use ($name) { return false !== stristr($item->version_name, $name);})->flatten();
		if(isset($sort)) $versions = $versions->sortBy($sort);
		return $this->reply($versions);
	}

	/**
	 * @return mixed
	 */
	public function libraryMetadata()
	{
		$bible_id = null;
		$bible = null;
		$dam_id = checkParam('dam_id', null, 'optional');
		$bibleEquivalent = BibleEquivalent::where('equivalent_id',$dam_id)->first();

		if(isset($bibleEquivalent)) $bible = $bibleEquivalent->bible;
		if(!$bible) $bible = Bible::find($dam_id);
		if(!$bible) return $this->replyWithError("No Bible found for given dam_id");
		if($bible) $bible_id = $bible->id;

		$bible = Bible::with('organizations')->when($bible_id, function ($query) use ($bible_id) {
			return $query->where('id', $bible_id);
		})->first();
		return $this->reply(fractal()->item($bible)->serializeWith($this->serializer)->transformWith(new BibleTransformer())->toArray());
	}

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

	    request()->validate([
		    'id'                      => 'required|unique:bibles,id|max:24',
		    'iso'                     => 'required|exists:languages,iso',
		    'translations.*.name'     => 'required',
		    'translations.*.iso'      => 'required|exists:languages,iso',
		    'date'                    => 'integer',
	    ]);

	    $bible = \DB::transaction(function () {
		    $bible = new Bible();
		    $bible = $bible->create(request()->only(['id','date','script','portions','copyright','derived','in_progress','notes','iso']));
		    $bible->translations()->createMany(request()->translations);
		    $bible->organizations()->attach(request()->organizations);
		    $bible->equivalents()->createMany(request()->equivalents);
		    $bible->links()->createMany(request()->links);
		    return $bible;
	    });

	    return redirect()->route('view_bibles.show', ['id' => $bible->id]);
    }

    /**
     * Description:
     * Display the bible meta data for the specified ID.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $bible = Bible::with('filesets')->find($id);
	    if(!$bible) return $this->setStatusCode(404)->replyWithError("Bible not found for ID: $id");

    	if(!$this->api) return view('bibles.show',compact('bible'));
		return $this->reply(fractal()->collection($bible)->serializeWith($this->serializer)->transformWith(new BibleTransformer())->toArray());
    }

	public function manage($id)
	{
		$bible = Bible::with('filesets')->find($id);
		if(!$bible) return $this->setStatusCode(404)->replyWithError("Bible not found for ID: $id");

		return view('bibles.manage',compact('bible'));
	}

	public function books()
	{
		if(!$this->api) return view('bibles.books.index');

		$books = Book::select(['id','book_order','name'])->orderBy('book_order')->get();
		return $this->reply(fractal()->collection($books)->transformWith(new BooksTransformer)->toArray());
	}


	/**
	 * Display the equivalents for this resource.
	 *
	 * @param  string $id
	 * @return \Illuminate\Http\Response
	 */
	public function equivalents(string $id)
	{
		if(!$this->api) return view('bibles.books.index');

		$equivalents = BibleEquivalent::where('bible_id',$id)->get();
		return $this->reply($equivalents);
	}


	public function book($id)
	{
		if(!$this->api) return view('bibles.books.show');

		$book = Book::with('codes','translations')->find($id);
		return $this->reply(fractal()->item($book)->transformWith(new BooksTransformer)->toArray());
	}


	public function edit($id)
	{
		$bible = Bible::with('translations.language')->find($id);
		if(!$this->api) {
			$languages = Language::select(['iso','name'])->orderBy('iso')->get();
			$organizations = OrganizationTranslation::select(['name','organization_id'])->where('language_iso','eng')->get();
			$alphabets = Alphabet::select('script')->get();
			return view('bibles.edit',compact('languages', 'organizations', 'alphabets','bible'));
		}

		return $this->reply(fractal()->collection($bible)->transformWith(new BibleTransformer())->toArray());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$languages = Language::select(['iso','name'])->get();
		$organizations = OrganizationTranslation::select(['name','organization_id'])->where('language_iso','eng')->get();
		$alphabets = Alphabet::select('script')->get();
		return view('bibles.create',compact('languages', 'organizations', 'alphabets'));
	}


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

	    request()->validate([
		    'id'                      => 'required|max:24',
		    'iso'                     => 'required|exists:languages,iso',
		    'translations.*.name'     => 'required',
		    'translations.*.iso'      => 'required|exists:languages,iso',
		    'date'                    => 'integer',
	    ]);

	    $bible = \DB::transaction(function () use($id) {
		    $bible = Bible::with('translations','organizations','equivalents','links')->find($id);
		    $bible->update(request()->only(['id','date','script','portions','copyright','derived','in_progress','notes','iso']));

			if(request()->translations) {
				foreach ($bible->translations as $translation) $translation->delete();
				foreach (request()->translations as $translation) if($translation['name']) $bible->translations()->create($translation);
			}

		    if(request()->organizations) $bible->organizations()->sync(request()->organizations);

		    if(request()->equivalents) {
			    foreach ($bible->equivalents as $equivalent) $equivalent->delete();
			    foreach (request()->equivalents as $equivalent) if($equivalent['equivalent_id']) $bible->equivalents()->create($equivalent);
		    }

		    if(request()->links) {
			    foreach ($bible->links as $link) $link->delete();
			    foreach (request()->links as $link) if($link['url']) $bible->links()->create($link);
		    }

		    return $bible;
	    });

	    return redirect()->route('view_bibles.show', ['id' => $bible->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // TODO: Generate Delete Model for Bible
    }
}
