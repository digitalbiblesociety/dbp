<?php

namespace App\Http\Controllers\Bible;

use App\Http\Controllers\APIController;
use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFileset;
use App\Traits\CallsBucketsTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Traits\ArclightConnection;
class VideoStreamController extends APIController
{

    use CallsBucketsTrait;
    use ArclightConnection;

    /**
     *
     * Generate the parent m3u8 file which contains the various resolution m3u8 files
     *
     * @param null $id
     * @param null $file_id
     *
     * @return $this
     */
    public function index($id = null, $file_id = null)
    {
        $asset_id = checkParam('asset_id') ?? config('filesystems.disks.s3_fcbh_video.bucket');

        $fileset = BibleFileset::uniqueFileset($id, $asset_id)->select('hash_id','id')->first();
        if (!$fileset) {
            return $this->setStatusCode(404)->replyWithError('No fileset found for the provided params');
        }

        $file = BibleFile::with('videoResolution')->where('hash_id', $fileset->hash_id)->where('id', $file_id)->first();
        if (!$file) {
            return $this->replyWithError(trans('api.bible_file_errors_404', ['id'=> $file_id]));
        }

        $current_file = '#EXTM3U';
        foreach ($file->videoResolution as $resolution) {
            $current_file .= "\n#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH=$resolution->bandwidth,RESOLUTION=".$resolution->resolution_width."x$resolution->resolution_height,CODECS=\"$resolution->codec\"\n$resolution->file_name" . '?key=' . $this->key . '&v=4&asset_id='.$asset_id;
        }

        return response($current_file, 200, [
            'Content-Disposition' => 'attachment; filename="'.$file->file_name.'"',
            'Content-Type'        => 'application/x-mpegURL'
        ]);
    }

    /**
     *
     * Deliver the ts files referenced by file created by the generated m3u8
     *
     * @param null $fileset_id
     * @param null $file_id
     * @param null $file_name
     *
     * @return $this
     * @throws \Exception
     */
    public function transportStream(Response $response, $fileset_id = null, $file_id = null, $file_name = null)
    {
        $asset_id = checkParam('asset_id') ?? config('filesystems.disks.s3_fcbh_video.bucket');

        $fileset = BibleFileset::uniqueFileset($fileset_id, $asset_id, 'video_stream')->select('hash_id','id','asset_id')->first();
        if (!$fileset) {
            return $this->setStatusCode(404)->replyWithError('No fileset found for the provided params');
        }

        $file = BibleFile::with('videoResolution.transportStream')->whereId($file_id)->first();
        if (!$file) {
            return $this->replyWithError(trans('api.bible_file_errors_404', ['id'=> $file_id]));
        }

        $bible_path    = $fileset->bible->first() !== null ? $fileset->bible->first()->id . '/' : '';
        $current_file = "#EXTM3U\n#EXT-X-VERSION:3\n#EXT-X-MEDIA-SEQUENCE:0\n#EXT-X-ALLOW-CACHE:YES\n#EXT-X-TARGETDURATION:4";

        $currentResolution = $file->videoResolution->where('file_name', $file_name)->first();
        if (!$currentResolution) {
            return $this->setStatusCode(404)->replyWithError(trans('api.file_errors_404_size'));
        }
        $transaction_id = random_int(0, 10000000);
        try {
            apiLogs(request(), $response->getStatusCode(), $transaction_id);
        } catch (\Exception $e) {
            Log::error($e);
        }

        foreach ($currentResolution->transportStream as $stream) {
            $current_file_path = $this->signedUrl('video' . '/' . $bible_path . $fileset->id . '/' . $stream->file_name, $fileset->asset_id, $transaction_id);
            $current_file .= "\n#EXTINF:$stream->runtime\n$current_file_path";
        }
        $current_file .= "\n#EXT-X-ENDLIST";

        return response($current_file, 200, [
            'Content-Disposition' => 'attachment; filename="'.$file->file_name.'"',
            'Content-Type'        => 'application/x-mpegURL'
        ]);
    }

    public function jesusFilms()
    {
        return collect($this->fetchArclight('media-languages')->mediaLanguages)->pluck('languageId', 'iso3')->toArray();
    }

    public function jesusFilmStream()
    {
        $iso = checkParam('iso', true);

        $chapter_verse_ref = implode(',', array_keys($this->getIdReferences()));
        $arclight_language = LanguageCode::whereHas('language', function($query) use($iso) {
            $query->where('iso', $iso);
        })->where('source','arclight')->select('code')->first()->code;

        return $this->fetchArclight('media-components/', [
            'platform'    => 'ios',
            'ids'         => $chapter_verse_ref,
            'languageIds' => $arclight_language
        ]);
    }

}
