<?php

namespace Tests\Integration;

use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleVerse;
use App\Models\Bible\Book;
use App\Models\User\AccessGroup;
use App\Models\User\Key;
use App\Traits\AccessControlAPI;

class BibleTextTest extends ApiV4Test
{

    use AccessControlAPI;

    /* - Feature -------------------------*/

    public function bookAndChapterParamsReturnFilteredResult()
    {
        // Get information that should return valid filtered results
        $fileset = BibleFileset::where('set_type_code', 'text_plain')->inRandomOrder()->select('hash_id', 'id')->first();
        $reference = BibleVerse::where('hash_id', $fileset->hash_id)->inRandomOrder()->first();
        $path = route('v4_bible_filesets.chapter', ['fileset_id' => $fileset->id, 'book_id' => 'Gen', 'limit' => 5] + $this->params);

        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }




    /* - Swagger -------------------------*/
    /**
     * @category V4_API
     * @category Route Name: v4_bible_filesets.chapter
     * @category Route Path: https://api.dbp.test/bibles/filesets/ENGKJV/GEN/1?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\TextController::index
     * @group    V4
     * @test
     */
    public function bibleFilesetsChapter()
    {
        $access_control = $this->accessControl($this->key);

        $fileset = BibleFileset::with('files')->whereIn('hash_id', $access_control->hashes)->where('set_type_code', 'text_plain')->inRandomOrder()->first();
        $bible_verse = BibleVerse::where('hash_id',$fileset->hash_id)->inRandomOrder()->first();

        $this->params = array_merge([
            'fileset_id' => $fileset->id,
            'book_id'    => @$bible_verse->book_id,
            'chapter'    => @$bible_verse->chapter,
            'asset_id'   => $fileset->asset_id,
            'type'       => 'text_plain'
        ], $this->params);

        $path = route('v4_filesets.chapter', $this->params);

        echo "\nTesting: $path";

        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }


    /**
     *
     * @category V2_Text
     * @see \app\Http\Controllers\TextController::index()
     * @category Swagger ID: TextVerse
     * @category Route Name: v2_text_verse
     * @link Route Path: https://api.dbp.test/text/verse?v=2&key=1234&dam_id=ENGESV&book_id=GEN&chapter_id=1&verse_start=1&verse_end=10
     * @test
    */
    public function text_verse_allowed()
    {
        $access_control = $this->accessControl($this->params['key']);
        $fileset = BibleFileset::with('files')->whereIn('hash_id', $access_control->hashes)->where('set_type_code', 'text_plain')->inRandomOrder()->first();
        $bible_verse = BibleVerse::where('hash_id',$fileset->hash_id)->inRandomOrder()->first();

        $this->params['dam_id']      = $fileset->id;
        $this->params['book_id']     = $bible_verse->book;
        $this->params['chapter_id']  = $bible_verse->chapter;
        $this->params['verse_start'] = $bible_verse->verse_start;
        $this->params['verse_end']   = $bible_verse->verse_end;

        echo "\nTesting: " . route('v2_text_verse', $this->params);
        $response = $this->get(route('v2_text_verse', $this->params));
        $response->assertSuccessful();
    }
}
