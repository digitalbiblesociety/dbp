<?php

namespace App\Http\Controllers;

use App\Models\Bible\Bible;
use App\Models\Bible\BibleFileset;
use App\Models\Organization\Asset;
use App\Models\User\Changelog;
use App\Traits\CallsBucketsTrait;
use Illuminate\Support\Facades\Cache;

class ApiMetadataController extends APIController
{
    use CallsBucketsTrait;

    /**
     *
     *
     * @param null    $path1
     * @param null    $path2
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function passThrough($path1 = null, $path2 = null)
    {
        $params = checkParam('params') ?? $_GET;
        if (\is_array($params)) {
            $params = implode('&', array_map(function ($v, $k) {
                if ($k === 'key') {
                    return 'key='.config('services.bibleIs.key');
                }
                if ($k === 0) {
                    return $v;
                }
                return sprintf('%s=%s', $k, $v);
            }, $params, array_keys($params)));
        }
        $contents = json_decode(file_get_contents('https://dbt.io/'.$path1.'/'.$path2.'?'.$params));
        return response()->json($contents);
    }


    /**
     *
     * @category v4_api.gitVersion
     * @link http://api.dbp.test/api/git/version?key={key}&v=4
     *
     * @return mixed
     */
    public function gitVersion()
    {
        $dir = getcwd();
        chdir(base_path());

        $head    = shell_exec('git rev-parse HEAD');
        $tags    = shell_exec('git describe --tags');

        chdir($dir);

        return $this->reply([
            'head' => trim($head),
            'tags' => trim($tags)
        ]);
    }

    public function getStatus()
    {
        $status_code = 200;
        try {
            \DB::connection('dbp_users')->getPdo();
            $user_connection_message = 'live';
        } catch (\Exception $e) {
            $user_connection_message = $e->getMessage();
            $status_code = 417;
        }

        try {
            \DB::connection('dbp')->getPdo();
            $dbp_connection_message = 'live';
        } catch (\Exception $e) {
            $dbp_connection_message = $e->getMessage();
            $status_code = 417;
        }

        try {
            \Cache::forget('cache_test');
            \Cache::add('cache_test', 'live', 5);
            $cache_test = \Cache::get('cache_test', 'failed by default');
        } catch (\Exception $e) {
            $cache_test = $e->getMessage();
            $status_code = 417;
        }

        $connection = [
            'bibles_count' => Bible::count(),
            'systems' => [
                'status_code' => $status_code,
                'cache' => $cache_test
            ],
            'database'  => [
                'users' => $user_connection_message,
                'dbp'   => $dbp_connection_message
            ]
        ];

        return $this->setStatusCode($status_code)->reply($connection);
    }

    /**
     *
     * Returns an array of signed audio urls
     *
     * @category v2_library_asset
     * @link http://api.dbp4.org/library/asset - V4 Access
     * @link https://api.dbp.test/library/asset?key=1234&v=4&pretty - V4 Test Access
     * @link https://dbp.test/eng/docs/swagger/gen#/Version_2/v2_library_asset - V4 Test Docs
     *
     * @OA\Get(
     *     path="/library/asset",
     *     tags={"Library Catalog"},
     *     summary="Returns Library File path information",
     *     description="This call returns the file path information. This information can be used with the response of the locations calls to create a URI to retrieve files.",
     *     operationId="v2_library_asset",
     *     @OA\Parameter(name="dam_id", in="query", description="The DAM ID for which to retrieve file path info.", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/id")),
     *     @OA\Parameter(name="asset_id", in="query", description="Will filter the results by the given Asset", @OA\Schema(ref="#/components/schemas/BibleFileset/properties/asset_id")),
     *     @OA\Parameter(name="asset_type", in="query", description="The asset type to filter result by.", @OA\Schema(ref="#/components/schemas/Asset/properties/asset_type")),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_library_asset")),
     *         @OA\MediaType(mediaType="application/xml", @OA\Schema(ref="#/components/schemas/v2_library_asset")),
     *         @OA\MediaType(mediaType="text/csv", @OA\Schema(ref="#/components/schemas/v2_library_asset")),
     *         @OA\MediaType(mediaType="text/x-yaml", @OA\Schema(ref="#/components/schemas/v2_library_asset"))
     *     )
     * )
     *
     * @OA\Schema (
     *     type="object",
     *     schema="v2_library_asset",
     *     description="v2_library_asset",
     *     title="v2_library_asset",
     *     @OA\Xml(name="v2_library_asset"),
     *     @OA\Property(property="server",type="string",example="cloud.faithcomesbyhearing.com"),
     *     @OA\Property(property="root_path",type="string",example="/mp3audiobibles2"),
     *     @OA\Property(property="protocol",type="string",example="http"),
     *     @OA\Property(property="CDN",type="string",example="1"),
     *     @OA\Property(property="priority",type="string",example="5"),
     *     @OA\Property(property="volume_id",type="string",example="")
     * )
     *
     * @return mixed
     */
    public function assets()
    {
        $dam_id   = checkParam('dam_id|fileset_id');
        $asset_id = checkParam('bucket|bucket_id|asset_id') ?? config('filesystems.disks.s3_fcbh.bucket');
        $asset_type = checkParam('asset_type');

        if ($dam_id) {
            $fileset = BibleFileset::uniqueFileset($dam_id, $asset_id, $asset_type)->first();
            if (!$fileset) {
                return $this->setStatusCode(404)->replyWithError(trans('api.bible_fileset_errors_404'));
            }
        }

        return $this->reply([
            [
                'server'    => 'content.cdn.dbp-prod.dbp4.org',
                'root_path' => '/audio',
                'protocol'  => 'https',
                'CDN'       => '1',
                'priority'  => '1',
                'volume_id' => $dam_id,
            ]
        ]);
    }

    /**
     *
     * Returns an array of version return types
     *
     * @category v2_video_path
     * @link http://api.dbp4.org/api/reply - V4 Access
     * @link https://api.dbp.test/api/reply?key=1234&v=4&pretty - V4 Test Access
     * @link https://dbp.test/eng/docs/swagger/gen#/Version_2/v2_api_apiReply - V4 Test Docs
     *
     * @OA\Get(
     *     path="/api/apiversion",
     *     tags={"API"},
     *     summary="Returns version information",
     *     description="Gives information about return types of the different versions of the APIs",
     *     operationId="v2_api_versionLatest",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_api_versionLatest")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v2_api_versionLatest")),
     *         @OA\MediaType(mediaType="text/csv", @OA\Schema(ref="#/components/schemas/v2_api_versionLatest")),
     *         @OA\MediaType(mediaType="text/x-yaml", @OA\Schema(ref="#/components/schemas/v2_api_versionLatest"))
     *     )
     * )
     *
     * @OA\Schema (
     *     type="object",
     *     schema="v2_api_versionLatest",
     *     description="The return for the api reply",
     *     title="v2_api_versionLatest",
     *     @OA\Xml(name="v2_api_apiReply"),
     *     @OA\Property(property="Version",type="string",example="2.0.0"),
     * )
     *
     * @return mixed
     */
    public function versionLatest()
    {
        return $this->reply(['Version' => 4]);
    }

    /**
     *
     * Returns an array of version return types
     *
     * @category v2_api_apiReply
     * @link http://api.dbp4.org/api/reply - V4 Access
     * @link https://api.dbp.test/api/reply?key=1234&v=4&pretty - V4 Test Access
     * @link https://dbp.test/eng/docs/swagger/gen#/Version_2/v2_api_apiReply - V4 Test Docs
     *
     * @OA\Get(
     *     path="/api/reply",
     *     tags={"API"},
     *     summary="Returns version information",
     *     description="Gives information about return types of the different versions of the APIs",
     *     operationId="v2_api_apiReply",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/v2_api_apiReply")),
     *         @OA\MediaType(mediaType="application/xml",  @OA\Schema(ref="#/components/schemas/v2_api_apiReply")),
     *         @OA\MediaType(mediaType="text/csv", @OA\Schema(ref="#/components/schemas/v2_api_apiReply")),
     *         @OA\MediaType(mediaType="text/x-yaml", @OA\Schema(ref="#/components/schemas/v2_api_apiReply"))
     *     )
     * )
     *
     * @OA\Schema (
     *     type="object",
     *     schema="v2_api_apiReply",
     *     description="The return for the api reply",
     *     title="v2_api_apiReply",
     *     @OA\Xml(name="v2_api_apiReply"),
     *     example={"json", "jsonp", "html"}
     * )
     *
     * @return mixed
     */
    public function replyTypes()
    {
        $versionReplies = [
            '2' => ['json', 'jsonp', 'html'],
            '4' => ['json', 'jsonp', 'xml', 'html'],
        ];

        return $this->reply($versionReplies[$this->v]);
    }

    public function refreshDevCache()
    {
        if (config('app.server_name') != 'APP_DEV') {
            return $this->setStatusCode(422)->replyWithError('This is not the dev server');
        }
        Cache::flush();
        return $this->reply('Cache Flushed successfully');
    }

    public function changelog()
    {
        return $this->reply(Changelog::orderBy('released_at', 'desc')->get()->groupBy('subheading'));
    }
}
