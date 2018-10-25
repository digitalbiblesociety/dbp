<?php

namespace Tests\Feature;

class v4_biblesRoutesTest extends API_V4_Test
{

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.types
	 * @category Route Path: https://api.dbp.test/bibles/filesets/media/types?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController::mediaTypes
	 */
	public function test_v4_bible_filesets_types()
	{
		$path = route('v4_bible_filesets.types', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.podcast
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/podcast?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController::podcast
	 */
	public function test_v4_bible_filesets_podcast()
	{
		$path = route('v4_bible_filesets.podcast', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.download
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/download?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController::download
	 */
	public function test_v4_bible_filesets_download()
	{
		$path = route('v4_bible_filesets.download', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.copyright
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/copyright?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController::copyright
	 */
	public function test_v4_bible_filesets_copyright()
	{
		$path = route('v4_bible_filesets.copyright', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible.filesets
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}/books?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BooksController::show
	 */
	public function test_v4_bible_filesets()
	{
		$path = route('v4_bible.filesets', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.chapter
	 * @category Route Path: https://api.dbp.test/bibles/filesets/ENGKJV/GEN/1?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\TextController::index
	 */
	public function test_v4_bible_filesets_chapter()
	{
		$this->params = array_merge(['fileset_id' => 'ENGKJV','book_id' => 'GEN', 'chapter' => 1], $this->params);
		$path = route('v4_bible_filesets.chapter', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.show
	 * @category Route Path: https://api.dbp.test/bibles/filesets/ENGKJV?v=4&key=1234&type=plain_text&bucket=dbs-web
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController::show
	 */
	public function test_v4_bible_filesets_show()
	{
		$this->params = array_merge(['fileset_id' => 'ENGKJV','book_id' => 'GEN', 'chapter' => 1], $this->params);
		$path = route('v4_bible_filesets.show', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.update
	 * @category Route Path: https://api.dbp.test/bibles/filesets/{fileset_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController::update
	 */
	public function test_v4_bible_filesets_update()
	{
		$path = route('v4_bible_filesets.update', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_filesets.store
	 * @category Route Path: https://api.dbp.test/bibles/filesets/?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleFileSetsController::store
	 */
	public function test_v4_bible_filesets_store()
	{
		$path = route('v4_bible_filesets.store', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible.links
	 * @category Route Path: https://api.dbp.test/bibles/links?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleLinksController::index
	 */
	public function test_v4_bible_links()
	{
		$path = route('v4_bible.links', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible.allBooks
	 * @category Route Path: https://api.dbp.test/bibles/books/?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BooksController::index
	 */
	public function test_v4_bible_allBooks()
	{
		$path = route('v4_bible.allBooks', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_text_search'
	 * @category Route Path: https://api.dbp.test/search?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\TextController::search
	 */
	public function test_v4_text_search()
	{
		$path = route('v4_text_search', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_equivalents.all
	 * @category Route Path: https://api.dbp.test/bible/equivalents?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleEquivalentsController::index
	 */
	public function test_v4_bible_equivalents_all()
	{
		$path = route('v4_bible_equivalents.all', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible_equivalents.one
	 * @category Route Path: https://api.dbp.test/bibles/{bible_id}/equivalents?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BibleEquivalentsController::show
	 */
	public function test_v4_bible_equivalents_one()
	{
		$path = route('v4_bible_equivalents.one', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible.books
	 * @category Route Path: https://api.dbp.test/bibles/{bible_id}/book/{book?}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BiblesController::books
	 */
	public function test_v4_bible_books()
	{
		$path = route('v4_bible.books', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible.archival
	 * @category Route Path: https://api.dbp.test/bibles/archival?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BiblesController::archival
	 */
	public function test_v4_bible_archival()
	{
		$path = route('v4_bible.archival', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible.one
	 * @category Route Path: https://api.dbp.test/bibles/{bible_id}?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BiblesController::show
	 */
	public function test_v4_bible_one()
	{
		$path = route('v4_bible.one', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}

	/**
	 * @category V4_API
	 * @category Route Name: v4_bible.all
	 * @category Route Path: https://api.dbp.test/bibles?v=4&key=1234
	 * @see      \App\Http\Controllers\Bible\BiblesController::index
	 */
	public function test_v4_bible_all()
	{
		$path = route('v4_bible.all', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
	}



}
