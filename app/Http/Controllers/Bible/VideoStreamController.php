<?php

namespace App\Http\Controllers\Bible;

use App\Http\Controllers\APIController;
use App\Traits\ArclightConnection;

class VideoStreamController extends APIController
{
    use ArclightConnection;

    public function jesusFilmsLanguages()
    {
        $show_detail = checkBoolean('show_detail');
        $metadata_tag = checkParam('metadata_tag') ?? 'en';
        if (!$show_detail) {
            return collect($this->fetchArclight('media-languages', false)->mediaLanguages)->pluck('languageId', 'iso3')->toArray();
        }

        $cache_params = [$metadata_tag];
        $languages = cacheRemember('arclight_languages_detail', $cache_params, now()->addDay(), function () use ($metadata_tag) {
            $languages = collect($this->fetchArclight('media-languages', false, false, 'contentTypes=video&metadataLanguageTags=' . $metadata_tag . ',en')->mediaLanguages);
            return $languages->where('counts.speakerCount.value', '>', 0)->map(function ($language) {
                return [
                    'jesus_film_id' => $language->languageId,
                    'iso' => $language->iso3,
                    'name' => $language->name,
                    'autonym' => $language->nameNative
                ];
            })->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->values()->all();
        });

        return $languages;
    }

    public function jesusFilmChapters($iso = null)
    {
        $iso = checkParam('iso') ?? $iso;
        if ($iso) {
            $languages = cacheRemember('arclight_languages', [$iso], now()->addDay(), function () use ($iso) {
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

        $component = cacheRemember('arclight_chapters', [$arclight_id], now()->addDay(), function () use ($arclight_id) {
            $component = $this->fetchArclight('media-components/1_jf-0-0/languages/' . $arclight_id);
            return $component;
        });

        if (!$component) {
            return $this->setStatusCode(404)->replyWithError('Jesus Film component not found');
        }

        $media_languages = cacheRemember('arclight_chapters_language_tag', [$arclight_id], now()->addDay(), function () use ($arclight_id) {
            $media_languages = $this->fetchArclight('media-languages/' . $arclight_id);
            return $media_languages;
        });

        $metadataLanguageTag = isset($media_languages->bcp47) ? $media_languages->bcp47 : '';
        $cache_params =  [$arclight_id, $metadataLanguageTag];

        $metadata = cacheRemember('arclight_chapters_metadata', $cache_params, now()->addDay(), function () use ($arclight_id, $metadataLanguageTag) {
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

        $cache_params = [$chapter_id, $language_id];
        $stream_file  = cacheRemember('arclight_media_components', $cache_params, now()->addDay(), function () use ($chapter_id, $language_id) {
            $media_components = $this->fetchArclight('media-components/' . $chapter_id . '/languages/' . $language_id, $language_id, false);
            return file_get_contents($media_components->streamingUrls->m3u8[0]->url);
        });

        return response($stream_file, 200, [
            'Content-Disposition' => 'attachment',
            'Content-Type'        => 'application/x-mpegURL'
        ]);
    }
}
