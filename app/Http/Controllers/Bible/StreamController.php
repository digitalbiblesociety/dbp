<?php

namespace App\Http\Controllers\Bible;

use App\Http\Controllers\APIController;
use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFileset;
use App\Traits\CallsBucketsTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class StreamController extends APIController
{
    use CallsBucketsTrait;

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

        $fileset = BibleFileset::uniqueFileset($id, $asset_id)->select('hash_id', 'id')->first();
        if (!$fileset) {
            return $this->setStatusCode(404)->replyWithError('No fileset found for the provided params');
        }

        $file = BibleFile::with('streamBandwidth')->where('hash_id', $fileset->hash_id)->where('id', $file_id)->first();
        if (!$file) {
            return $this->replyWithError(trans('api.bible_file_errors_404', ['id' => $file_id]));
        }

        $current_file = '#EXTM3U';
        foreach ($file->streamBandwidth as $bandwidth) {
            $current_file .= "\n#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH=$bandwidth->bandwidth";
            if ($bandwidth->resolution_width) {
                $current_file .= ',RESOLUTION=' . $bandwidth->resolution_width . "x$bandwidth->resolution_height";
            }
            if ($bandwidth->codec) {
                $current_file .= ",CODECS=\"$bandwidth->codec\"";
            }
            $current_file .= "\n$bandwidth->file_name" . '?key=' . $this->key . '&v=4&asset_id=' . $asset_id;
        }

        return response($current_file, 200, [
            'Content-Disposition' => 'attachment; filename="' . $file->file_name . '"',
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

        $video_fileset = BibleFileset::uniqueFileset($fileset_id, $asset_id, 'video_stream')->select('hash_id', 'id', 'asset_id')->first();
        $audio_fileset = BibleFileset::uniqueFileset($fileset_id, $asset_id, 'audio_stream')->select('hash_id', 'id', 'asset_id')->first();
        if (!$video_fileset && !$audio_fileset) {
            return $this->setStatusCode(404)->replyWithError('No fileset found for the provided params');
        }

        if ($audio_fileset) {
            $fileset = $audio_fileset;
            $fileset_type = 'audio';
        } else {
            $fileset = $video_fileset;
            $fileset_type = 'video';
        }

        $file = BibleFile::with('streamBandwidth.transportStream')->whereId($file_id)->first();
        if (!$file) {
            return $this->replyWithError(trans('api.bible_file_errors_404', ['id' => $file_id]));
        }

        $bible_path    = $fileset->bible->first() !== null ? $fileset->bible->first()->id . '/' : '';

        $currentBandwidth = $file->streamBandwidth->where('file_name', $file_name)->first();
        if (!$currentBandwidth) {
            return $this->setStatusCode(404)->replyWithError(trans('api.file_errors_404_size'));
        }
        $transaction_id = random_int(0, 10000000);
        try {
            apiLogs(request(), $response->getStatusCode(), $transaction_id);
        } catch (\Exception $e) {
            Log::error($e);
        }

        $current_file = "#EXTM3U\n#EXT-X-VERSION:3\n#EXT-X-MEDIA-SEQUENCE:0\n#EXT-X-ALLOW-CACHE:YES\n#EXT-X-TARGETDURATION:" . ceil($currentBandwidth->transportStream->max('runtime'));

        foreach ($currentBandwidth->transportStream as $stream) {
            $current_file_path = $this->signedUrl($fileset_type . '/' . $bible_path . $fileset->id . '/' . $stream->file_name, $fileset->asset_id, $transaction_id);
            $current_file .= "\n#EXTINF:$stream->runtime\n$current_file_path";
        }
        $current_file .= "\n#EXT-X-ENDLIST";

        return response($current_file, 200, [
            'Content-Disposition' => 'attachment; filename="' . $file->file_name . '"',
            'Content-Type'        => 'application/x-mpegURL'
        ]);
    }
}
