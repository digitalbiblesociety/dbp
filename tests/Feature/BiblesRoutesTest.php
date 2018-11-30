<?php

namespace Tests\Feature;

use App\Models\Bible\BibleFileset;
use App\Models\Bible\Book;

class BiblesRoutesTest extends ApiV4Test
{

    /**
     * @category V4_API
     * @category Route Name: v4_bible_filesets.types
     * @category Route Path: https://api.dbp.test/bibles/filesets/media/types?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BibleFileSetsController::mediaTypes
     * @group    V4
     * @test
     */
    public function bibleFilesetsTypes()
    {
        $path = route('v4_bible_filesets.types', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_bible_filesets.podcast
     * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/podcast?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BibleFileSetsController::podcast
     * @group    V4
     * @test
     */
    public function bibleFilesetsPodcast()
    {
        $path = route('v4_bible_filesets.podcast', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_bible_filesets.download
     * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/download?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BibleFileSetsController::download
     * @group    V4
     * @test
     */
    public function bibleFilesetsDownload()
    {
        $path = route('v4_bible_filesets.download', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_bible_filesets.copyright
     * @category Route Path: https://api.dbp.test/bibles/filesets/ENGESV/copyright?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BibleFileSetsController::copyright
     * @group    V4
     * @test
     */
    public function bibleFilesetsCopyright()
    {
        $params = array_merge(['fileset_id' => 'UBUANDP2DA','type' => 'audio_drama'], $this->params);
        $path = route('v4_bible_filesets.copyright', $params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_bible_filesets.books
     * @category Route Path: https://api.dbp.test/bibles/filesets/ENGESV/books?v=4&key={key}&fileset_type=text_plain
     * @see      \App\Http\Controllers\Bible\BooksController::show
     * @group    V4
     * @test
     */
    public function bibleFilesetsBooks()
    {
        $params = array_merge(['fileset_id' => 'ENGESV', 'fileset_type' => 'text_plain'], $this->params);
        $path = route('v4_bible_filesets.books', $params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

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
     * @category V4_API
     * @category Route Name: v4_bible_filesets.show
     * @category Route Path: https://api.dbp.test/bibles/filesets/ENGESV?v=4&key={key}&type=text_plain&bucket=dbp-prod
     * @see      \App\Http\Controllers\Bible\BibleFileSetsController::show
     * @group    V4
     * @test
     */
    public function bibleFilesetsShow()
    {
        $path = route('v4_bible_filesets.show', array_merge(['fileset_id' => 'ACHBSU','book_id' => 'MAT', 'chapter' => 1,'type' => 'text_format', 'bucket' => 'dbp-prod'], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_bible.links
     * @category Route Path: https://api.dbp.test/bibles/links?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BibleLinksController::index
     * @group    V4
     * @test
     */
    public function bibleLinks()
    {
        $path = route('v4_bible.links', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_bible_books_all
     * @category Route Path: https://api.dbp.test/bibles/books/?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BooksController::index
     * @group    V4
     * @test
     */
    public function bibleBooksAll()
    {
        $path = route('v4_bible_books_all', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_bible_equivalents.all
     * @category Route Path: https://api.dbp.test/bible/equivalents?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BibleEquivalentsController::index
     * @group    V4
     * @test
     */
    public function bibleEquivalentsAll()
    {
        $path = route('v4_bible_equivalents.all', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_bible.books
     * @category Route Path: https://api.dbp.test/bibles/ENGESV/book?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BiblesController::books
     * @group    V4
     * @test
     */
    public function bibleBooks()
    {
        $path = route('v4_bible.books', array_merge(['bible_id' => 'ENGESV', 'book' => 'MAT'], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_bible.archival
     * @category Route Path: https://api.dbp.test/bibles/archival?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BiblesController::archival
     * @group    V4
     * @test
     */
    public function bibleArchival()
    {
        $path = route('v4_bible.archival', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_bible.one
     * @category Route Path: https://api.dbp.test/bibles/{bible_id}?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BiblesController::show
     * @group    V4
     * @test
     */
    public function bibleOne()
    {
        $path = route('v4_bible.one', array_add($this->params, 'bible_id', 'ENGESV'));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_bible.all
     * @category Route Path: https://api.dbp.test/bibles?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BiblesController::index
     * @group    V4
     * @test
     */
    public function bibleAll()
    {
        $path = route('v4_bible.all', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }
}
