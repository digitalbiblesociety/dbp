<?php

namespace App\Http\Controllers\Connections\V2Controllers\LibraryCatalog;

use App\Http\Controllers\APIController;
use App\Models\Language\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Bible\BibleFileset;
use App\Transformers\V2\LibraryCatalog\LibraryCatalogTransformer;

class LibraryVersionController extends APIController
{

    /**
     *
     * Get the list of versions defined in the system
     *
     * @OA\Get(
     *     path="/library/version",
     *     tags={"Library Catalog"},
     *     summary="Returns Audio File path information",
     *     description="This call returns the file path information for audio files for a volume. This information can
               be used with the response of the /audio/location call to create a URI to retrieve the audio files.",
     *     operationId="v2_library_version",
     *     @OA\Parameter(ref="#/components/parameters/version_number"),
     *     @OA\Parameter(ref="#/components/parameters/key"),
     *     @OA\Parameter(ref="#/components/parameters/pretty"),
     *     @OA\Parameter(ref="#/components/parameters/format"),
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id"),
     *         description="The abbreviated `BibleFileset` id created from the letters after the iso",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         @OA\Schema(ref="#/components/schemas/BibleFile/properties/chapter_start"),
     *         description="The name of the version in the language that it's written in"
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         @OA\Schema(type="string",title="encoding"),
     *         description="The name of the version in english"
     *     ),
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
     *     @OA\Property(
     *         property="version_code",
     *         type="string",
     *         description="The abbreviated `BibleFileset` id created from letters after the iso code"),
     *     @OA\Property(
     *         property="version_name",
     *         type="string",
     *         description="The name of the version in the language that it's written in"),
     *     @OA\Property(
     *         property="english_name",
     *         type="string",
     *         description="The name of the version in english")
     * )
     *
     * @return JsonResponse
     */
    public function libraryVersion()
    {
        $code = checkParam('code');
        $name = checkParam('name');
        $sort = checkParam('sort_by');

        $versions = \Cache::remember('libraryVersion'.$code.$name.$sort,2800, function() use($code, $sort, $name) {

            $english_id = Language::where('iso','eng')->first()->id ?? '6414';

            $versions = \DB::connection(config('database.connections.dbp.database'))->table('bible_filesets')
                ->where('asset_id', config('filesystems.disks.s3_fcbh.bucket'))
                ->rightJoin('bible_fileset_connections as bibles', 'bibles.hash_id', 'bible_filesets.hash_id')
                ->join('bible_translations as ver_title', function ($join) use($name) {
                    $join->on('ver_title.bible_id', 'bibles.bible_id')->where('ver_title.vernacular', 1);
                })
                ->join('bible_translations as eng_title', function ($join) use ($english_id, $name) {
                    $join->on('eng_title.bible_id', 'bibles.bible_id')->where('eng_title.language_id', $english_id);
                })
                ->when($code, function ($q) use ($code) {
                    $q->where('id', $code);
                })->when($sort, function ($q) use ($sort) {
                    $q->orderBy($sort,'asc');
                })->select([
                    'eng_title.name as eng_title',
                    'ver_title.name as ver_title',
                    'bible_filesets.id'
                ])->get();

            if ($name) {
                $subsetVersions = $versions->where('eng_title',$name)->first();
                if(!$subsetVersions) {
                    $subsetVersions = $versions->where('ver_title',$name)->first();
                }
                $versions = $subsetVersions;
            }

            return $versions;
        });

        return $this->reply($versions);
    }
}
