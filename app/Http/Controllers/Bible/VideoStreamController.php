<?php

namespace App\Http\Controllers\Bible;

use App\Http\Controllers\APIController;
use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFileset;
use App\Traits\CallsBucketsTrait;

class VideoStreamController extends APIController
{

    use CallsBucketsTrait;

    /**
     *
     * Generate the parent m3u8 file which contains the various resolution m3u8 files
     *
     * @param null $fileset_id
     * @param null $file_id
     *
     * @return $this
     */
    public function index($fileset_id = null, $file_id = null)
    {
        $fileset = BibleFileset::where('id', $fileset_id)->select(['id','hash_id'])->first();
        if (!$fileset) {
            return $this->replyWithError(trans('api.bible_fileset_errors_404', ['id' => $fileset_id]));
        }
        $file = BibleFile::with('videoResolution')->where('hash_id', $fileset->hash_id)->where('id', $file_id)->first();
        if (!$file) {
            return $this->replyWithError(trans('api.bible_file_errors_404', ['id'=> $file_id]));
        }

        $current_file = '#EXTM3U';
        foreach ($file->videoResolution as $resolution) {
            $current_file .= "\n#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH=$resolution->bandwidth,RESOLUTION=".$resolution->resolution_width."x$resolution->resolution_height,CODECS=\"$resolution->codec\"\n$resolution->file_name" . '?key=' . $this->key . '&v=4';
        }
        return response($current_file, 200)->header('Content-Disposition', 'attachment; filename="'.$file->file_name.'"')->header('Content-Type', 'application/x-mpegURL');
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
     */
    public function transportStream($fileset_id = null, $file_id = null, $file_name = null)
    {
        $fileset = BibleFileset::with('bible')->where('id', $fileset_id)->select(['id','hash_id','asset_id'])->first();
        if (!$fileset) {
            return $this->replyWithError(trans('api.bible_fileset_errors_404', ['id' => $fileset_id]));
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
            apiLogs(request(), $this->getStatusCode(), $transaction_id);
        } catch (\Exception $e) {
            Log::error($e);
        }

        foreach ($currentResolution->transportStream as $stream) {
            $current_file_path = $this->signedUrl('video' . '/' . $bible_path . $fileset->id . '/' . $stream->file_name, $fileset->asset_id, $transaction_id);
            $current_file .= "\n#EXTINF:$stream->runtime\n$current_file_path";
        }
        $current_file .= "\n#EXT-X-ENDLIST";

        return response($current_file, 200)->header('Content-Disposition', 'attachment; filename="'.$file->file_name.'"')->header('Content-Type', 'application/x-mpegURL');
    }
}
