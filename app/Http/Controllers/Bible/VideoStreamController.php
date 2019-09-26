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

        $fileset = BibleFileset::uniqueFileset($id, $asset_id)->select('hash_id', 'id')->first();
        if (!$fileset) {
            return $this->setStatusCode(404)->replyWithError('No fileset found for the provided params');
        }

        $file = BibleFile::with('videoResolution')->where('hash_id', $fileset->hash_id)->where('id', $file_id)->first();
        if (!$file) {
            return $this->replyWithError(trans('api.bible_file_errors_404', ['id' => $file_id]));
        }

        $current_file = '#EXTM3U';
        foreach ($file->videoResolution as $resolution) {
            $current_file .= "\n#EXT-X-STREAM-INF:PROGRAM-ID=1,BANDWIDTH=$resolution->bandwidth,RESOLUTION=" . $resolution->resolution_width . "x$resolution->resolution_height,CODECS=\"$resolution->codec\"\n$resolution->file_name" . '?key=' . $this->key . '&v=4&asset_id=' . $asset_id;
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

        $fileset = BibleFileset::uniqueFileset($fileset_id, $asset_id, 'video_stream')->select('hash_id', 'id', 'asset_id')->first();
        if (!$fileset) {
            return $this->setStatusCode(404)->replyWithError('No fileset found for the provided params');
        }

        $file = BibleFile::with('videoResolution.transportStream')->whereId($file_id)->first();
        if (!$file) {
            return $this->replyWithError(trans('api.bible_file_errors_404', ['id' => $file_id]));
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
            'Content-Disposition' => 'attachment; filename="' . $file->file_name . '"',
            'Content-Type'        => 'application/x-mpegURL'
        ]);
    }

    public function jesusFilmsLanguages()
    {
        return collect($this->fetchArclight('media-languages', false)->mediaLanguages)->pluck('languageId', 'iso3')->toArray();
    }

    public function jesusFilmChapters()
    {
        $iso = checkParam('iso');
        if ($iso) {
            $cache_string =  'arclight_languages';
            $languages = \Cache::remember($cache_string, now()->addDay(), function () {
                $languages = collect($this->fetchArclight('media-languages', false)->mediaLanguages)->pluck('languageId', 'iso3')->toArray();
                return $languages;
            });
            if (!isset($languages[$iso])) {
                return $this->setStatusCode(404)->replyWithError('No language could be found for the iso code specified');
            }
            $arclight_id = $languages[$iso];
        } else {
            $arclight_id = checkParam('arclight_id', true);
        }

        $cache_string =  'arclight_chapters_' . $arclight_id;

        $component = \Cache::remember($cache_string, now()->addDay(), function () use ($arclight_id) {
            $component = $this->fetchArclight('media-components/1_jf-0-0/languages/' . $arclight_id);
            return $component;
        });

        $cache_string =  'arclight_chapters_language_tag' . $arclight_id;

        $media_languages = \Cache::remember($cache_string, now()->addDay(), function () use ($arclight_id) {
            $media_languages = $this->fetchArclight('media-languages/' . $arclight_id);
            return $media_languages;
        });

        $cache_string =  'arclight_chapters_metadata' . $arclight_id;

        $metadataLanguageTag = isset($media_languages->bcp47) ? $media_languages->bcp47 : '';

        $metadata = \Cache::remember($cache_string, now()->addDay(), function () use ($arclight_id, $metadataLanguageTag) {
            $media_components = $this->fetchArclight('media-components', $arclight_id, true, 'metadataLanguageTags=' . $metadataLanguageTag . ',en');
            $metadata = collect($media_components->mediaComponents)
                ->map(function ($component) use ($arclight_id) {
                    return [
                        'mediaComponentId' => $component->mediaComponentId,
                        'meta' => [
                            'thumbnail' => $component->imageUrls->thumbnail,
                            'thumbnail_high' => $component->imageUrls->mobileCinematicHigh,
                            'title' => $component->title,
                            'shortDescription' => $component->shortDescription,
                            'longDescription' => $component->longDescription,
                            'file_name' => route('v4_video_jesus_film_file', [
                                'chapter_id'  => $component->mediaComponentId,
                                'arclight_id' => $arclight_id,
                                'v'           => $this->v,
                                'key'         => $this->key
                            ])
                        ]
                    ];
                })->pluck('meta', 'mediaComponentId');
            return $metadata;
        });

        return $this->reply([
            'verses'                   => $this->getIdReferences($component->mediaComponentId),
            'meta'                     => $metadata,
            'duration_in_milliseconds' => $component->lengthInMilliseconds,
            'file_name' => route('v4_video_jesus_film_file', [
                'chapter_id'  => $component->mediaComponentId,
                'arclight_id' => $arclight_id,
                'v'           => $this->v,
                'key'         => $this->key
            ])
        ]);
    }

    public function jesusFilmFile()
    {
        $language_id  = checkParam('arclight_id', true);
        $chapter_id   = checkParam('chapter_id') ?? '1_jf-0-0';

        $cache_string = 'arclight_media_components_' . $chapter_id . $language_id;
        $stream_file  = \Cache::remember($cache_string, now()->addDay(), function () use ($chapter_id, $language_id) {
            $media_components = $this->fetchArclight('media-components/' . $chapter_id . '/languages/' . $language_id, $language_id, false);
            return file_get_contents($media_components->streamingUrls->m3u8[0]->url);
        });

        return response($stream_file, 200, [
            'Content-Disposition' => 'attachment',
            'Content-Type'        => 'application/x-mpegURL'
        ]);
    }
}
