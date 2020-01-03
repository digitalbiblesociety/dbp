<?php

namespace App\Http\Controllers\Bible;

use App\Http\Controllers\APIController;
use App\Transformers\FileSetTransformer;

use App\Models\Bible\BibleFileset;

class BibleFilesetsPodcastController extends APIController
{
    /**
     *
     * @OA\Get(
     *     path="/bibles/filesets/{fileset_id}/podcast",
     *     tags={"Bibles"},
     *     summary="Audio Filesets as Podcasts",
     *     description="An audio Fileset in an RSS format suitable for consumption by iTunes",
     *     operationId="v4_bible_filesets.podcast",
     *     @OA\Parameter(name="fileset_id", in="path", required=true, description="The fileset ID", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
     *     @OA\Response(
     *         response=200,
     *         description="The requested fileset as a rss compatible xml podcast",
     *         @OA\MediaType(mediaType="application/xml")
     *     )
     * )
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index($id)
    {
        $asset_id = checkParam('bucket|bucket_id|asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');
        $fileset  = BibleFileset::with('translations', 'files.currentTitle', 'bible.books')->uniqueFileset($id, $asset_id, 'audio', true)->first();
        if (!$fileset) {
            return $this->replyWithError(trans('api.bible_fileset_errors_404'));
        }

        return $this->reply(fractal($fileset, new FileSetTransformer(), $this->serializer), [
            'rootElementName' => 'rss',
            'rootAttributes' => [
                'xmlns:itunes' => 'http://www.itunes.com/dtds/podcast-1.0.dtd',
                'xmlns:atom' => 'http://www.w3.org/2005/Atom',
                'xmlns:media' => 'http://search.yahoo.com/mrss/',
                'version' => '2.0'
            ]
        ]);
    }
}
