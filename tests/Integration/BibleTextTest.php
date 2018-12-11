<?php

namespace Tests\Integration;

use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleVerse;
use App\Models\Bible\Book;
use App\Models\User\AccessGroup;

class BibleTextTest extends ApiV4Test
{
    protected $params = ['key' => 'tighten_37518dau8gb891ub', 'v' => '4'];

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
        $random_bible = collect(\DB::connection('sophia')->select('SHOW TABLES'))
            ->pluck('Tables_in_sophia')->filter(function ($table) {
                return str_contains($table, '_vpl');
            })->random();
        $reference = \DB::connection('sophia')->table($random_bible)->inRandomOrder()->first();
        $fileset = BibleFileset::where('id', substr($random_bible, 0, -4))
                               ->where('set_type_code', 'text_plain')->first();
        $book = Book::where('id_usfx', $reference->book)->first();

        $this->params = array_merge([
            'fileset_id' => $fileset->id,
            'book_id'    => $book->id,
            'chapter'    => $reference->chapter,
            'asset_id'   => $fileset->asset_id
        ], $this->params);

        $path = route('v4_bible_filesets.chapter', $this->params);
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
        $public_domain_access_group = AccessGroup::with('filesets')->where('name', 'PUBLIC_DOMAIN')->first();
        $fileset_hashes = $public_domain_access_group->filesets->pluck('hash_id');
        $fileset = BibleFileset::with('files')->whereIn('hash_id', $fileset_hashes)->where('set_type_code', 'text_plain')->inRandomOrder()->first();

        $file = \DB::connection('sophia')->table(strtoupper($fileset->id).'_vpl')->inRandomOrder()->take(1)->first();

        $this->params['dam_id']      = $fileset->id;
        $this->params['book_id']     = $file->book;
        $this->params['chapter_id']  = $file->chapter;
        $this->params['verse_start'] = $file->verse_start;
        $this->params['verse_end']   = $file->verse_end;

        echo "\nTesting: " . route('v2_text_verse', $this->params);
        $response = $this->get(route('v2_text_verse', $this->params));
        $response->assertSuccessful();
    }
}
