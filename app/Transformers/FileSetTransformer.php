<?php

namespace App\Transformers;

class FileSetTransformer extends BaseTransformer
{

    /**
     * A Fractal transformer.
     *
     * @param $audio
     *
     * @return array
     */
    public function transform($audio)
    {
        switch ((int) $this->version) {
            case 2:
            case 3:
                return $this->transformForV2($audio);
            case 4:
            default:
                return $this->transformForV4($audio);
        }
    }

    public function transformForV2($audio)
    {
        switch ($this->route) {
            case 'v2_audio_timestamps':
                return [
                    'verse_id'    => (string) $audio->verse_start,
                    'verse_start' => $audio->timestamp
                ];

            default:
            case 'v2_audio_path':
                return [
                    'book_id'    => ucfirst(strtolower($audio->book->id_osis)),
                    'chapter_id' => (string) $audio->chapter_start,
                    'path'       => $audio->file_name
                ];
        }
    }

    public function transformForV4($fileset)
    {
        switch ($this->route) {
            case 'v4_filesets.podcast':
                $bible = $fileset->bible->first();
                if (!$bible) {
                    return $this->replyWithError(trans('api.filesets_errors_404'));
                }
                $meta['channel']['title'] = $fileset->translations->where('iso', $bible->language->iso)->first()->name.' - '.$bible->language->name ?? $bible->where('iso', 'eng')->first()->name.' - '.$bible->language->name;
                $meta['channel']['link'] = config('app.url_podcast');
                $meta['channel']['atom:link']['_attributes'] = ['href'  => 'http://www.faithcomesbyhearing.com/feeds/audio-bibles/'.$bible->id.'.xml','rel'   => 'self','type'  => 'application/rss+xml'];
                $meta['channel']['description'] = $bible->translations->where('iso', $bible->language->iso)->first()->description ?? $bible->language->where('iso', 'eng')->first()->description;
                $meta['channel']['language'] = $bible->language->iso;
                $meta['channel']['managingEditor'] = 'adhooker@fcbhmail.org';
                $meta['channel']['webMaster'] = 'charles@faithcomesbyhearing.com';
                $meta['channel']['copyright'] = $bible->copyright;
                $meta['channel']['lastBuildDate'] = $bible->last_updated ? $fileset->last_updated->toRfc2822String() : '';
                //$meta['channel']['pubDate'] = ($bible->date) ? $fileset->date->toRfc2822String() : "";
                $meta['channel']['docs'] = 'http://blogs.law.harvard.edu/tech/rss';
                $meta['channel']['webMaster'] = config('app.contact');
                $meta['channel']['itunes:keywords'] = 'Bible, Testament, Jesus, Scripture, Holy, God, Heaven, Hell, Gospel, Christian, Bible.is, Church';
                $meta['channel']['itunes:author'] = 'Faith Comes By Hearing';
                $meta['channel']['itunes:subtitle'] = 'Online Audio Bible Recorded by Faith Comes By Hearing';
                $meta['channel']['itunes:explicit'] = 'no';
                $meta['channel']['itunes:owner']['itunes:name'] = 'Faith Comes By Hearing';
                $meta['channel']['itunes:owner']['itunes:email'] = config('app.contact');
                $meta['channel']['itunes:image'] = ['href' =>  $fileset->artwork_url];
                $meta['channel']['itunes:category'] = [
                    '_attributes' => ['text' => 'Religion & Spirituality']
                ];

                $meta['channel']['managingEditor'] = config('app.contact');
                $meta['channel']['image']['_attributes'] = [
                    'url'   => 'http://bible.is/'.$fileset->id.'.jpg',
                    'title' => 'Faith Comes by Hearing',
                    'link'  => 'http://bible.is',
                ];
                $meta['channel']['atom:link'] = [
                    'href' => 'http://bible.is/feed.xml',
                    'rel'  => 'self',
                    'type' => 'application/rss+xml'
                ];
                //$meta['channel']['pubDate'] = 'Sun, 01 Jan 2012 00:00:00 EST';
                //$meta['channel']['itunes:summary'] = 'Duplicate of above verbose description.';
                //$meta['channel']['itunes:subtitle'] = 'Short description of the podcast - 255 character max.';

                $items = [];
                $xml_safe_expression = '/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u';
                foreach ($fileset->files as $file) {
                    $file_name = optional($fileset->bible->first()->books->where('book_id', $file->book_id)->first())->name;
                    $items[] = [
                        'title'       => $file_name.' '.$file->chapter_start,
                        'link'        => "http://podcastdownload.faithcomesbyhearing.com/mp3.php/$fileset->id/$file->file_name",
                        'guid'        => "http://podcastdownload.faithcomesbyhearing.com/mp3.php/$fileset->id/$file->file_name",
                        //'description' => ($file->currentTitle) ? htmlspecialchars($file->currentTitle->title) : "",
                        'enclosure'   => [
                            'name'   => optional($file->currentTitle)->title,
                            '_attributes' => [
                                'url'    => "http://podcastdownload.faithcomesbyhearing.com/mp3.php/$fileset->id/$file->file_name.mp3",
                                'length' => $file->duration ?? 0,
                                'type'   => 'audio/mpeg'
                            ],
                        ],
                        'pubDate'              => $fileset->created_at->toRfc2822String() ?? '',
                        'itunes:author'        => 'Faith Comes By Hearing',
                        'itunes:explicit'      => 'no',
                        'itunes:subtitle'      => $file->currentTitle ? preg_replace($xml_safe_expression, ' ', $file->currentTitle->title) : '',
                        'itunes:summary'       => $file->currentTitle ? preg_replace($xml_safe_expression, ' ', $file->currentTitle->title) : '',
                        'itunes:duration'      => $file->duration ?? '0:00',
                        'itunes:keywords'      => 'Bible, Testament, Jesus, Scripture, Holy, God, Heaven, Hell, Gospel, Christian, Bible.is, Church'
                    ];
                }
                $meta['channel']['item'] = $items;
                return $meta;

            default:
                /**
                 * @OA\Schema (
                 *  type="array",
                 *  schema="v4_bible_filesets.show",
                 *  description="The minimized alphabet return for the all alphabets route",
                 *  title="v4_bible_filesets.show",
                 *  @OA\Xml(name="v4_bible_filesets.show"),
                 *  @OA\Items(
                 *          @OA\Property(property="book_id",        ref="#/components/schemas/BibleFile/properties/book_id"),
                 *          @OA\Property(property="book_name",      ref="#/components/schemas/BookTranslation/properties/name"),
                 *          @OA\Property(property="chapter_start",  ref="#/components/schemas/BibleFile/properties/chapter_start"),
                 *          @OA\Property(property="chapter_end",    ref="#/components/schemas/BibleFile/properties/chapter_end"),
                 *          @OA\Property(property="verse_start",    ref="#/components/schemas/BibleFile/properties/verse_start"),
                 *          @OA\Property(property="verse_end",      ref="#/components/schemas/BibleFile/properties/verse_end"),
                 *          @OA\Property(property="timestamp",      ref="#/components/schemas/BibleFileTimestamp/properties/timestamp"),
                 *          @OA\Property(property="path",           ref="#/components/schemas/BibleFile/properties/file_name"),
                 *     )
                 *   )
                 * )
                 */
                return [
                    'book_id'       => $fileset->book_id,
                    'book_name'     => $fileset->book_name,
                    'chapter_start' => $fileset->chapter_start,
                    'chapter_end'   => $fileset->chapter_end,
                    'verse_start'   => $fileset->verse_start,
                    'verse_end'     => $fileset->verse_end,
                    'timestamp'     => $fileset->timestamp,
                    'path'          => $fileset->file_name,
                    'duration'      => $fileset->duration
                ];
        }
    }
}
