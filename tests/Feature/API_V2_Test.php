<?php

namespace Tests\Feature;

use App\Models\Bible\Book;
use App\Models\Language\NumeralSystem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class API_V2_Test extends TestCase
{

	protected $params;
	protected $swagger;
	protected $schemas;
	protected $key;

	/**
	 * API_V2_Test constructor
	 *
	 *
	 */
	function setUp() {
		parent::setUp();
		config(['app.url' => 'https://api.dbp.test']);

		$this->key = '53355c32fca5f3cac4d7a670d2df2e09';
		$this->params = ['v' => 2,'key' => $this->key,'pretty'];

		// Fetch the Swagger Docs for Structure Validation
		$arrContextOptions= [ "ssl" => ["verify_peer"=>false, "verify_peer_name"=>false]];
		$swagger_url = 'https://dbp.test/swagger_docs?v=v2';
		$this->swagger = json_decode(file_get_contents($swagger_url, false, stream_context_create($arrContextOptions)), true);
		$this->schemas = $this->swagger['components']['schemas'];
		ini_set('memory_limit', '1264M');
	}

	public function getSchemaKeys($schema)
	{
		if(isset($this->schemas[$schema]['items'])) return array_keys($this->schemas[$schema]['items']['properties']);
		return array_keys($this->schemas[$schema]['properties']);
	}

	public function compareToOriginal($path,$schemaStructure)
	{
		$params = implode('&', array_map(function ($v, $k) { return sprintf("%s=%s", $k, $v); }, $this->params, array_keys($this->params)));
		$path = str_replace('http://api.dbp.test/','',$path);
		$v2_route = route('v2_pass_through', ltrim($path,'/'),false)."?".$params;
		echo "\nTesting: $v2_route";
		$v2_response = $this->withHeaders(['params' => $params,'v' => 2,'key' => $this->key])->get($v2_route);
		$v2_response->assertSuccessful();
		$v2_response->assertJsonStructure($schemaStructure);
	}

	/**
	 *
	 * Test Library Asset Route
	 *
	 * @category V2_Library
	 * @see HomeController::libraryAsset()
	 * @category Swagger ID: LibraryAsset
	 * @category Route Name: v2_library_asset
	 * @link Route Path: https://api.dbp.test/library/asset?v=2&key=1234&pretty
     * @link V2 Route Path: https://dbt.io/library/asset?v=2&key=3e0eed1a69fc6e012fef51b8a28cc6ff&pretty
	 *
	 */
	public function test_library_asset() {
		$path = route('v2_library_asset', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
		//$response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_library_asset')]);
		//$this->compareToOriginal($path,[$this->withHeaders($this->params)->getSchemaKeys('v2_library_asset')]);
	}

    /**
     *
     * Test API Reply Route
     *
     * @category V2_API
     * @see HomeController::versionReplyTypes()
     * @category Swagger ID: APIReply
     * @category Route Name: v2_api_apiReply
     * @link Route Path: https://api.dbp.test/api/reply?v=2&pretty&key=1234
     * @link V2 Route Path: https://dbt.io/api/reply?v=2&key=3e0eed1a69fc6e012fef51b8a28cc6ff&pretty
     *
     */
	public function test_api_apiReply() {
		$path = route('v2_api_apiReply', $this->params);
		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
    }

	/**
	 *
	 * Test Library Version Route
	 *
	 * @category V2_Library
	 * @see HomeController::versionLatest()
	 * @category Swagger ID: LibraryAsset
	 * @category Route Name: v2_library_asset
	 * @link Route Path: https://api.dbp.test/api/apiversion?v=2&pretty&key=1234
	 *
	 */
	public function test_library_version() {
		$path = route('v2_api_versionLatest',[],false);
		$params = implode('&', array_map(function ($v, $k) { return sprintf("%s=%s", $k, $v); }, $this->params, array_keys($this->params)));
		//echo "\nTesting: " . route('v2_api_versionLatest', $this->params),'light_cyan',true);
		$response = $this->withHeaders([$params])->get(route('v2_api_versionLatest'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure($this->withHeaders($this->params)->getSchemaKeys('v2_api_versionLatest'));
		$this->compareToOriginal($path,$this->withHeaders($this->params)->getSchemaKeys('v2_api_versionLatest'));
	}

	/**
	 * Test Library Book Order Route
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\Connections\V2Controllers\LibraryCatalog\BooksController::bookOrder
	 * @category Swagger ID: v2_library_bookOrder
	 * @category Route Name: v2_library_bookOrder
	 * @link Route Path: https://api.dbp.test/library/bookorder?v=2&dam_id=ENGESV&pretty&key=1234
	 * @link V2 Route Path: https://dbt.io/library/bookorder?v=2&dam_id=AAIWBTN2ET&key=3e0eed1a69fc6e012fef51b8a28cc6ff&pretty
	 *
	 */
	public function test_library_bookOrder() {
		$this->params['dam_id'] = "AAIWBTN2ET";
		$path = route('v2_library_bookOrder',$this->params);
		echo "\nTesting: $path";

		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('v2_library_bookOrder')]);
		$this->compareToOriginal(route('v2_library_bookOrder',$this->params,false),[$this->withHeaders($this->params)->getSchemaKeys('v2_library_bookOrder')]);
	}

	/**
	 *
	 * Test Library Book
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\Connections\V2Controllers\LibraryCatalog\BooksController::book
	 * @category Swagger ID: v2_library_book
	 * @category Route Name: v2_library_book
	 * @link Test Route Path: https://api.dbp.test/library/book?v=2&dam_id=AAIWBTN2ET&key=1234&pretty
	 * @link V2 Route Path: https://dbt.io/library/book?v=2&dam_id=AAIWBTN2ET&key=3e0eed1a69fc6e012fef51b8a28cc6ff&pretty
	 *
	 */
	public function test_library_book() {
		$path = route('v2_library_book', [], false);
		$this->params['dam_id'] = 'ENGESV';

		echo "\nTesting: " . route('v2_library_book', $this->params);
		$response = $this->withHeaders($this->params)->get(route('v2_library_book'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->getSchemaKeys('v2_library_book')]);
		$this->compareToOriginal($path,[$this->withHeaders($this->params)->getSchemaKeys('v2_library_book')]);
	}

	/**
	 * Tests the Library Book Name Route
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\Connections\V2Controllers\LibraryCatalog\BooksController::bookNames()
	 * @category Swagger ID: BookName
	 * @category Route Name: v2_library_bookName
	 * @link Route Path: https://api.dbp.test/library/bookname?v=2&language_code=eng&pretty&key=1234
	 *
	 */
	public function test_library_bookName() {
		$path = route('v2_library_bookName',$this->params);
		$this->params['language_code'] = 'eng';

		echo "\nTesting: " . route('v2_library_bookName', $this->params);
		$response = $this->withHeaders($this->params)->get($path);

		$response->assertSuccessful();
		$response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('BookName')]);

		$this->params['language_code'] = strtoupper($this->params['language_code']);
		$this->compareToOriginal($path,[$this->withHeaders($this->params)->getSchemaKeys('BookName')]);
	}

	/**
	 * Tests the Library Chapter Route
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\Connections\V2Controllers\LibraryCatalog\BooksController::chapters()
	 * @category Swagger ID: BookName
	 * @category Route Name: v2_library_bookName
	 * @link Route Path: https://api.dbp.test/library/chapter?v=2&dam_id=AAIWBTN2ET&book_id=GEN&pretty&key=1234
	 * @link V2 Route Path: https://dbt.io/library/chapter?v=2&dam_id=AAIWBTN2ET&key=3e0eed1a69fc6e012fef51b8a28cc6ff
	 */
	public function test_library_chapter() {
		$path = route('v2_library_chapter', [], false);
		$this->params['dam_id'] = 'AAIWBTN2ET';
		$this->params['book_id'] = 'Matt';

		echo "\nTesting: " . route('v2_library_chapter', $this->params);
		$response = $this->withHeaders($this->params)->get(route('v2_library_chapter'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_library_chapter')]);
		$this->compareToOriginal($path,[$this->withHeaders($this->params)->getSchemaKeys('v2_library_chapter')]);
	}

	/**
	 *
	 * Tests the Library Language Route
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\Connections\V2Controllers\LibraryCatalog\LanguageController::languageListing()
	 * @category Swagger ID: LibraryLanguage
	 * @category Route Name: v2_library_language
	 * @link Route Path: https://api.dbp.test/library/language?v=2&pretty&key=1234
	 *
	 */
	public function test_library_language() {
		$path = route('v2_library_language', [], false);

		echo "\nTesting: " . route('v2_library_language');
		$response = $this->withHeaders($this->params)->get(route('v2_library_language'), $this->params);
		$response->assertSuccessful();
		//$response->assertJsonStructure($this->withHeaders($this->params)->getSchemaKeys('v2_library_language'));
		//$this->compareToOriginal($path,[$this->withHeaders($this->params)->getSchemaKeys('v2_library_language')]);
	}

	/**
	 *
	 * Tests the Library Verse Info Route
	 *
	 * @category V2_Library
	 * @see VerseController::info()
	 * @category Swagger ID: LibraryVerseInfo
	 * @category Route Name: v2_library_verseInfo
	 * @link Route Path: https://api.dbp.test/library/verseinfo?v=2&pretty&bible_id=ENGESV&book_id=GEN&chapter=1&verse_start=1&key=1234
	 * @link https://dbt.io/library/verseinfo?v=2&pretty&bible_id=ENGESV&book_id=GEN&chapter=1&verse_start=1
	 *
	 */
	public function test_library_verseInfo() {
		$path = route('v2_library_verseInfo', [], false);
        $random_bible = collect(\DB::connection('sophia')->select('SHOW TABLES'))
            ->pluck('Tables_in_sophia')->filter(function ($table) {
                return str_contains($table, '_vpl');
            })->random();

		$verse = \DB::connection('sophia')->table($random_bible)->inRandomOrder()->first();
		$book = Book::where('id_usfx',$verse->book)->first();
		$chapter = $verse->chapter;
		$verse_start = $verse->verse_start;
		$verse_end = $verse->verse_start + 5;

		$this->params['bible_id'] = substr($random_bible,0,-4);
		$this->params['book_id'] = $book->id;
		$this->params['chapter'] = $chapter;
		$this->params['verse_start'] = $verse_start;
		$this->params['verse_end'] = $verse_end;

		echo "\nTesting: " . route('v2_library_verseInfo', $this->params);
		$response = $this->withHeaders($this->params)->get(route('v2_library_verseInfo'), $this->params);
		$response->assertSuccessful();
		// TODO: Get a working example of v2
		// $response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('LibraryVerseInfo')]);
		// $this->compareToOriginal($path,[$this->withHeaders($this->params)->getSchemaKeys('LibraryVerseInfo')]);
	}

	/**
	 *
	 * Tests the Library Numbers Route
	 *
	 * @category V2_Library
	 * @see NumbersController::customRange()
	 * @category Swagger ID: Number
	 * @category Route Name: v2_library_numbers
	 * @link Route Path: https://api.dbp.test/library/numbers?v=2&pretty&iso=arb&start=1&end=50&key=1234&script=Arab
	 *
	 */
	public function test_library_numbers() {
	    $random_script = NumeralSystem::inRandomOrder()->first();
		$this->params['script'] = $random_script->id;
		$this->params['start'] = 1;
		$this->params['end'] = 100;

		$path = route('v2_library_numbers', $this->params);

		echo "\nTesting: " . route('v2_library_numbers', $this->params);
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v4_numbers_range')]);
		// DBT.io version is broken
		// $this->compareToOriginal($path,[$this->withHeaders($this->params)->getSchemaKeys('v4_numbers_range')]);
	}

	/**
	 *
	 * Tests the Library MetaData Route
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\Connections\V2Controllers\LibraryCatalog\LibraryMetadataController::index
	 * @category Swagger ID: LibraryMetaData
	 * @category Route Name: v2_library_metadata
	 * @link Route Path: https://api.dbp.test/library/metadata?v=2&dam_id=ENGESVN1ET&key=1234
	 * @link Source Path: https://dbt.io/library/metadata?v=2&dam_id=ENGESVN1ET&key=53355c32fca5f3cac4d7a670d2df2e09
	 *
	 */
	public function test_library_metadata()
	{
		$path = route('v2_library_metadata', [], false);
		// Test Default Route
		echo "\nTesting Default Route for Library Metadata";
		$response = $this->withHeaders($this->params)->get(route('v2_library_metadata'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_library_metadata')]);
		$this->compareToOriginal($path,[$this->withHeaders($this->params)->getSchemaKeys('v2_library_metadata')]);

		echo "\nTesting Route with dam_id specified for Library Metadata";
		$this->params['dam_id'] = 'ENGESVN1ET';
		$response = $this->withHeaders($this->params)->get(route('v2_library_metadata'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_library_metadata')]);
		$this->compareToOriginal($path,[$this->withHeaders($this->params)->getSchemaKeys('v2_library_metadata')]);
	}

	/**
	 *
	 * Tests the Library Volume Route
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\Connections\V2Controllers\LibraryCatalog\LibraryVolumeController@libraryVolume
	 * @category Swagger ID: LibraryVolume
	 * @category Route Name: v2_library_volume
	 * @link Route Path: https://api.dbp.test/library/volume?v=2&pretty&key=1234
	 * @link Route:
	 *
	 */
	public function test_library_volume()
	{
		$path = route('v2_library_volume', [], false);

		echo "\nTesting: " . route('v2_library_volume', $this->params);
		$response = $this->withHeaders($this->params)->get(route('v2_library_volume'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_library_volume')]);
		$this->compareToOriginal($path,[$this->withHeaders($this->params)->getSchemaKeys('v2_library_volume')]);
	}

	/**
	 *
	 * Tests the Volume Language Route
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\Connections\V2Controllers\LibraryCatalog\LanguageController::volumeLanguage()
	 * @category Swagger ID: LibraryVolume
	 * @category Route Name: v2_library_volumeLanguage
	 * @link Route Path: https://api.dbp.test/library/volumelanguage?v=2&pretty&key=1234
	 *
	 */
	public function test_library_volumeLanguage() {
		$path = route('v2_library_volumeLanguage', [], false);

		echo "\nTesting: " . route('v2_library_volumeLanguage', $this->params);
		$response = $this->withHeaders($this->params)->get(route('v2_library_volumeLanguage'), $this->params);
		$response->assertSuccessful();
		$response->assertJsonStructure( [$this->withHeaders($this->params)->getSchemaKeys('v2_library_language')]);
		$this->compareToOriginal($path, [$this->withHeaders($this->params)->getSchemaKeys('v2_library_language')]);
	}

	/**
	 *
	 * Tests the Volume Language Family
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\Connections\V2Controllers\LibraryCatalog\LanguageController::volumeLanguage()
	 * @category Swagger ID: LibraryVolumeLanguageFamily
	 * @category Route Name: v2_library_volumeLanguageFamily
	 * @link Route Path: https://api.dbp.test/library/volumelanguagefamily?v=2&pretty&key=1234
	 * @link Route Path: https://dbt.io/library/volumelanguagefamily?v=2&key=53355c32fca5f3cac4d7a670d2df2e09
	 *
	 */
	public function test_library_volumeLanguageFamily() {
		$path = route('v2_library_volumeLanguageFamily', $this->params);

		echo "\nTesting: " . route('v2_library_volumeLanguageFamily', $this->params);
		$response = $this->withHeaders($this->params)->get($path);
		$response->assertSuccessful();
		$response->assertJsonStructure( [$this->withHeaders($this->params)->getSchemaKeys('v2_library_volumeLanguageFamily')]);
		$this->compareToOriginal($path, [$this->withHeaders($this->params)->getSchemaKeys('v2_library_volumeLanguageFamily')]);
	}

	/**
	 *
	 * Tests the Volume Organization List
	 *
	 * @category V2_Library
	 * @see \app\Http\Controllers\OrganizationsController::index()
	 * @category Swagger ID: VolumeOrganization
	 * @category Route Name: v2_volume_organization_list
	 * @link Route Path: https://api.dbp.test/library/volumeorganization?v=2&pretty&key=1234
	 *
	 */
	public function test_volume_organization_list() {
		$path = route('v2_volume_organization_list', $this->params);

		echo "\nTesting: $path";
		$response = $this->withHeaders($this->params)->get($path, $this->params);
		$response->assertSuccessful();
		//$response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('LibraryVolumeLanguageFamily')]);
		//$this->compareToOriginal($path,$this->withHeaders($this->params)->getSchemaKeys('LibraryVolumeLanguageFamily'));
	}

	/**
	 *
	 * Tests the Volume History
	 *
	 * @category V2_Library
	 * @see BiblesController::history
	 * @category Swagger ID: LibraryVolumeLanguageFamily
	 * @category Route Name: v2_volume_history
	 * @link Route Path: https://api.dbp.test/library/volumehistory?v=2&pretty&key=1234
	 *
	 */
	public function test_volume_history()
	{
		$path = route('v2_volume_history', [], false);
		$this->params['limit'] = 5;

		echo "\nTesting: " . route('v2_volume_history', $this->params);
		$response = $this->withHeaders($this->params)->get(route('v2_volume_history'), $this->params);
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
	 * @link Route Path: https://api.dbp.test/audio/path?v=2&dam_id=AFRNVVN2DA&pretty&key=1234
	 *
	 */
	public function test_audio_path()
	{
		$this->params['dam_id'] = 'AFRNVVN2DA';

		echo "\nTesting: " . route('v2_audio_path', $this->params);
		$response = $this->withHeaders($this->params)->get(route('v2_audio_path'), $this->params);
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
	 * @link Route Path: https://api.dbp.test/audio/versestart?v=2&fileset_id=CHNUNVN2DA&chapter=1&book=MAT&pretty&key=1234
	 *
	 */
	//public function test_audio_timestamps() {
//
	//	$this->params['fileset_id'] = 'CHNUNVN2DA';
	//	$this->params['chapter'] = '1';
	//	$this->params['book'] = 'MAT';
//
	//	// TODO: AUDIO TIMESTAMPS
	//	//echo "\nTesting: " . route('v2_audio_timestamps', $this->params);
	//	//$response = $this->withHeaders($this->params)->get(route('v2_audio_timestamps'), $this->params);
	//	//$response->assertSuccessful();
	//	//$response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('AudioTimestamp')]);
	//}

	/**
	 *
	 * Tests the Text Font
	 *
	 * @category V2_Text
	 * @see \app\Http\Controllers\TextController::fonts()
	 * @category Swagger ID: TextFont
	 * @category Route Name: v2_text_font
	 * @link Route Path: https://api.dbp.test/text/font?v=2&platform=web&key=1234&pretty
	 *
	 */
	public function test_text_font() {
		$this->params['platform'] = 'web';

		echo "\nTesting: " . route('v2_text_font', $this->params);
		$response = $this->withHeaders($this->params)->get(route('v2_text_font'), $this->params);
		$response->assertSuccessful();
	}

	/**
	 * \\ TODO:Tests the Text Verse
	 *
	 * @category V2_Text
	 * @see \app\Http\Controllers\TextController::index()
	 * @category Swagger ID: TextVerse
	 * @category Route Name: v2_text_verse
	 * @link Route Path: https://api.dbp.test/text/verse?v=2&key=1234&dam_id=ENGESV&book_id=GEN&chapter_id=1&verse_start=1&verse_end=10
	 *

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
		$response = $this->withHeaders($this->params)->get(route('v2_text_verse', $this->params));
		$response->assertSuccessful();
	}
	 */
	/**
	 *
	 * Tests the Text Search
	 *
	 * @category V2_Text
	 * @see \App\Http\Controllers\Bible\TextController::search()
	 * @category Swagger ID: TextSearch
	 * @category Route Name: v2_text_search
	 * @link Route Path: https://api.dbp.test/text/search?v=2&query=God&dam_id=ENGESV&limit=5&pretty&key=1234
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
		$response = $this->withHeaders($this->params)->get(route('v2_text_search'), $this->params);
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
	 * @link Route Path: https://api.dbp.test/text/searchgroup?v=2&query=God&dam_id=ENGESV&limit=5&pretty&key=1234
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
		$response = $this->withHeaders($this->params)->get(route('v2_text_search_group'), $this->params);
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
	 * @link Route Path: https://api.dbp.test/video/location?v=2&dam_id=ENGESV
	 *

	public function test_video_location() {
		$bible = Video::inRandomOrder()->first();
		$response = $this->withHeaders($this->params)->get(route('v2_video_location'), ['v' => 2,'dam_id' => $bible->id]);
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
	 * @link Route Path: https://api.dbp.test/video/path?v=2&dam_id=ENGESV&pretty
	 *

	public function test_video_video_path() {
		$bible = Video::inRandomOrder()->first();
		$response = $this->withHeaders($this->params)->get(route('v2_video_video_path'), ['v' => 2, 'dam_id' => $bible->id]);
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
	 * @link Route Path: https://api.dbp.test/country/countrylang?v=2&pretty
	 *

	public function test_country_lang() {
		$response = $this->withHeaders($this->params)->get(route('v2_country_lang'), ['v' => 2, 'country_additional' => true, 'sort_by' => 'name']);
		echo "\nTesting: " . route('v2_country_lang', ['v' => 2, 'country_additional' => true, 'sort_by' => 'name']);

		$response->assertSuccessful();
	 * $response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('CountryLang')]);
	}
	 * */

}
