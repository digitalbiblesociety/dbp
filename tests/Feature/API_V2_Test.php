<?php

namespace Tests\Feature;

use App\Http\Controllers\HomeController;
use App\Models\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Bible\Bible;
use App\Models\Bible\Video;

class API_V2_Test extends TestCase
{

	protected $params;
	protected $swagger;
	protected $schemas;

	/**
	 * API_V2_Test constructor
	 *
	 *
	 */
	function setUp() {
		parent::setUp();
		$user = User::inRandomOrder()->first();
		$this->params = ['v' => 2,'key' => $user->id,'pretty'];

		// Fetch the Swagger Docs for Structure Validation
		$arrContextOptions= [ "ssl" => ["verify_peer"=>false, "verify_peer_name"=>false]];
		$swagger_url = env('APP_URL').'/swagger_v2.json';
		$this->swagger = json_decode(file_get_contents($swagger_url, false, stream_context_create($arrContextOptions)), true);
		$this->schemas = $this->swagger['components']['schemas'];
	}

	public function getSchemaKeys($schema)
	{
		return array_keys($this->schemas[$schema]['properties']);
	}

	/**
	 *
	 * Test Library Asset Route
	 *
	 * @category V2_Library
	 * @see HomeController::libraryAsset()
	 * @category Swagger ID: LibraryAsset
	 * @category Route Name: v2_library_asset
	 * @link Route Path: https://api.dbp.dev/library/asset?v=2&pretty
	 *
	 */
	public function test_library_asset() {
		$response = $this->get(route('v2_library_asset'), $this->params);
		$response_v2 = $this->get("https://dbt.io/library/asset", $this->params);

		echo "\nTesting: ".route('v2_library_asset', $this->params);
		$response->assertSuccessful()->assertJsonStructure([$this->getSchemaKeys('LibraryAsset')]);
		$response_v2->assertSuccessful()->assertJsonStructure([$this->getSchemaKeys('LibraryAsset')]);
	}

	/**
	 *
	 * Test Library Version Route
	 *
	 * @category V2_Library
	 * @see HomeController::versionLatest()
	 * @category Swagger ID: LibraryAsset
	 * @category Route Name: v2_library_asset
	 * @link Route Path: https://api.dbp.dev/api/apiversion?v=2&pretty
	 *
	 */
	public function test_library_version() {
		$response = $this->get(route('v2_api_versionLatest'), $this->params);
		$response_v2 = $this->get("https://dbt.io/api/apiversion", $this->params);

		echo "\nTesting: ".route('v2_api_versionLatest', $this->params);
		$response->assertSuccessful()->assertJsonStructure($this->getSchemaKeys('VersionNumber'));
		$response_v2->assertSuccessful()->assertJsonStructure($this->getSchemaKeys('VersionNumber'));
	}

	/**
	 * Test Library Book Order Route
	 *
	 * @category V2_Library
	 * @see BooksController::show()
	 * @category Swagger ID: BookOrder
	 * @category Route Name: v2_library_bookOrder
	 * @link Route Path: https://api.dbp.dev/library/bookorder?v=2&dam_id=ENGKJV
	 *
	 */
	public function test_library_bookOrder() {
		$bible = Bible::has('text')->inRandomOrder()->first();
		echo "\nTesting: " . route('v2_library_bookOrder',  ['v' => 2, 'dam_id' => $bible->id]);
		$response = $this->get(route('v2_library_bookOrder'), ['v' => 2, 'dam_id' => $bible->id]);
		$response->assertSuccessful()->assertJsonStructure([$this->getSchemaKeys('BookOrder')]);
	}

	/**
	 *
	 * Test Library Book
	 *
	 * @category V2_Library
	 * @see BooksController::show()
	 * @category Swagger ID: BookOrder
	 * @category Route Name: v2_library_bookOrder
	 * @link Route Path: https://api.dbp.dev/library/book?v=2&dam_id=ENGKJV&pretty
	 *
	 */
	public function test_library_book() {
		$bible = Bible::has('text')->inRandomOrder()->first();
		echo "\nTesting: " . route('v2_library_book',  ['v' => 2, 'dam_id' => $bible->id]);
		$response = $this->get(route('v2_library_book'), ['v' => 2, 'dam_id' => $bible->id]);
		$response->assertSuccessful()->assertJsonStructure([$this->getSchemaKeys('BookOrder')]);
	}

	/**
	 * Tests the Library Book Name Route
	 *
	 * @category V2_Library
	 * @see BooksController::bookNames()
	 * @category Swagger ID: BookName
	 * @category Route Name: v2_library_bookName
	 * @link Route Path: https://api.dbp.dev/library/bookname?v=2&language_code=eng&pretty
	 *
	 */
	public function test_library_bookName() {
		$response = $this->get(route('v2_library_bookName'), ['v' => 2, 'language_code' => 'eng']);
		echo "\nTesting: ".route('v2_library_bookName', ['v' => 2, 'language_code' => 'eng']);
		$response->assertSuccessful();
	}

	/**
	 * Tests the Library Chapter Route
	 *
	 * @category V2_Library
	 * @see BooksController::chapters()
	 * @category Swagger ID: BookName
	 * @category Route Name: v2_library_bookName
	 * @link Route Path: https://api.dbp.dev/library/chapter?v=2&dam_id=ENGKJV&book_id=GEN&pretty
	 *
	 */
	public function test_library_chapter() {
		$bible = Bible::has('text')->inRandomOrder()->first();
		$book_id = $bible->text->first()->book->id;
		$response = $this->get(route('v2_library_chapter'), ['v' => 2, 'dam_id' => $bible->id, 'book_id' => $book_id]);
		echo "\nTesting: ".route('v2_library_chapter', ['v' => 2, 'dam_id' => $bible->id, 'book_id' => $book_id]);
		$response->assertSuccessful()->assertJsonStructure([$this->getSchemaKeys('ChapterListItem')]);
	}

	/**
	 *
	 * Tests the Library Language Route
	 *
	 * @category V2_Library
	 * @see LanguagesController::index()
	 * @category Swagger ID: LibraryLanguage
	 * @category Route Name: v2_library_language
	 * @link Route Path: https://api.dbp.dev/library/language?v=2&pretty
	 *
	 */
	public function test_library_language() {
		$response = $this->get(route('v2_library_language'), ['v' => 2]);
		echo "\nTesting: ".route('v2_library_language');
		$response->assertSuccessful()->assertJsonStructure([$this->getSchemaKeys('LibraryLanguage')]);
	}

	/**
	 *
	 * Tests the Library Verse Info Route
	 *
	 * @category V2_Library
	 * @see VerseController::info()
	 * @category Swagger ID: LibraryVerseInfo
	 * @category Route Name: v2_library_verseInfo
	 * @link Route Path: https://api.dbp.dev/library/verseinfo?v=2&pretty&bible_id=ENGKJV&book_id=GEN&chapter=1&verse_start=1
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
		$response->assertSuccessful()->assertJsonStructure([$this->getSchemaKeys('LibraryVerseInfo')]);
	}

	/**
	 *
	 * Tests the Library Numbers Route
	 *
	 * @category V2_Library
	 * @see NumbersController::customRange()
	 * @category Swagger ID: Number
	 * @category Route Name: v2_library_numbers
	 * @link Route Path: https://api.dbp.dev/library/numbers?v=2&pretty&iso=arb&start=1&end=50
	 *
	 */
	public function test_library_numbers()              {
		$response = $this->get(route('v2_library_numbers'), ['v' => 2, 'iso' => 'arb', 'start' => 1, 'end' => 100]);
		echo "\nTesting: ".route('v2_library_numbers', ['v' => 2, 'iso' => 'arb', 'start' => 1, 'end' => 100]);
		$response->assertSuccessful()->assertJsonStructure([$this->getSchemaKeys('Number')]);
	}

	/**
	 *
	 * Tests the Library MetaData Route
	 *
	 * @category V2_Library
	 * @see BiblesController
	 * @category Swagger ID: LibraryMetaData
	 * @category Route Name: v2_library_metadata
	 * @link Route Path: https://api.dbp.dev/library/metadata?v=2&id=ENGKJV
	 *
	 */
	public function test_library_metadata()             {
		$bible = Bible::has('text')->inRandomOrder()->first();
		$response = $this->get(route('v2_library_metadata', ['id' => $bible->id]), ['v' => 2]);
		echo "\nTesting: ".route('v2_library_metadata', ['id' => $bible->id]);
		$response->assertSuccessful()->assertJsonStructure([$this->getSchemaKeys('LibraryMetaData')]);
	}

	/**
	 *
	 * Tests the Library Volume Route
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\BiblesController::index()
	 * @category Swagger ID: LibraryVolume
	 * @category Route Name: v2_library_volume
	 * @link Route Path: https://api.dbp.dev/library/volume?v=2&pretty
	 *
	 */
	public function test_library_volume() {
		$response = $this->get(route('v2_library_volume'), $this->params);
		echo "\nTesting: ".route('v2_library_volume');
		$response->assertSuccessful()->assertJsonStructure([$this->getSchemaKeys('LibraryVolume')]);
	}

	/**
	 *
	 * Tests the Volume Language Route
	 *
	 * @category V2_Library
	 * @see LanguagesController::volumeLanguage()
	 * @category Swagger ID: LibraryVolume
	 * @category Route Name: v2_library_volumeLanguage
	 * @link Route Path: https://api.dbp.dev/library/volumelanguage?v=2&pretty
	 *
	 */
	public function test_library_volumeLanguage() {
		$response = $this->get(route('v2_library_volumeLanguage'), ['v' => 2]);
		echo "\nTesting: ".route('v2_library_volumeLanguage');
		$response->assertSuccessful()->assertJsonStructure([$this->getSchemaKeys('LibraryLanguage')]);
	}

	/**
	 *
	 * Tests the Volume Language Family
	 *
	 * @category V2_Library
	 * @see LanguagesController::volumeLanguage()
	 * @category Swagger ID: LibraryVolumeLanguageFamily
	 * @category Route Name: v2_library_volumeLanguageFamily
	 * @link Route Path: https://api.dbp.dev/library/volumelanguagefamily?v=2&pretty
	 *
	 */
	public function test_library_volumeLanguageFamily() {
		$response = $this->get(route('v2_library_volumeLanguageFamily'), ['v' => 2]);
		echo "\nTesting: ".route('v2_library_volumeLanguageFamily', ['v' => 2]);
		//dd(json_decode($response->getContent())->data[0]);
		$response->assertSuccessful()->assertJsonStructure([$this->getSchemaKeys('LibraryVolumeLanguageFamily')]);
	}

	/**
	 *
	 * Tests the Volume Organization List
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\OrganizationsController::index()
	 * @category Swagger ID: VolumeOrganization
	 * @category Route Name: v2_volume_organization_list
	 * @link Route Path: https://api.dbp.dev/library/volumeorganization?v=2&pretty
	 *
	 */
	public function test_volume_organization_list() {
		$response = $this->get(route('v2_volume_organization_list'), ['v' => 2]);
		echo "\nTesting: ".route('v2_volume_organization_list', ['v' => 2]);
		$response->assertSuccessful();
	}

	/**
	 *
	 * Tests the Volume History
	 *
	 * @category V2_Library
	 * @see OrganizationsController::index()
	 * @category Swagger ID: LibraryVolumeLanguageFamily
	 * @category Route Name: v2_volume_history
	 * @link Route Path: https://api.dbp.dev/library/organization?v=2&pretty
	 *
	 */
	public function test_volume_history()               {
		$response = $this->get(route('v2_volume_history'), ['v' => 2, 'limit' => 20]);
		echo "\nTesting: ".route('v2_volume_history');
		$response->assertSuccessful();
	}

	/**
	 *
	 * Tests the Audio Path
	 *
	 * @category V2_Audio
	 * @see \app\Http\Controllers\AudioController::index()
	 * @category Swagger ID:
	 * @category Route Name: v2_audio_path
	 * @link Route Path: https://api.dbp.dev/audio/path?v=2&fileset_id=ENGKJV&pretty
	 *
	 */
	public function test_audio_path()                   {
		$bible = Bible::has('text')->inRandomOrder()->first();
		$response = $this->get(route('v2_audio_path'), ['v' => 2, 'fileset_id' => $bible->id]);
		echo "\nTesting: ".route('v2_audio_path');

		$response->assertSuccessful();
	}

	/**
	 *
	 * Tests the Audio Timestamps
	 *
	 * @category V2_Audio
	 * @see \app\Http\Controllers\AudioController::timestampsByReference()
	 * @category Swagger ID:
	 * @category Route Name: v2_audio_timestamps
	 * @link Route Path: https://api.dbp.dev/audio/versestart?v=2&dam_id=ENGKJV&chapter=1&book=GEN&pretty
	 *
	 */
	public function test_audio_timestamps() {
		$response = $this->get(route('v2_audio_timestamps'), ['v' => 2, 'fileset_id' => "ENGESVO2DA", 'chapter' => 1, 'book' => "GEN"]);
		echo "\nTesting: ".route('v2_audio_timestamps');
		$response->assertSuccessful()->assertJsonStructure([$this->getSchemaKeys('AudioTimestamp')]);
	}

	/**
	 *
	 * Tests the Text Font
	 *
	 * @category V2_Text
	 * @see \app\Http\Controllers\TextController::fonts()
	 * @category Swagger ID: TextFont
	 * @category Route Name: v2_text_font
	 * @link Route Path: https://api.dbp.dev/text/font?v=2&platform=web&pretty
	 *
	 */
	public function test_text_font() {
		$response = $this->get(route('v2_text_font'), ['v' => 2, 'platform' => 'web']);
		echo "\nTesting: ".route('v2_text_font');

		$response->assertSuccessful();
	}

	/**
	 *
	 * Tests the Text Verse
	 *
	 * @category V2_Text
	 * @see \app\Http\Controllers\TextController::index()
	 * @category Swagger ID: TextVerse
	 * @category Route Name: v2_text_verse
	 * @link Route Path: https://api.dbp.dev/text/verse?v=2&dam_id=ENGESV&book_id=GEN&chapter_id=1&verse_start=1&verse_end=10&pretty
	 *
	 */
	public function test_text_verse() {
		$response = $this->get(route('v2_text_verse'), ['v' => 2, 'dam_id' => "ENGESV", 'book_id' => "GEN", 'chapter_id' => 1, 'verse_start' => 1, 'verse_end' => 10]);
		echo "\nTesting: ".route('v2_text_verse');

		$response->assertSuccessful();
	}

	/**
	 *
	 * Tests the Text Search
	 *
	 * @category V2_Text
	 * @see \app\Http\Controllers\TextController::search()
	 * @category Swagger ID: TextSearch
	 * @category Route Name: v2_text_search
	 * @link Route Path: https://api.dbp.dev/text/search?v=2&query=God&dam_id=ENGESV&limit=5&pretty
	 *
	 */
	public function test_text_search() {
		$response = $this->get(route('v2_text_search'), ['v' => 2, 'query' => 'God', 'dam_id' => 'ENGESV', 'limit' => 5, 'pretty']);
		echo "\nTesting: ".route('v2_text_search', ['v' => 2, 'query' => 'God', 'dam_id' => 'ENGESV', 'limit' => 5, 'pretty']);

		$response->assertSuccessful();
	}

	/**
	 *
	 * Tests the Text Search Group
	 *
	 * @category V2_Text
	 * @see \app\Http\Controllers\TextController::searchGroup()
	 * @category Swagger ID: TextSearchGroup
	 * @category Route Name: v2_text_search_group
	 * @link Route Path: https://api.dbp.dev/text/searchgroup?v=2&query=God&dam_id=ENGESV&limit=5&pretty
	 *
	 */
	public function test_text_search_group()            {
		$response = $this->get(route('v2_text_search_group'), ['v' => 2, 'query' => 'God', 'dam_id' => 'ENGESV']);
		echo "\nTesting: ".route('v2_text_search_group', ['v' => 2, 'query' => 'God', 'dam_id' => 'ENGESV']);
		$response->assertSuccessful();
	}

	/**
	 *
	 * Tests the Video Location
	 *
	 * @category V2_Video
	 * @see \app\Http\Controllers\FilmsController::location()
	 * @category Swagger ID: VideoLocation
	 * @category Route Name: v2_video_location
	 * @link Route Path: https://api.dbp.dev/video/location?v=2&dam_id=ENGKJV
	 *

	public function test_video_location() {
		$bible = Video::inRandomOrder()->first();
		$response = $this->get(route('v2_video_location'), ['v' => 2,'dam_id' => $bible->id]);
		echo "\nTesting: ".route('v2_video_location');
		$response->assertSuccessful();
	}

	/**
	 *
	 * Tests the Video Path
	 *
	 * @category V2_Video
	 * @see \app\Http\Controllers\FilmsController::videoPath()
	 * @category Swagger ID: VideoPath
	 * @category Route Name: v2_video_video_path
	 * @link Route Path: https://api.dbp.dev/video/path?v=2&dam_id=ENGESV&pretty
	 *

	public function test_video_video_path() {
		$bible = Video::inRandomOrder()->first();
		$response = $this->get(route('v2_video_video_path'), ['v' => 2, 'dam_id' => $bible->id]);
		echo "\nTesting: ".route('v2_video_video_path');
		$response->assertSuccessful();
	}

	/**
	 *
	 * Tests the Video Path
	 *
	 * @category V2_Meta
	 * @see \app\Http\Controllers\LanguagesController::CountryLang()
	 * @category Swagger ID: CountryLang
	 * @category Route Name: v2_country_lang
	 * @link Route Path: https://api.dbp.dev/country/countrylang?v=2&pretty
	 *

	public function test_country_lang() {
		$response = $this->get(route('v2_country_lang'), ['v' => 2, 'country_additional' => true, 'sort_by' => 'name']);
		echo "\nTesting: ".route('v2_country_lang', ['v' => 2, 'country_additional' => true, 'sort_by' => 'name']);

		$response->assertSuccessful()->assertJsonStructure([$this->getSchemaKeys('CountryLang')]);
	}
	 * */

}
