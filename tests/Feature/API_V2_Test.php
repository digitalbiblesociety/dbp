<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Bible\Bible;
use App\Models\Bible\Text;
use App\Models\Bible\Video;
class API_V2_Test extends TestCase
{
	//use DatabaseTransactions;
	//use WithoutMiddleware;
	/**
     * A basic test example.
     *
     * @return void
     */

	public function __construct() {
		$this->params = ['v' => 2];

		// Fetch the Swagger Docs for Structure Validation
		$arrContextOptions= [ "ssl"=> ["verify_peer"=>false, "verify_peer_name"=>false]];
		$swagger_url = env('APP_URL').'/swagger.json';
		$this->swagger = json_decode(file_get_contents($swagger_url, false, stream_context_create($arrContextOptions)), true);
		$this->schemas = $this->swagger['components']['schemas'];
	}

	private function getSchemaKeys($schema)
	{
		return array_keys($this->schemas[$schema]['properties']);
	}

	/**
	 *
	 * Test Library Asset Route
	 *
	 * @category V2_Library
	 * @category Controller: HomeController@libraryAsset
	 * @category Swagger ID: LibraryAsset
	 * @category Route Name: v2_library_asset
	 *
	 */
	public function test_library_asset() {
		$response = $this->get(route('v2_library_asset'), $this->params);
		echo "\nTesting: ".route('v2_library_asset');
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('LibraryAsset')]);
	}

	/**
	 *
	 * Test Library Version Route
	 *
	 * @category V2_Library
	 * @category Controller: HomeController@versionLatest
	 * @category Swagger ID: LibraryAsset
	 * @category Route Name: v2_library_asset
	 *
	 */
	public function test_library_version() {
		$response = $this->get(route('v2_api_versionLatest'), $this->params);
		echo "\nTesting: ".route('v2_api_versionLatest');
		$response->assertSuccessful();
		$response->assertJsonStructure($this->getSchemaKeys('VersionNumber'));
	}

	/**
	 * Test Library Book Order Route
	 *
	 * @category V2_Library
	 * @category Controller: BooksController@show
	 * @category Swagger ID: BookOrder
	 * @category Route Name: v2_library_bookOrder
	 *
	 */
	public function test_library_bookOrder() {
		$bible = Bible::has('text')->inRandomOrder()->first();
		echo "\nTesting: " . route('v2_library_bookOrder',  ['v' => 2, 'dam_id' => $bible->id]);
		$response = $this->get(route('v2_library_bookOrder'), ['v' => 2, 'dam_id' => $bible->id]);
		$response->assertSuccessful();
		$response->assertJsonStructure([ "data" => [$this->getSchemaKeys('BookOrder')]]);
	}

	/**
	 *
	 * Test Library Book
	 *
	 * @category V2_Library
	 * @category Controller: BooksController@show
	 * @category Swagger ID: BookOrder
	 * @category Route Name: v2_library_bookOrder
	 *
	 */
	public function test_library_book() {
		$bible = Bible::has('text')->inRandomOrder()->first();
		echo "\nTesting: " . route('v2_library_book',  ['v' => 2, 'dam_id' => $bible->id]);
		$response = $this->get(route('v2_library_book'), ['v' => 2, 'dam_id' => $bible->id]);
		$response->assertSuccessful();
		//$response->assertJsonStructure([ "data" => [$this->getSchemaKeys('BookOrder')]]);
	}

	/**
	 * Tests the Library Book Name Route
	 *
	 * @category V2_Library
	 * @category Controller: BooksController@bookNames
	 * @category Swagger ID: BookName
	 * @category Route Name: v2_library_bookName
	 *
	 */
	public function test_library_bookName() {
		$response = $this->get(route('v2_library_bookName'), ['v' => 2, 'language_code' => 'eng']);
		echo "\nTesting: ".route('v2_library_bookName', ['v' => 2, 'language_code' => 'eng']);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('BookName')]);
	}

	/**
	 *
	 */
	public function test_library_chapter() {
		$bible = Bible::has('text')->inRandomOrder()->first();
		$book_id = $bible->text->first()->book->id;
		$response = $this->get(route('v2_library_chapter'), ['v' => 2, 'dam_id' => $bible->id, 'book_id' => $book_id]);
		echo "\nTesting: ".route('v2_library_chapter', ['v' => 2, 'dam_id' => $bible->id, 'book_id' => $book_id]);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('ChapterListItem')]);
	}

	/**
	 *
	 */
	public function test_library_language() {
		$response = $this->get(route('v2_library_language'), ['v' => 2]);
		echo "\nTesting: ".route('v2_library_language');
		$response->assertSuccessful();
		$response->assertJsonStructure([ "data" => [$this->getSchemaKeys('LibraryLanguage')]]);
	}

	/**
	 *
	 */
	public function test_library_verseInfo() {
		$bible = Bible::has('text')->inRandomOrder()->first();
		$text = $bible->text->first();
		$book_id = $text->book->id;
		$chapter = $text->chapter_number;
		$verse_start = $text->verse_start;
		$verse_end = $text->verse_start + 5;
		$response = $this->get(route('v2_library_verseInfo'), ['v' => 2,'bible_id' => $bible->id, 'book_id' => $book_id, 'chapter' => $chapter, 'verse_start' => $verse_start, 'verse_end' => $verse_end]);
		echo "\nTesting: ".route('v2_library_verseInfo', ['v' => 2,'bible_id' => $bible->id, 'book_id' => $book_id, 'chapter' => $chapter, 'verse_start' => $verse_start, 'verse_end' => $verse_end]);
		$response->assertSuccessful();
		// TODO: $response->assertJsonStructure($this->getSchemaKeys('LibraryLanguage'));
	}

	/**
	 *
	 */
	public function test_library_numbers()              {
		$response = $this->get(route('v2_library_numbers'), ['v' => 2, 'iso' => 'arb', 'start' => 1, 'end' => 100]);
		echo "\nTesting: ".route('v2_library_numbers', ['v' => 2, 'iso' => 'arb', 'start' => 1, 'end' => 100]);
		$response->assertSuccessful();
	}

	/**
	 *
	 */
	public function test_library_metadata()             {
		$bible = Bible::has('text')->inRandomOrder()->first();
		$response = $this->get(route('v2_library_metadata', ['id' => $bible->id]), ['v' => 2]);
		echo "\nTesting: ".route('v2_library_metadata', ['id' => $bible->id]);
		$response->assertSuccessful();
	}

	/**
	 *
	 */
	public function test_library_volume()               {
	$response = $this->get(route('v2_library_volume'), ['v' => 2]);
	echo "\nTesting: ".route('v2_library_volume');
	$response->assertSuccessful();
}

	/**
	 *
	 */
	public function test_library_volumeLanguage()       {
	$response = $this->get(route('v2_library_volumeLanguage'), ['v' => 2]);
	echo "\nTesting: ".route('v2_library_volumeLanguage');
	$response->assertSuccessful();

}

	/**
	 *
	 */
	public function test_library_volumeLanguageFamily() {
	$response = $this->get(route('v2_library_volumeLanguageFamily'), ['v' => 2]);
	echo "\nTesting: ".route('v2_library_volumeLanguageFamily');
	$response->assertSuccessful();

}

	/**
	 *
	 */
	public function test_volume_organization_list()     {
	$response = $this->get(route('v2_volume_organization_list'), ['v' => 2]);
	echo "\nTesting: ".route('v2_volume_organization_list');
	$response->assertSuccessful();

}

	/**
	 *
	 */
	public function test_volume_history()               {
	$response = $this->get(route('v2_volume_history'), ['v' => 2, 'limit' => 20]);
	echo "\nTesting: ".route('v2_volume_history');
	$response->assertSuccessful();

}

	/**
	 *
	 */
	public function test_audio_path()                   {
	$bible = Bible::has('text')->inRandomOrder()->first();
	$response = $this->get(route('v2_audio_path'), ['v' => 2, 'dam_id' => $bible->id]);
	echo "\nTesting: ".route('v2_audio_path');
	$response->assertSuccessful();

}

	/**
	 *
	 */
	public function test_audio_timestamps()             {
	$response = $this->get(route('v2_audio_timestamps'), ['v' => 2, 'dam_id' => "ENGESV", 'chapter' => 1, 'book' => "GEN"]);
	echo "\nTesting: ".route('v2_audio_timestamps');

	$response->assertSuccessful();

}

	/**
	 *
	 */
	public function test_text_font()                    {
	$response = $this->get(route('v2_text_font'), ['v' => 2, 'platform' => 'web']);
	echo "\nTesting: ".route('v2_text_font');
	$response->assertSuccessful();

}

	/**
	 *
	 */
	public function test_text_verse()                   {
	$response = $this->get(route('v2_text_verse'), ['v' => 2, 'dam_id' => "ENGESV", 'book_id' => "GEN", 'chapter_id' => 1, 'verse_start' => 1, 'verse_end' => 10]);
	echo "\nTesting: ".route('v2_text_verse');
	$response->assertSuccessful();

}

	/**
	 *
	 */
	public function test_text_search()                  {
	$response = $this->get(route('v2_text_search'), ['v' => 2, 'query' => 'God', 'dam_id' => 'ENGESV', 'limit' => 5]);
	echo "\nTesting: ".route('v2_text_search');
	$response->assertSuccessful();

}

	/**
	 *
	 */
	public function test_text_search_group()            {
	$response = $this->get(route('v2_text_search_group'), ['v' => 2, 'query' => 'God', 'dam_id' => 'ENGESV']);
	echo "\nTesting: ".route('v2_text_search_group');
	$response->assertSuccessful();

}

	/**
	 *
	 */
	public function test_video_location()               {
	$bible = Video::inRandomOrder()->first();
	$response = $this->get(route('v2_video_location'), ['v' => 2,'dam_id' => $bible->id]);
	echo "\nTesting: ".route('v2_video_location');
	$response->assertSuccessful();

}

	/**
	 *
	 */
	public function test_video_video_path()             {
	$bible = Video::inRandomOrder()->first();
	$response = $this->get(route('v2_video_video_path'), ['v' => 2, 'dam_id' => $bible->id]);
	echo "\nTesting: ".route('v2_video_video_path');
	$response->assertSuccessful();

}

	/**
	 *
	 */
	public function test_country_lang()                 {
	$response = $this->get(route('v2_country_lang'), ['v' => 2]);
	echo "\nTesting: ".route('v2_country_lang');
	$response->assertSuccessful();
}

}
