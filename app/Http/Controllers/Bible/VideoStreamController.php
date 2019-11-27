<?php

namespace App\Http\Controllers\Bible;

use App\Http\Controllers\APIController;
use App\Traits\ArclightConnection;

class VideoStreamController extends APIController
{
    use ArclightConnection;

    public function jesusFilmsLanguages()
    {
        return collect($this->fetchArclight('media-languages', false)->mediaLanguages)->pluck('languageId', 'iso3')->toArray();
    }

    public function jesusFilmChapters()
    {
        $iso = checkParam('iso');
        if ($iso) {
            $cache_string =  'arclight_languages' . $iso;
            $languages = \Cache::remember($cache_string, now()->addDay(), function () use ($iso) {
                $languages = collect($this->fetchArclight('media-languages', false, false, 'iso3=' . $iso)->mediaLanguages);
                $languages = $languages->where('counts.speakerCount.value', $languages->max('counts.speakerCount.value'))->keyBy('iso3')->map(function ($item) {
                    return $item->languageId;
                });
                return $languages;
            });

            $has_language = $languages->contains(function ($value, $key) use ($iso) {
                return $key === $iso;
            });

            if (!$has_language) {
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
