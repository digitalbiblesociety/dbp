<?php

namespace App\Http\Controllers\Connections\V2Controllers\LibraryCatalog;

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use App\Models\Bible\BibleFileset;
use App\Transformers\V2\LibraryCatalog\LibraryCatalogTransformer;

class LibraryVersionController extends APIController
{

    /**
     *
     * Get the list of versions defined in the system
     *
     * @link https://api.dbp.test/library/version?key=1234&v=2
     *
     * @param code (optional): Get the entry for a three letter version code.
     * @param name (optional): Get the entry for a part of a version name in either native language or English.
     * @param sort_by (optional): [code|name|english] Primary criteria by which to sort. 'name' refers to the native language name. The default is 'english'.
     *
     * @OA\Get(
     *     path="/library/version",
     *     tags={"Library Catalog"},
     *     summary="Returns Audio File path information",
     *     description="This call returns the file path information for audio files for a volume. This information can be used with the response of the /audio/location call to create a URI to retrieve the audio files.",
     *     operationId="v2_library_version",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(name="code", in="query", description="The abbreviated `BibleFileset` id created from the three letters identifier after the iso code", required=true, @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
     *     @OA\Parameter(name="name", in="query", description="The name of the version in the language that it's written in", @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start")),
     *     @OA\Parameter(name="sort_by", in="query", description="The name of the version in english", @OA\Schema(type="string",title="encoding")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_library_version"))
     *     )
     * )
     *
     * @OA\Schema (
     *     type="object",
     *     schema="v2_library_version",
     *     description="The various version ids in the old version 2 style",
     *     title="v2_library_version",
     *     @OA\Xml(name="v2_library_version"),
     *     @OA\Property(property="version_code",type="string",description="The abbreviated `BibleFileset` id created from the three letters identifier after the iso code"),
     *     @OA\Property(property="version_name",type="string",description="The name of the version in the language that it's written in"),
     *     @OA\Property(property="english_name",type="string",description="The name of the version in english")
     * )
     *
     * @return json
     */
    public function libraryVersion()
    {
        $code = checkParam('code', null, 'optional');
        $name = checkParam('name', null, 'optional');
        $sort = checkParam('sort_by', null, 'optional');

        $versions = BibleFileset::where('asset_id', config('filesystems.disks.s3_fcbh.bucket'))
            ->with('bible.currentTranslation', 'bible.vernacularTranslation')
            ->when($code, function ($q) use ($code) {
                $q->where('id', $code);
            })->when($sort, function ($q) use ($sort) {
                $q->orderBy($sort);
            })->get();

        return $this->reply(fractal()->collection($versions)->transformWith(LibraryCatalogTransformer::class)->serializeWith($this->serializer));
    }
}
