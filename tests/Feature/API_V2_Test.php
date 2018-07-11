<?php

namespace Tests\Feature;

use App\Models\Bible\BibleFileset;
use App\Models\User\AccessGroup;
use App\Models\User\AccessGroupKey;
use App\Models\User\User;
use Tests\TestCase;

use App\Models\Bible\Book;

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
		$this->params = ['v' => 2,'key' => '53355c32fca5f3cac4d7a670d2df2e09','pretty'];

		// Fetch the Swagger Docs for Structure Validation
		$arrContextOptions= [ "ssl" => ["verify_peer"=>false, "verify_peer_name"=>false]];
		$swagger_url = env('APP_URL').'/swagger_docs?v=v2'; //https://dbp.localhost/
		$this->swagger = json_decode(file_get_contents($swagger_url, false, stream_context_create($arrContextOptions)), true);
		$this->schemas = $this->swagger['components']['schemas'];
	}

	public function getSchemaKeys($schema)
	{
		if(isset($this->schemas[$schema]['items'])) return array_keys($this->schemas[$schema]['items']['properties']);
		return array_keys($this->schemas[$schema]['properties']);
	}

	public function compareToOriginal($path,$schemaStructure)
	{
		$params = implode('&', array_map(function ($v, $k) { return sprintf("%s=%s", $k, $v); }, $this->params, array_keys($this->params)));

		$v2_route = route('v2_pass_through', ltrim($path,'/'))."?".$params;
		$this->log("\nTesting: $v2_route",'light_cyan',true);
		$v2_response = $this->withHeaders(['params' => $params,'v' => 2,'key' => '53355c32fca5f3cac4d7a670d2df2e09'])->get($v2_route);
		$v2_response->assertSuccessful();
		$v2_response->assertJsonStructure($schemaStructure);
	}

	public function compare()
	{

	}

	/**
	 *
	 * Test Library Asset Route
	 *
	 * @category V2_Library
	 * @see HomeController::libraryAsset()
	 * @category Swagger ID: LibraryAsset
	 * @category Route Name: v2_library_asset
	 * @link Route Path: https://api.dbp.localhost/library/asset?v=2&pretty
	 *
	 */
	public function test_library_asset() {
		$path = route('v2_library_asset',[],false);

		$this->log("\nTesting: " . route('v2_library_asset', $this->params),'light_cyan',true);
		$response = $this->get(route('v2_library_asset'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('LibraryAsset')]);
		$this->compareToOriginal($path,[$this->getSchemaKeys('LibraryAsset')]);
	}

	/**
	 *
	 * Test Library Version Route
	 *
	 * @category V2_Library
	 * @see HomeController::versionLatest()
	 * @category Swagger ID: LibraryAsset
	 * @category Route Name: v2_library_asset
	 * @link Route Path: https://api.dbp.localhost/api/apiversion?v=2&pretty
	 *
	 */
	public function test_library_version() {
		$path = route('v2_api_versionLatest',[],false);
		$params = implode('&', array_map(function ($v, $k) { return sprintf("%s=%s", $k, $v); }, $this->params, array_keys($this->params)));

		$this->log("\nTesting: " . route('v2_api_versionLatest', $this->params),'light_cyan',true);
		$response = $this->get(route('v2_api_versionLatest'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure($this->getSchemaKeys('VersionNumber'));
		$this->compareToOriginal($path,$this->getSchemaKeys('VersionNumber'));
	}

	/**
	 * Test Library Book Order Route
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\V2Controllers\LibraryCatalog\BooksController::show()
	 * @category Swagger ID: v2_library_bookOrder
	 * @category Route Name: v2_library_bookOrder
	 * @link Route Path: https://api.dbp.localhost/library/bookorder?v=2&dam_id=ENGESV&pretty&key=1234
	 *
	 */
	public function test_library_bookOrder() {
		$bible = "AAIWBTN2ET";
		$path = route('v2_library_bookOrder',[],false);
		$this->params['dam_id'] = $bible;

		$this->log("\n\n v2_library_bookOrder: ",'light_green',true);
		echo "\nTesting: " . route('v2_library_bookOrder', $this->params);
		$response = $this->get(route('v2_library_bookOrder'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('v2_library_bookOrder')]);
		$this->compareToOriginal($path,[$this->getSchemaKeys('v2_library_bookOrder')]);
	}

	/**
	 *
	 * Test Library Book
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\V2Controllers\LibraryCatalog\BooksController::book()
	 * @category Swagger ID: v2_library_book
	 * @category Route Name: v2_library_book
	 * @link Test Route Path: https://api.dbp.localhost/library/book?v=2&dam_id=AAIWBTN2ET&key=1234&pretty
	 * @link V2 Route Path: https://dbt.io/library/book?v=2&dam_id=AAIWBTN2ET&key=3e0eed1a69fc6e012fef51b8a28cc6ff&pretty
	 *
	 */
	public function test_library_book() {
		$bible_id = fetchRandomBibleID();
		$path = route('v2_library_book', [], false);
		$this->params['dam_id'] = $bible_id;

		echo "\nTesting: " . route('v2_library_book', $this->params);
		$response = $this->get(route('v2_library_book'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('v2_library_book')]);
		$this->compareToOriginal($path,[$this->getSchemaKeys('v2_library_book')]);
	}

	/**
	 * Tests the Library Book Name Route
	 *
	 * @category V2_Library
	 * @see BooksController::bookNames()
	 * @category Swagger ID: BookName
	 * @category Route Name: v2_library_bookName
	 * @link Route Path: https://api.dbp.localhost/library/bookname?v=2&language_code=eng&pretty
	 *
	 */
	public function test_library_bookName() {
		$path = route('v2_library_bookName',[],false);
		$this->params['language_code'] = 'eng';

		echo "\nTesting: " . route('v2_library_bookName', $this->params);
		$response = $this->get(route('v2_library_bookName'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('BookName')]);

		$this->params['language_code'] = strtoupper($this->params['language_code']);
		$this->compareToOriginal($path,[$this->getSchemaKeys('BookName')]);
	}

	/**
	 * Tests the Library Chapter Route
	 *
	 * @category V2_Library
	 * @see BooksController::chapters()
	 * @category Swagger ID: BookName
	 * @category Route Name: v2_library_bookName
	 * @link Route Path: https://api.dbp.localhost/library/chapter?v=2&dam_id=ENGESV&book_id=GEN&pretty
	 *
	 */
	public function test_library_chapter() {
		$path = route('v2_library_bookName', [], false);
		$this->params['language_code'] = 'AAIWBTN2ET';
		$this->params['language_code'] = 'MAT';

		echo "\nTesting: " . route('v2_library_chapter', $this->params);
		$response = $this->get(route('v2_library_chapter'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('ChapterListItem')]);
		$this->compareToOriginal($path,[$this->getSchemaKeys('ChapterListItem')]);
	}

	/**
	 *
	 * Tests the Library Language Route
	 *
	 * @category V2_Library
	 * @see LanguagesController::index()
	 * @category Swagger ID: LibraryLanguage
	 * @category Route Name: v2_library_language
	 * @link Route Path: https://api.dbp.localhost/library/language?v=2&pretty&key=1234
	 *
	 */
	public function test_library_language() {
		$path = route('v2_library_language', [], false);

		echo "\nTesting: " . route('v2_library_language');
		$response = $this->get(route('v2_library_language'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('LibraryLanguage')]);
		$this->compareToOriginal($path,[$this->getSchemaKeys('LibraryLanguage')]);
	}

	/**
	 *
	 * Tests the Library Verse Info Route
	 *
	 * @category V2_Library
	 * @see VerseController::info()
	 * @category Swagger ID: LibraryVerseInfo
	 * @category Route Name: v2_library_verseInfo
	 * @link Route Path: https://api.dbp.localhost/library/verseinfo?v=2&pretty&bible_id=ENGESV&book_id=GEN&chapter=1&verse_start=1
	 *
	 */
	public function test_library_verseInfo() {
		$path = route('v2_library_verseInfo', [], false);
		$verse = \DB::connection('sophia')->table('AAIWBT_vpl')->inRandomOrder()->first();
		$book = Book::where('id_usfx',$verse->book)->first();
		$chapter = $verse->chapter;
		$verse_start = $verse->verse_start;
		$verse_end = $verse->verse_start + 5;

		$this->params['bible_id'] = "AAIWBT";
		$this->params['book_id'] = $book->id;
		$this->params['chapter'] = $chapter;
		$this->params['verse_start'] = $verse_start;
		$this->params['verse_end'] = $verse_end;

		echo "\nTesting: " . route('v2_library_verseInfo', $this->params);
		$response = $this->get(route('v2_library_verseInfo'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('LibraryVerseInfo')]);
		$this->compareToOriginal($path,[$this->getSchemaKeys('LibraryVerseInfo')]);
	}

	/**
	 *
	 * Tests the Library Numbers Route
	 *
	 * @category V2_Library
	 * @see NumbersController::customRange()
	 * @category Swagger ID: Number
	 * @category Route Name: v2_library_numbers
	 * @link Route Path: https://api.dbp.localhost/library/numbers?v=2&pretty&iso=arb&start=1&end=50
	 *
	 */
	public function test_library_numbers()              {
		$path = route('v2_library_verseInfo', [], false);

		$this->params['iso'] = 'arb';
		$this->params['script'] = 'Arab';
		$this->params['start'] = 1;
		$this->params['end'] = 100;

		echo "\nTesting: " . route('v2_library_numbers', $this->params);
		$response = $this->get(route('v2_library_numbers'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('Number')]);
		$this->compareToOriginal($path,[$this->getSchemaKeys('Number')]);
	}

	/**
	 *
	 * Tests the Library MetaData Route
	 *
	 * @category V2_Library
	 * @see BiblesController::libraryMetadata()
	 * @category Swagger ID: LibraryMetaData
	 * @category Route Name: v2_library_metadata
	 * @link Route Path: https://api.dbp.localhost/library/metadata?v=2&id=AAIWBT&key=3e0eed1a69fc6e012fef51b8a28cc6ff
	 *
	 */
	public function test_library_metadata()
	{
		$bible_id = fetchRandomFilesetID();
		$path = route('v2_library_metadata', [], false);

		$this->params['dam_id'] = $bible_id;

		echo "\nTesting: " . route('v2_library_metadata', $this->params);
		$response = $this->get(route('v2_library_metadata'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure($this->getSchemaKeys('LibraryMetaData'));
		$this->compareToOriginal($path,$this->getSchemaKeys('LibraryMetaData'));
	}

	/**
	 *
	 * Tests the Library Volume Route
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\BiblesController::index()
	 * @category Swagger ID: LibraryVolume
	 * @category Route Name: v2_library_volume
	 * @link Route Path: https://api.dbp.localhost/library/volume?v=2&pretty
	 *
	 */
	public function test_library_volume()
	{
		$path = route('v2_library_volume', [], false);

		echo "\nTesting: " . route('v2_library_volume', $this->params);
		$response = $this->get(route('v2_library_volume'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('LibraryVolume')]);
		$this->compareToOriginal($path,$this->getSchemaKeys('LibraryVolume'));
	}

	/**
	 *
	 * Tests the Volume Language Route
	 *
	 * @category V2_Library
	 * @see LanguagesController::volumeLanguage()
	 * @category Swagger ID: LibraryVolume
	 * @category Route Name: v2_library_volumeLanguage
	 * @link Route Path: https://api.dbp.localhost/library/volumelanguage?v=2&pretty
	 *
	 */
	public function test_library_volumeLanguage() {
		$path = route('v2_library_volumeLanguage', [], false);

		echo "\nTesting: " . route('v2_library_volumeLanguage', $this->params);
		$response = $this->get(route('v2_library_volumeLanguage'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('LibraryLanguage')]);
		$this->compareToOriginal($path,$this->getSchemaKeys('LibraryLanguage'));
	}

	/**
	 *
	 * Tests the Volume Language Family
	 *
	 * @category V2_Library
	 * @see LanguagesController::volumeLanguage()
	 * @category Swagger ID: LibraryVolumeLanguageFamily
	 * @category Route Name: v2_library_volumeLanguageFamily
	 * @link Route Path: https://api.dbp.localhost/library/volumelanguagefamily?v=2&pretty
	 *
	 */
	public function test_library_volumeLanguageFamily() {
		$path = route('v2_library_volumeLanguageFamily', [], false);

		echo "\nTesting: " . route('v2_library_volumeLanguageFamily', $this->params);
		$response = $this->get(route('v2_library_volumeLanguageFamily'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('LibraryVolumeLanguageFamily')]);
		$this->compareToOriginal($path,$this->getSchemaKeys('LibraryVolumeLanguageFamily'));
	}

	/**
	 *
	 * Tests the Volume Organization List
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\OrganizationsController::index()
	 * @category Swagger ID: VolumeOrganization
	 * @category Route Name: v2_volume_organization_list
	 * @link Route Path: https://api.dbp.localhost/library/volumeorganization?v=2&pretty
	 *
	 */
	public function test_volume_organization_list() {
		$path = route('v2_volume_organization_list', [], false);

		echo "\nTesting: " . route('v2_volume_organization_list', $this->params);
		$response = $this->get(route('v2_volume_organization_list'), $this->params);
		$response->assertSuccessful();
		//$response->assertJsonStructure([$this->getSchemaKeys('LibraryVolumeLanguageFamily')]);
		//$this->compareToOriginal($path,$this->getSchemaKeys('LibraryVolumeLanguageFamily'));
	}

	/**
	 *
	 * Tests the Volume History
	 *
	 * @category V2_Library
	 * @see BiblesController::history
	 * @category Swagger ID: LibraryVolumeLanguageFamily
	 * @category Route Name: v2_volume_history
	 * @link Route Path: https://api.dbp.localhost/library/volumehistory?v=2&pretty
	 *
	 */
	public function test_volume_history()
	{
		$path = route('v2_volume_history', [], false);
		$this->params['limit'] = 5;

		echo "\nTesting: " . route('v2_volume_history', $this->params);
		$response = $this->get(route('v2_volume_history'), $this->params);
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
	 * @link Route Path: https://api.dbp.localhost/audio/path?v=2&fileset_id=AFRNVVN2DA&pretty
	 *
	 */
	public function test_audio_path()
	{
		$this->params['dam_id'] = 'AFRNVVN2DA';

		echo "\nTesting: " . route('v2_audio_path', $this->params);
		$response = $this->get(route('v2_audio_path'), $this->params);
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
	 * @link Route Path: https://api.dbp.localhost/audio/versestart?v=2&fileset_id=CHNUNVN2DA&chapter=1&book=MAT&pretty
	 *
	 */
	public function test_audio_timestamps() {

		$this->params['fileset_id'] = 'CHNUNVN2DA';
		$this->params['chapter'] = '1';
		$this->params['book'] = 'MAT';

		echo "\nTesting: " . route('v2_audio_timestamps', $this->params);
		$response = $this->get(route('v2_audio_timestamps'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('AudioTimestamp')]);
	}

	/**
	 *
	 * Tests the Text Font
	 *
	 * @category V2_Text
	 * @see \app\Http\Controllers\TextController::fonts()
	 * @category Swagger ID: TextFont
	 * @category Route Name: v2_text_font
	 * @link Route Path: https://api.dbp.localhost/text/font?v=2&platform=web&key=3e0eed1a69fc6e012fef51b8a28cc6ff&pretty
	 *
	 */
	public function test_text_font() {
		$this->params['platform'] = 'web';

		echo "\nTesting: " . route('v2_text_font', $this->params);
		$response = $this->get(route('v2_text_font'), $this->params);
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
	 * @link Route Path: https://api.dbp.localhost/text/verse?v=2&key=3e0eed1a69fc6e012fef51b8a28cc6ff&dam_id=ENGESV&book_id=GEN&chapter_id=1&verse_start=1&verse_end=10
	 *
	 */
	public function test_text_verse_allowed() {
		$public_domain_access_group = \App\Models\User\AccessGroup::with('filesets')->where('name','PUBLIC_DOMAIN')->first();
		$fileset_hashes = $public_domain_access_group->filesets->pluck('hash_id');
		$fileset = \App\Models\Bible\BibleFileset::with('files')->whereIn('hash_id',$fileset_hashes)->where('set_type_code','text_plain')->inRandomOrder()->first();

		$file = \DB::connection('sophia')->table(strtoupper($fileset->id).'_vpl')->inRandomOrder()->take(1)->first();

		$this->params['dam_id']      = $fileset->id;
		$this->params['book_id']     = $file->book_id;
		$this->params['chapter_id']  = $file->chapter_start;
		$this->params['verse_start'] = 1;
		$this->params['verse_end']   = 10;

		echo "\nTesting: " . route('v2_text_verse', $this->params);
		$response = $this->get(route('v2_text_verse', $this->params));
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
	 * @link Route Path: https://api.dbp.localhost/text/search?v=2&query=God&dam_id=ENGESV&limit=5&pretty
	 *
	 */
	public function test_text_search() {
		$public_domain_access_group = \App\Models\User\AccessGroup::with('filesets')->where('name','PUBLIC_DOMAIN')->first();
		$fileset_hashes = $public_domain_access_group->filesets->pluck('hash_id');
		$fileset = \App\Models\Bible\BibleFileset::with('files')->whereIn('hash_id',$fileset_hashes)->where('set_type_code','text_plain')->inRandomOrder()->first();

		$sophia = \DB::connection('sophia')->table(strtoupper($fileset->id).'_vpl')->inRandomOrder()->take(1)->first();
		$text = collect(explode(' ',$sophia->verse_text))->random(1)->first();

		$this->params['dam_id'] = $fileset->id;
		$this->params['query']  = $text;
		$this->params['limit']  = 5;

		echo "\nTesting: " . route('v2_text_search', $this->params);
		$response = $this->get(route('v2_text_search'), $this->params);
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
	 * @link Route Path: https://api.dbp.localhost/text/searchgroup?v=2&query=God&dam_id=ENGESV&limit=5&pretty
	 *
	 */
	public function test_text_search_group()            {
		$public_domain_access_group = \App\Models\User\AccessGroup::with('filesets')->where('name','PUBLIC_DOMAIN')->first();
		$fileset_hashes = $public_domain_access_group->filesets->pluck('hash_id');
		$fileset = \App\Models\Bible\BibleFileset::with('files')->whereIn('hash_id',$fileset_hashes)->where('set_type_code','text_plain')->inRandomOrder()->first();

		$sophia = \DB::connection('sophia')->table(strtoupper($fileset->id).'_vpl')->inRandomOrder()->take(1)->first();
		$text = collect(explode(' ',$sophia->verse_text))->random(1)->first();

		$this->params['dam_id'] = $fileset->id;
		$this->params['query']  = $text;
		$this->params['limit']  = 5;

		echo "\nTesting: " . route('v2_text_search_group', $this->params);
		$response = $this->get(route('v2_text_search_group'), $this->params);
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
	 * @link Route Path: https://api.dbp.localhost/video/location?v=2&dam_id=ENGESV
	 *

	public function test_video_location() {
		$bible = Video::inRandomOrder()->first();
		$response = $this->get(route('v2_video_location'), ['v' => 2,'dam_id' => $bible->id]);
		echo "\nTesting: " . route('v2_video_location');
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
	 * @link Route Path: https://api.dbp.localhost/video/path?v=2&dam_id=ENGESV&pretty
	 *

	public function test_video_video_path() {
		$bible = Video::inRandomOrder()->first();
		$response = $this->get(route('v2_video_video_path'), ['v' => 2, 'dam_id' => $bible->id]);
		echo "\nTesting: " . route('v2_video_video_path');
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
	 * @link Route Path: https://api.dbp.localhost/country/countrylang?v=2&pretty
	 *

	public function test_country_lang() {
		$response = $this->get(route('v2_country_lang'), ['v' => 2, 'country_additional' => true, 'sort_by' => 'name']);
		echo "\nTesting: " . route('v2_country_lang', ['v' => 2, 'country_additional' => true, 'sort_by' => 'name']);

		$response->assertSuccessful();
	 * $response->assertJsonStructure([$this->getSchemaKeys('CountryLang')]);
	}
	 * */

}
