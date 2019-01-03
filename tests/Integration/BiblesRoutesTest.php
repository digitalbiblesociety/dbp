<?php

namespace Tests\Integration;

use App\Models\Bible\BibleEquivalent;
use App\Models\Bible\BibleFile;
use App\Models\Bible\BibleFileset;
use App\Traits\AccessControlAPI;

class BiblesRoutesTest extends ApiV4Test
{

    use AccessControlAPI;


    /**
     * @category V4_API
     * @category Route Name: v4_filesets.types
     * @category Route Path: https://api.dbp.test/bibles/filesets/media/types?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BibleFileSetsController::mediaTypes
     * @group    BibleRoutes
     * @group    V4
     * @test
     */
    public function bibleFilesetsTypes()
    {
        $path = route('v4_filesets.types', $this->params);
        echo "\nTesting: $path";

        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_filesets.podcast
     * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/podcast?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BibleFileSetsController::podcast
     * @group    BibleRoutes
     * @group    V4
     * @test
     */
    public function bibleFilesetsPodcast()
    {
        $path = route('v4_filesets.podcast', $this->params);
        echo "\nTesting: $path";

        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_filesets.download
     * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/download?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BibleFileSetsController::download
     * @group    BibleRoutes
     * @group    V4
     * @test
     */
    public function bibleFilesetsDownload()
    {
        $path = route('v4_filesets.download', $this->params);
        echo "\nTesting: $path";

        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_filesets.copyright
     * @category Route Path: https://api.dbp.test/bibles/filesets/ENGESV/copyright?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BibleFileSetsController::copyright
     * @group    BibleRoutes
     * @group    V4
     * @test
     */
    public function bibleFilesetsCopyright()
    {
        $params = array_merge(['fileset_id' => 'UBUANDP2DA','type' => 'audio_drama'], $this->params);
        $path = route('v4_filesets.copyright', $params);
        echo "\nTesting: $path";

        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_filesets.books
     * @category Route Path: https://api.dbp.test/bibles/filesets/ENGESV/books?v=4&key={key}&fileset_type=text_plain
     * @see      \App\Http\Controllers\Bible\BooksController::show
     * @group    BibleRoutes
     * @group    V4
     * @test
     */
    public function bibleFilesetsBooks()
    {
        $params = array_merge(['fileset_id' => 'ENGESV', 'fileset_type' => 'text_plain'], $this->params);
        $path = route('v4_filesets.books', $params);
        echo "\nTesting: $path";

        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }


    /**
     * @category V4_API
     * @category Route Name: v4_filesets.show
     * @category Route Path: https://api.dbp.test/bibles/filesets/ENGESV?v=4&key={key}&type=text_plain&bucket=dbp-prod
     * @see      \App\Http\Controllers\Bible\BibleFileSetsController::show
     * @group    BibleRoutes
     * @group    V4
     * @test
     */
    public function bibleFilesetsShow()
    {
        $access_control = $this->accessControl($this->key);
        $fileset = BibleFileset::whereIn('hash_id',$access_control->hashes)->where('set_type_code', 'text_format')->inRandomOrder()->first();
        $random_file = BibleFile::where('hash_id',$fileset->hash_id)->first();
        $path = route('v4_filesets.show', array_merge([
            'fileset_id' => $fileset->id,
            'book_id'    => $random_file->book_id,
            'chapter' => $random_file->chapter_start,
            'type' => 'text_format',
            'bucket' => $fileset->asset_id
        ], $this->params));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_bible.links
     * @category Route Path: https://api.dbp.test/bibles/links?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BibleLinksController::index
     * @group    BibleRoutes
     * @group    V4
     * @test
     */
    public function bibleLinks()
    {
        $path = route('v4_bible.links', array_add($this->params, 'iso', 'eng'));
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
    }

    /**
     * @category V4_API
     * @category Route Name: v4_bible_books_all
     * @category Route Path: https://api.dbp.test/bibles/books/?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BooksController::index
     * @group    BibleRoutes
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
     * @group    BibleRoutes
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

    /** @test */
    public function bibleEquivalentsCanBeFilteredByBible()
    {
        $bible_path = route('v4_bible_equivalents.all', array_merge(['bible_id' => 'ENGESV'], $this->params));
        $response = $this->withHeaders($this->params)->get($bible_path);
        $response->assertSuccessful();
    }

    /** @test */
    public function bibleEquivalentsCanBeFilteredByOrganization()
    {
        $bible_equivalents = BibleEquivalent::inRandomOrder()->first();
        $org_path = route('v4_bible_equivalents.all', array_merge(['organization_id' => $bible_equivalents->organization_id], $this->params));
        $response = $this->withHeaders($this->params)->get($org_path);
        $response->assertSuccessful();

        $content = collect(json_decode($response->getContent()))->pluck('organization_id')->unique();
        $this->assertEquals($content->count(), 1);
        $this->assertEquals($content[0], $bible_equivalents->organization_id);
    }


    /**
     * @category V4_API
     * @category Route Name: v4_bible.books
     * @category Route Path: https://api.dbp.test/bibles/ENGESV/book?v=4&key={key}
     * @see      \App\Http\Controllers\Bible\BiblesController::books
     * @group    BibleRoutes
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
     * @group    BibleRoutes
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
     * @group    BibleRoutes
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
     * @group    BibleRoutes
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
