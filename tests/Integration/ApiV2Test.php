<?php

namespace Tests\Integration;

use App\Models\Bible\BibleFileset;
use App\Models\Bible\BibleFilesetCopyright;
use App\Models\Bible\BibleVerse;
use App\Models\Language\NumeralSystem;
use App\Models\User\Key;
use App\Models\Bible\Video;
use Tests\TestCase;

class ApiV2Test extends TestCase
{
    protected $params;
    protected $swagger;
    protected $schemas;
    protected $key;

    /**
     * Api_V2_Test constructor
     */
    public function setUp()
    {
        parent::setUp();
        $this->key = Key::where('name', 'test-key')->first()->key;
        $this->params = ['v' => 2,'key' => $this->key,'pretty'];

        // Fetch the Swagger Docs for Structure Validation
        $arrContextOptions = ['ssl' => ['verify_peer' =>false, 'verify_peer_name' =>false]];
        $swagger_url       = base_path('resources/assets/js/swagger_v2.json');
        $this->swagger = json_decode(file_get_contents($swagger_url, false, stream_context_create($arrContextOptions)), true);
        $this->schemas = $this->swagger['components']['schemas'];
        ini_set('memory_limit', '1264M');
    }

    public function getSchemaKeys($schema)
    {
        if (isset($this->schemas[$schema]['items'])) {
            return array_keys($this->schemas[$schema]['items']['properties']);
        }
        return array_keys($this->schemas[$schema]['properties']);
    }

    public function compareToOriginal($path, $schemaStructure)
    {
        $params = implode('&', array_map(function ($v, $k) {
            return sprintf('%s=%s', $k, $v);
        }, $this->params, array_keys($this->params)));
        $path = str_replace('http://api.dbp.test/', '', $path);
        $v2_route = route('v2_pass_through', ltrim($path, '/'), false) . '?' . $params;
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
     * @link Route Path: https://api.dbp.test/library/asset?v=2&key={key}&pretty
     * @link V2 Route Path: https://dbt.io/library/asset?v=2&key=3e0eed1a69fc6e012fef51b8a28cc6ff&pretty
     * @group    V2
     * @test
     */
    public function libraryAsset()
    {
        $path = route('v2_library_asset', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
        $response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_library_asset')]);
        //$this->compareToOriginal($path, [$this->withHeaders($this->params)->getSchemaKeys('v2_library_asset')]);
    }

    /**
     *
     * Test API Reply Route
     *
     * @category V2_API
     * @see ApiMetadataController::versionReplyTypes()
     * @category Swagger ID: APIReply
     * @category Route Name: v2_api_apiReply
     * @link Route Path: https://api.dbp.test/api/reply?v=2&pretty&key={key}
     * @link V2 Route Path: https://dbt.io/api/reply?v=2&key={key}&pretty
     * @group    V2
     * @test
     */
    public function apiReplyReturnsSuccessful()
    {
        $path = route('v2_api_apiReply', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();

        // The response value is static to the version and should always be what the example describes
        $response->assertExactJson($this->schemas['v2_api_apiReply']['example']);
    }

    /**
     *
     * Test Library Version Route
     *
     * @category V2_Library
     * @see HomeController::versionLatest()
     * @category Swagger ID: LibraryAsset
     * @category Route Name: v2_library_asset
     * @link Route Path: https://api.dbp.test/api/apiversion?v=2&pretty&key={key}
     * @group    V2
     * @test
     */
    public function libraryVersion()
    {
        $params = implode('&', array_map(function ($v, $k) {
            return sprintf('%s=%s', $k, $v);
        }, $this->params, array_keys($this->params)));
        //echo "\nTesting: " . route('v2_api_versionLatest', $this->params),'light_cyan',true);
        $response = $this->withHeaders([$params])->get(route('v2_api_versionLatest'), $this->params);
        $response->assertSuccessful();
        $response->assertJsonStructure($this->withHeaders($this->params)->getSchemaKeys('v2_api_versionLatest'));
        //$this->compareToOriginal($path, $this->withHeaders($this->params)->getSchemaKeys('v2_api_versionLatest'));
    }

    /**
     * Test Library Book Order Route
     *
     * @category V2_Library
     * @see \app\Http\Controllers\Bible\BooksControllerV2::bookOrder()
     * @category Swagger ID: v2_library_bookOrder
     * @category Route Name: v2_library_bookOrder
     * @link Route Path: https://api.dbp.test/library/bookorder?v=2&dam_id=ENGESV&pretty&key={key}
     * @link V2 Route Path: https://dbt.io/library/bookorder?v=2&dam_id=AAIWBTN2ET&key=3e0eed1a69fc6e012fef51b8a28cc6ff&pretty
     * @group    V2
     * @test
     */
    public function libraryBookOrder()
    {
        $hash_id = BibleVerse::where('id', random_int(1, BibleVerse::count()))->select('hash_id')->first()->hash_id;
        $bible_fileset = BibleFileset::where('hash_id',$hash_id)->first();

        $this->params['asset_id'] = $bible_fileset->asset_id;
        $this->params['dam_id']   = $bible_fileset->id;

        $path = route('v2_library_bookOrder', $this->params);
        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
        $response->assertJsonStructure([$this->getSchemaKeys('v2_library_bookOrder')]);
        //$this->compareToOriginal(route('v2_library_bookOrder', $this->params, false), [$this->withHeaders($this->params)->getSchemaKeys('v2_library_bookOrder')]);
    }

    /**
     *
     * Test Library Book
     *
     * @category V2_Library
     * @see \app\Http\Controllers\BooksControllerV2::book
     * @category Swagger ID: v2_library_book
     * @category Route Name: v2_library_book
     * @link Test Route Path: https://api.dbp.test/library/book?v=2&dam_id=AAIWBTN2ET&key={key}&pretty
     * @link V2 Route Path: https://dbt.io/library/book?v=2&dam_id=AAIWBTN2ET&key=3e0eed1a69fc6e012fef51b8a28cc6ff&pretty
     * @group    V2
     * @test
     */
    public function libraryBook()
    {
        $this->params['dam_id'] = 'ENGESV';

        echo "\nTesting: " . route('v2_library_book', $this->params);
        $response = $this->withHeaders($this->params)->get(route('v2_library_book'), $this->params);
        $response->assertSuccessful();
        $response->assertJsonStructure([$this->getSchemaKeys('v2_library_book')]);
        //$this->compareToOriginal($path, [$this->withHeaders($this->params)->getSchemaKeys('v2_library_book')]);
    }

    /**
     * Tests the Library Book Name Route
     *
     * @category V2_Library
     * @see \app\Http\Controllers\Bible\BooksControllerV2::bookNames()
     * @category Swagger ID: BookName
     * @category Route Name: v2_library_bookName
     * @link Route Path: https://api.dbp.test/library/bookname?v=2&language_code=eng&pretty&key={key}
     * @group    V2
     * @test
     */
    public function libraryBookName()
    {
        $path = route('v2_library_bookName', $this->params);
        $this->params['language_code'] = 'eng';

        echo "\nTesting: " . route('v2_library_bookName', $this->params);
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
        $response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('BookName')]);

        $this->params['language_code'] = strtoupper($this->params['language_code']);
        //$this->compareToOriginal($path, [$this->withHeaders($this->params)->getSchemaKeys('BookName')]);
    }

    /**
     * Tests the Library Chapter Route
     *
     * @category V2_Library
     * @see \app\Http\Controllers\BooksControllerV2::chapters()
     * @category Swagger ID: BookName
     * @category Route Name: v2_library_bookName
     * @link Route Path: https://api.dbp.test/library/chapter?v=2&dam_id=AAIWBTN2ET&book_id=GEN&pretty&key={key}
     * @group    V2
     * @test@link V2 Route Path: https://dbt.io/library/chapter?v=2&dam_id=AAIWBTN2ET&key=3e0eed1a69fc6e012fef51b8a28cc6ff
     */
    public function libraryChapter()
    {
        $this->params['dam_id'] = 'AAIWBTN2ET';
        $this->params['book_id'] = 'Matt';

        echo "\nTesting: " . route('v2_library_chapter', $this->params);
        $response = $this->withHeaders($this->params)->get(route('v2_library_chapter'), $this->params);
        $response->assertSuccessful();
        $response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_library_chapter')]);
        //$this->compareToOriginal($path, [$this->withHeaders($this->params)->getSchemaKeys('v2_library_chapter')]);
    }

    /**
     *
     * Tests the Library Language Route
     *
     * @category V2_Library
     * @see \app\Http\Controllers\LanguageControllerV2::languageListing()
     * @category Swagger ID: LibraryLanguage
     * @category Route Name: v2_library_language
     * @link Route Path: https://api.dbp.test/library/language?v=2&pretty&key={key}
     * @group    V4
     * @test
     */
    public function libraryLanguage()
    {
        $path = route('v2_library_language', [], false);

        echo "\nTesting: " . route('v2_library_language');
        $response = $this->withHeaders($this->params)->get(route('v2_library_language'), $this->params);
        $response->assertSuccessful();
        $response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_library_language')]);
        //$this->compareToOriginal($path, [$this->withHeaders($this->params)->getSchemaKeys('v2_library_language')]);
    }

    /**
     *
     * Tests the Library Verse Info Route
     *
     * @category V2_Library
     * @see VerseController::info()
     * @category Swagger ID: LibraryVerseInfo
     * @category Route Name: v2_library_verseInfo
     * @link Route Path: https://api.dbp.test/library/verseinfo?v=2&pretty&bible_id=ENGESV&book_id=GEN&chapter=1&verse_start=1&key={key}
     * @link https://dbt.io/library/verseinfo?v=2&pretty&bible_id=ENGESV&book_id=GEN&chapter=1&verse_start=1
     * @group    V4
     * @test
     */
    public function libraryVerseInfo()
    {
        $random_verse = BibleVerse::with('fileset')->where('id', random_int(1, BibleVerse::count()))->first();
        $this->params['bible_id'] = optional($random_verse->fileset)->id;
        $this->params['book_id'] = $random_verse->book_id;
        $this->params['chapter'] = $random_verse->chapter;
        $this->params['verse_start'] = $random_verse->verse_start;
        $this->params['asset_id'] = optional($random_verse->fileset)->asset_id;

        $path = route('v2_library_verseInfo', $this->params);
        echo "\nTesting: " . $path;
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
        $response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_library_verseInfo')]);

        // This cannot be compared reliably as too many new bibles have been added to the bible_filesets table
        //$this->compareToOriginal($path, [$this->withHeaders($this->params)->getSchemaKeys('v2_library_verseInfo')]);
    }

    /**
     *
     * Tests the Library Numbers Route
     *
     * @category V2_Library
     * @see NumbersController::customRange()
     * @category Swagger ID: Number
     * @category Route Name: v2_library_numbers
     * @link Route Path: https://api.dbp.test/library/numbers?v=2&pretty&iso=arb&start=1&end=50&key={key}&script=Arab
     * @group    V2
     * @test
     */
    public function libraryNumbers()
    {
        $numeral_systems = NumeralSystem::all();
        foreach ($numeral_systems as $numeral_system) {
            $this->params['script'] = $numeral_system->id;
            $this->params['start'] = 1;
            $this->params['end'] = 100;

            $path = route('v2_library_numbers', $this->params);

            echo "\nTesting: " . route('v2_library_numbers', $this->params);
            $response = $this->withHeaders($this->params)->get($path);
            $response->assertSuccessful();
            //$response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v4_numbers_range')]);
        }

        // DBT.io version is broken
        // //$this->compareToOriginal($path,[$this->withHeaders($this->params)->getSchemaKeys('v4_numbers_range')]);
    }

    /**
     *
     * Tests the Library MetaData Route
     *
     * @category V2_Library
     * @see \app\Http\Controllers\Bible\LibraryController::metadata()
     * @category Swagger ID: LibraryMetaData
     * @category Route Name: v2_library_metadata
     * @link Route Path: https://api.dbp.test/library/metadata?v=2&dam_id=ENGESVN1ET&key={key}
     * @link Source Path: https://dbt.io/library/metadata?v=2&dam_id=ENGESVN1ET&key=53355c32fca5f3cac4d7a670d2df2e09
     * @group    V2
     * @test
     */
    public function libraryMetadata()
    {
        $path = route('v2_library_metadata', $this->params);
        // Test Default Route
        echo "\nTesting Default Route for Library Metadata";
        echo "\n".$path;
        $response = $this->withHeaders($this->params)->get(route('v2_library_metadata'), $this->params);
        $response->assertSuccessful();
        $response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_library_metadata')]);
        //$this->compareToOriginal($path, [$this->withHeaders($this->params)->getSchemaKeys('v2_library_metadata')]);
    }

    /**
     *
     * Tests the Library Volume Route
     *
     * @category V2_Library
     * @see \app\Http\Controllers\Bible\LibraryController::volume()
     * @category Swagger ID: LibraryVolume
     * @category Route Name: v2_library_volume
     * @link Route Path: https://api.dbp.test/library/volume?v=2&pretty&key={key}
     * @link Route:
     * @group    V2
     * @test
     */
    public function libraryVolume()
    {
        $path = route('v2_library_volume', [], false);
        echo "\nTesting: " . route('v2_library_volume', $this->params);
        $response = $this->withHeaders($this->params)->get(route('v2_library_volume'), $this->params);
        $response->assertSuccessful();
        $response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_library_volume')]);
        //$this->compareToOriginal($path, [$this->withHeaders($this->params)->getSchemaKeys('v2_library_volume')]);
    }

    /**
     *
     * Tests the Volume Language Route
     *
     * @category V2_Library
     * @see \app\Http\Controllers\Wiki\LanguageControllerV2::volumeLanguage()
     * @category Swagger ID: LibraryVolume
     * @category Route Name: v2_library_volumeLanguage
     * @link Route Path: https://api.dbp.test/library/volumelanguage?v=2&pretty&key={key}
     * @group    V2
     * @test
     */
    public function libraryVolumeLanguage()
    {
        $path = route('v2_library_volumeLanguage', [], false);

        echo "\nTesting: " . route('v2_library_volumeLanguage', $this->params);
        $response = $this->withHeaders($this->params)->get(route('v2_library_volumeLanguage'), $this->params);
        $response->assertSuccessful();
        $response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_library_language')]);
        //$this->compareToOriginal($path, [$this->withHeaders($this->params)->getSchemaKeys('v2_library_language')]);
    }

    /**
     *
     * Tests the Volume Language Family
     *
     * @category V2_Library
     * @see \app\Http\Controllers\Wiki\LanguageControllerV2::volumeLanguage()
     * @category Swagger ID: LibraryVolumeLanguageFamily
     * @category Route Name: v2_library_volumeLanguageFamily
     * @link Route Path: https://api.dbp.test/library/volumelanguagefamily?v=2&pretty&key={key}
     * @link Route Path: https://dbt.io/library/volumelanguagefamily?v=2&key=53355c32fca5f3cac4d7a670d2df2e09
     * @group    V2
     * @test
     */
    public function libraryVolumeLanguageFamily()
    {
        $path = route('v2_library_volumeLanguageFamily', $this->params);

        echo "\nTesting: " . route('v2_library_volumeLanguageFamily', $this->params);
        $response = $this->withHeaders($this->params)->get($path);
        $response->assertSuccessful();
        $response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_library_volumeLanguageFamily')]);
        //$this->compareToOriginal($path, [$this->withHeaders($this->params)->getSchemaKeys('v2_library_volumeLanguageFamily')]);
    }

    /**
     *
     * Tests the Volume Organization List
     *
     * @category V2_Library
     * @see OrganizationsController::index()
     * @category Swagger ID: VolumeOrganization
     * @category Route Name: v2_volume_organization_list
     * @link Route Path: https://api.dbp.test/library/volumeorganization?v=2&pretty&key={key}
     * @group    V2
     * @test
     */
    public function volumeOrganizationList()
    {
        $path = route('v2_volume_organization_list', $this->params);

        echo "\nTesting: $path";
        $response = $this->withHeaders($this->params)->get($path, $this->params);
        $response->assertSuccessful();
        $response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_volume_organization_list')]);
        //$this->compareToOriginal($path, [$this->withHeaders($this->params)->getSchemaKeys('v2_volume_organization_list')]);
    }

    /**
     *
     * Tests the Volume History
     *
     * @category V2_Library
     * @see BiblesController::history
     * @category Swagger ID: LibraryVolumeLanguageFamily
     * @category Route Name: v2_volume_history
     * @link Route Path: https://api.dbp.test/library/volumehistory?v=2&pretty&key={key}
     * @group    V2
     * @test
     */
    public function volumeHistory()
    {
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
     * @link Route Path: https://api.dbp.test/audio/path?v=2&dam_id=AFRNVVN2DA&pretty&key={key}
     * @group    V2
     * @test
     */
    public function audioPath()
    {
        $this->params['dam_id'] = 'AFRNVVN2DA';

        echo "\nTesting: " . route('v2_audio_path', $this->params);
        $response = $this->withHeaders($this->params)->get(route('v2_audio_path'), $this->params);
        $response->assertSuccessful();
    }


    /**
     *
     * Tests the Video Location
     *
     * @category V2_Video
     * @see FilmsController::location()
     * @category Swagger ID: VideoLocation
     * @category Route Name: v2_video_location
     * @link Route Path: https://api.dbp.test/video/location?v=2&dam_id=ENGESV
     * @link Old Path: https://dbt.io/video/location?v=2&dam_id=ENGESV
     * @test
     */

    public function videoLocation()
    {
        $response = $this->withHeaders($this->params)->get(route('v2_video_location'), ['v' => 2]);
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
     * @test
     */

    public function videoPath()
    {
        $fileset = BibleFileset::where('set_type_code', 'video_stream')->inRandomOrder()->first();
        $response = $this->withHeaders($this->params)->get(route('v2_video_path'), ['v' => 2, 'dam_id' => $fileset->id]);
        echo "\nTesting: " . route('v2_video_path');
        $response->assertSuccessful();
    }

    /**
     *
     * Tests the Video Path
     *
     * @category V2_Meta
     * @see \app\Http\Controllers\LanguageControllerV2::countryLang()
     * @category Swagger ID: CountryLang
     * @category Route Name: v2_country_lang
     * @link Route Path: https://api.dbp.test/country/countrylang?v=2&pretty
     * @test
     */

    public function countryLang()
    {
        $response = $this->withHeaders($this->params)->get(route('v2_country_lang'), ['v' => 2, 'country_additional' => true, 'sort_by' => 'name']);
        echo "\nTesting: " . route('v2_country_lang', ['v' => 2, 'country_additional' => true, 'sort_by' => 'name']);

        $response->assertSuccessful();
        $response->assertJsonStructure([$this->withHeaders($this->params)->getSchemaKeys('v2_country_lang')]);
    }
}
