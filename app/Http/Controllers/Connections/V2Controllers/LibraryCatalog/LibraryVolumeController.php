<?php

namespace App\Http\Controllers\Connections\V2Controllers\LibraryCatalog;

use App\Http\Controllers\APIController;
use App\Models\Language\Language;
use App\Transformers\V2\LibraryVolumeTransformer;
use App\Traits\AccessControlAPI;

use App\Models\Bible\BibleFileset;
use App\Models\Bible\Bible;

class LibraryVolumeController extends APIController
{

	use AccessControlAPI;

    /**
     * v2_volume_history
     *
     * @link https://api.dbp.dev/library/volumehistory?key=1234&v=2
     *
     * @OA\Get(
     *     path="/library/volumehistory",
     *     tags={"Library Catalog"},
     *     summary="Volume History List",
     *     description="This call gets the event history for volume changes to status, expiry, basic info, delivery, and organization association. The event reflects the previous state of the volume. In other words, it reflects the state up to the moment of the time of the event.",
     *     operationId="v2_volume_history",
     *     @OA\Parameter(name="limit",  in="query", description="The Number of records to return"),
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v4_bible.one")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v4_bible.one")),
     *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v4_bible.one"))
     *     )
     * )
     *
     * A Route to Review The Last 500 Recent Changes to The Bible Resources
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function history()
    {
        if (!$this->api) return view('bibles.history');

        $limit  = checkParam('limit', null, 'optional') ?? 500;
        $filesets = BibleFileset::with('bible.language')->has('bible.language')->take($limit)->get();
        $filesets->map(function($fileset) {
            $fileset->v2_id = strtoupper($fileset->bible->first()->language->iso.substr($fileset->bible->first()->id,3,3));
            return $fileset;
        });

        return $this->reply(fractal($filesets, new LibraryVolumeTransformer())->serializeWith($this->serializer));
    }

	/**
	 *
	 *
	 * Display a listing of the bibles.
	 *
	 * @OA\Get(
	 *     path="/library/volume",
	 *     tags={"Library Catalog"},
	 *     summary="",
	 *     description="This method retrieves the available volumes in the system according to the filter specified",
	 *     operationId="v2_library_volume",
	 *     @OA\Parameter(
	 *          name="dam_id",
	 *          in="query",
	 *          description="The Bible Id",
	 *          ref="#/components/schemas/Bible/properties/id"
	 *     ),
	 *     @OA\Parameter(
	 *          name="fcbh_id",
	 *          in="query",
	 *          description="An alternative query name for the bible id",
	 *          @OA\Schema(type="string")
	 *     ),
	 *     @OA\Parameter(
	 *          name="media",
	 *          in="query",
	 *          description="If set, will filter results by the type of media for which filesets are available.",
	 *         @OA\Schema(
	 *          type="string",
	 *          @OA\ExternalDocumentation(
	 *              description="For a complete list of media types please see the v4_bible_filesets.types route",
	 *              url="/docs/swagger/v4#/Bibles/v4_bible_filesets_types"
	 *          )
	 *         )
	 *     ),
	 *     @OA\Parameter(
	 *          name="language",
	 *          in="query",
	 *          description="The language to filter results by",
	 *          @OA\Schema(ref="#/components/schemas/Language/properties/name")
	 *     ),
	 *     @OA\Parameter(
	 *          name="full_word",
	 *          in="query",
	 *          description="Consider the language name as being a full word. For instance, when false,
	               'new' will return volumes where the string 'new' is anywhere in the language name,
	               like in `Newari` and `Awa for Papua New Guinea`. When true, it will only return volumes
	               where the language name contains the word 'new', like in `Awa for Papua New Guinea`.",
	 *          @OA\Schema(ref="#/components/schemas/Language/properties/name")
	 *     ),
	 *     @OA\Parameter(
	 *          name="language_name",
	 *          in="query",
	 *          description="The language name to filter results by. For a complete list see the `/languages` route",
	 *          @OA\Schema(ref="#/components/schemas/Language/properties/name")),
	 *     @OA\Parameter(
	 *          name="language_code",
	 *          in="query",
	 *          description="The iso code to filter results by. This will return results only in the language specified.",
	 *          @OA\Schema(ref="#/components/schemas/Language/properties/iso"),
	 *          @OA\ExternalDocumentation(
	 *              description="For a complete list see the `iso` field in the `/languages` route",
	 *              url="/docs/swagger/v2#/Languages"
	 *          )),
	 *     @OA\Parameter(
	 *          name="language_family_code",
	 *          in="query",
	 *          description="The iso code of the trade language to filter results by. This will also return all dialects of a language. For a complete list see the `iso` field in the `/languages` route",
	 *          @OA\Schema(type="string")),
	 *     @OA\Parameter(
	 *          name="updated",
	 *          in="query",
	 *          description="The last time updated",
	 *          @OA\Schema(type="string")),
	 *     @OA\Parameter(
	 *          name="organization_id",
	 *          in="query",
	 *          description="The owning organization to return bibles for. For a complete list see the `/organizations` route",
	 *          @OA\Schema(type="string")),
	 *     @OA\Parameter(
	 *          name="sort_by",
	 *          in="query",
	 *          description="The any field to within the bible model may be selected as the value for this `sort_by` param.",
	 *          @OA\Schema(type="string")),
	 *     @OA\Parameter(
	 *          name="sort_dir",
	 *          in="query",
	 *          description="The direction to sort by the field specified in `sort_by`. Either `asc` or `desc`",
	 *          @OA\Schema(type="string")),
	 *     @OA\Parameter(
	 *          name="filter_by_fileset",
	 *          in="query",
	 *          description="This field defaults to true but when set to false will return all Bible entries regardless of whether or not the API has content for that biblical text.",
	 *          @OA\Schema(type="string")),
	 *     @OA\Parameter(ref="#/components/parameters/version_number"),
	 *     @OA\Parameter(ref="#/components/parameters/key"),
	 *     @OA\Parameter(ref="#/components/parameters/pretty"),
	 *     @OA\Parameter(ref="#/components/parameters/format"),
	 *     @OA\Response(
	 *         response=200,
	 *         description="successful operation",
	 *         @OA\MediaType(mediaType="application/json",
	 *          @OA\Schema(ref="#/components/schemas/v2_library_volume")),
	 *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v2_library_volume")),
	 *         @OA\MediaType(mediaType="text/x-yaml",      @OA\Schema(ref="#/components/schemas/v2_library_volume"))
	 *     )
	 * )
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
	 */
	public function libraryVolume()
	{
		if (env('APP_ENV') == 'local') ini_set('memory_limit', '864M');
		// Return the documentation if it's not an API request
		if (!$this->api) return view('bibles.index');

		$dam_id             = checkParam('dam_id|fcbh_id', null, 'optional');
		$media              = checkParam('media', null, 'optional');
		$language_name      = checkParam('language', null, 'optional');
		$full_word          = checkParam('full_word', null, 'optional');
		$iso                = checkParam('language_code|language_family_code', null, 'optional');
		$updated            = checkParam('updated', null, 'optional');
		$organization       = checkParam('organization_id', null, 'optional');

		$access_control = $this->accessControl($this->key, 'api');

		$language = $iso ? Language::where('iso',$iso)->first() : null;

		$filesets = \DB::connection('dbp')->table('bible_filesets')
					// Version 2 does not support delivery via s3
					->where('set_type_code','!=','text_format')
					->when($dam_id, function ($q) use ($dam_id) {
						$q->where('bible_filesets.id', $dam_id)->orWhere('bible_filesets.id',substr($dam_id,0,-4))->orWhere('bible_filesets.id',substr($dam_id,0,-2));
					})
					// Filter by media
					->when($media, function ($q) use ($media) {
						switch ($media) {
							case 'video': {$q->where('set_type_code', 'LIKE', 'video%');break;}
							case 'audio': {$q->where('set_type_code', 'LIKE', 'audio%');break;}
							case 'text':  {$q->where('set_type_code', 'LIKE', 'text%');break;}
						}
					})
					->join('bible_fileset_tags', function($q) {
						$q->on('bible_fileset_tags.hash_id','bible_filesets.hash_id')->where('name','volume');
					})
		            ->join('bible_fileset_connections as connection','connection.hash_id','bible_filesets.hash_id')
					->join('bibles','connection.bible_id','bibles.id')
					->join('bible_translations',function($q) use($language,$language_name,$full_word) {
						$q->on('bible_translations.bible_id','bibles.id');
						if($language && $language_name && $full_word) $q->where('name', $language);
						if($language_name) $q->where('name','LIKE','%'.$language_name.'%');
					})
					->join('bible_organizations', function($q) use($organization) {
						$q->on('bibles.id','bible_organizations.bible_id')->where('relationship_type','publisher');
						if($organization) $q->where('bible_organizations.organization_id',$organization);
					})
					->join('alphabets', function($q) {
						$q->on('bibles.script','alphabets.script');
					})
					->join('languages', 'bibles.language_id','languages.id')
					->when($language, function ($q, $language) {
						$q->where('languages.iso',$language->iso);
					})
					->whereIn('bible_filesets.hash_id',$access_control->hashes)
					->select([
						'bible_translations.name as version_name',
						'bibles.id as bible_id',
						'bible_filesets.id',
						'bible_fileset_tags.description as volume_name',
						'bible_filesets.created_at',
						'bible_filesets.updated_at',
						'bible_filesets.set_type_code',
						'bible_filesets.set_size_code',
						'alphabets.direction',
						'languages.iso',
						'languages.iso2B',
						'languages.iso2T',
						'languages.iso1',
						'languages.name as language_name',
						'languages.autonym as autonym',
					])
					->when($updated, function($q) use($updated) {
						$q->where('updated_at','>',$updated);
					})->orderBy('bibles.id')
					->get();

		return $this->reply(fractal($this->generate_v2_style_id($filesets), new LibraryVolumeTransformer())->serializeWith($this->serializer));
	}

	private function generate_v2_style_id($filesets)
	{
		$output = [];
		foreach($filesets as $fileset) {

			switch($fileset->set_type_code) {
				case 'audio_drama': { $type_code = '2DA'; break; }
				case 'audio':       { $type_code = '1DA'; break; }
				case 'text_plain':  { $type_code = 'ET'; break; }
			}

			$fileset_id = substr($fileset->id,0,6);

			switch ($fileset->set_size_code) {
				case 'C':
				case 'NTOTP':
				case 'OTNTP':
				case 'NTPOTP': {
					if($type_code == 'ET') {
						$output[$fileset_id.'O2'.$type_code] = clone $fileset;
						$output[$fileset_id.'N2'.$type_code] = clone $fileset;
					} else {
						$output[$fileset_id.'O'.$type_code] = clone $fileset;
						$output[$fileset_id.'N'.$type_code] = clone $fileset;
					}
					break;
				}

				case 'NT':
				case 'NTP':    {
					if($type_code == 'ET') {
						$output[$fileset_id.'N2'.$type_code] = clone $fileset;
					} else {
						$output[$fileset_id.'N'.$type_code] = clone $fileset;
					}
					break;
				}

				case 'OT':
				case 'OTP':    {
					if($type_code == 'ET') {
						$output[$fileset_id.'O2'.$type_code] = clone $fileset;
					} else {
						$output[$fileset_id.'O'.$type_code] = clone $fileset;
					}
					break;
				}
			}
		}

		foreach ($output as $key => $item) {$output[$key]->generated_id = $key;}
		return $output;
	}

}
