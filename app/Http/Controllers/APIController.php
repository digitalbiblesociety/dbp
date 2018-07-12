<?php

namespace App\Http\Controllers;

use App\Jobs\send_api_logs;
use App\Transformers\EmbeddedArraySerializer;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use SoapBox\Formatter\Formatter;
use App\Models\User\User;
use App\Models\User\Access;
use App\Models\User\Key;
use i18n;
use League\Fractal\Serializer\DataArraySerializer;
use \Spatie\Fractalistic\ArraySerializer;
use Spatie\ArrayToXml\ArrayToXml;

use Illuminate\Support\Facades\Log;

class APIController extends Controller
{

	// Top Level Swagger Docs

	/**
	 * @OAS\Info(
	 *     description="A Bible API",
	 *     version="4.0.0",
	 *     title="Digital Bible Platform",
	 *     termsOfService="http://bible.build/terms/",
	 *     @OAS\Contact(email="jon@dbs.org"),
	 *     @OAS\License(name="Apache 2.0",url="http://www.apache.org/licenses/LICENSE-2.0.html")
	 * )
	 *
	 * @OAS\Server(
	 *     url="https://api.bible.build",
	 *     description="Live Server",
	 *     @OAS\ServerVariable( serverVariable="schema", enum={"https"}, default="https")
	 * )
	 *
	 * @OAS\Server(
	 *     url="https://api.dbp.localhost",
	 *     description="Development server",
	 *     @OAS\ServerVariable( serverVariable="schema", enum={"https"}, default="https")
	 * )
	 *
	 * @OAS\Parameter(parameter="version_number",name="v",in="query",description="The Version Number",required=true,@OAS\Schema(type="integer",enum={2,4},example=4))
	 * @OAS\Parameter(parameter="key",name="key",in="query",description="The Key granted to the api user upon sign up",required=true,@OAS\Schema(type="string",example="ar45g3h4ae644"))
	 * @OAS\Parameter(parameter="pretty",name="pretty",in="query",description="Setting this param to true will add human readable whitespace to the return",@OAS\Schema(type="boolean"))
	 * @OAS\Parameter(parameter="format",name="format",in="query",description="Setting this param to true will add format the return as a specific file type. The currently supported return types are `xml`, `csv`, `json`, and `yaml`",@OAS\Schema(type="string",enum={"xml","csv","json","yaml"}))
	 * @OAS\Parameter(name="sort_by", in="query", description="The field to sort by", @OAS\Schema(type="string"))
	 * @OAS\Parameter(name="sort_dir", in="query", description="The direction to sort by", @OAS\Schema(type="string",enum={"asc","desc"}))
	 * @OAS\Parameter(name="l10n", in="query", description="When set to a valid three letter language iso, the returning results will be localized in the language matching that iso. (If an applicable translation exists).", @OAS\Schema(ref="#/components/schemas/Language/properties/iso")),
	 *
	 */

	/**
	 * Version 2 Tags
	 *
	 * @OAS\Tag(name="Library Audio",    description="v2 These methods retrieve all the information needed to build and retrieve audio information for each chapter/book/or volume.")
	 * @OAS\Tag(name="Library Catalog",  description="v2 These methods retrieve all the information needed to build and retrieve audio information for each chapter/book/or volume.")
	 * @OAS\Tag(name="Library Text",     description="v2 These methods allow the caller to retrieve Bible text in a variety of configurations.")
	 * @OAS\Tag(name="Library Video",    description="v2 These calls address the information needed to build and retrieve video information for each volume.")
	 * @OAS\Tag(name="Country Language", description="v2 These calls provide all information pertaining to country languages.")
	 * @OAS\Tag(name="Study Programs",   description="v2 These calls provide all information pertaining to Bible study programs.")
	 * @OAS\Tag(name="API",              description="v2 These calls provide basic information regarding API specifics.")
	 *
	 */

	/**
	 * Version 4 Tags
	 *
	 * @OAS\Tag(name="Languages",       description="v4 ")
	 * @OAS\Tag(name="Countries",       description="v4 ")
	 * @OAS\Tag(name="Bibles",          description="v4 ")
	 * @OAS\Tag(name="Users",           description="v4 ")
	 *
	 */

	/**
	 * The statusCode is a http status code. Every variation of this
	 * must also be a http status code. There is a full list here
	 * https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	 *
	 * @var int $statusCode
	 */
	protected $statusCode = 200;
	protected $api;
	protected $serializer;
	protected $v;
	protected $key;

	public function __construct(Request $request)
	{
		$url           = explode(".", url()->current());
		$this->request = $request;
		if (substr(array_shift($url), -3, 3) == "api") {
			$this->api = true;
			$this->v   = checkParam('v');
			$this->key = checkParam('key');
			$keyExists = Key::find($this->key);
			if (!isset($keyExists)) abort(403, "You need to provide a valid API key");
			$locale = $keyExists->preferred_language ?? \i18n::getCurrentLocale();
			\App::setLocale($locale);

			if (isset($this->v)) {
				switch ($this->v) {
					case "2": {
						$this->serializer = new ArraySerializer();
						break;
					}
					case "3": {
						$this->serializer = new ArraySerializer();
						break;
					}
					default:
						$this->serializer = new DataArraySerializer();
				}
			}
		}
		//$this->middleware('auth')->only(['create','edit']);
	}

	/**
	 * @return mixed
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * Set Status Code
	 *
	 * @param mixed $statusCode
	 *
	 * @return mixed
	 */
	public function setStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;

		return $this;
	}

	/**
	 *
	 * Get The object and return it in the format requested via query params.
	 *
	 * @param $object
	 *
	 * @return mixed
	 */
	public function reply($object, $meta = [], $s3response = false)
	{
		if (isset($_GET['echo'])) {
			$object = [$_GET, $object];
		}
		$input  = checkParam('callback|jsonp', null, 'optional');
		$format = checkParam('reply|format', null, 'optional');

		// Status Code, Headers, Params, Body, Time

		try {
			if($s3response) {
				$response_object = collect($object->toarray());
				$url_strings = (isset($response_object['data'])) ? $response_object['data']->pluck('path') : $response_object->pluck('path');
				$out_string = '';
				foreach($url_strings as $url_string) {
					parse_str($url_string,$output);
					$out_string .= $output['X-Amz-Signature'].'|';
				}
				sendLogsToS3($this->request, $this->getStatusCode(), $out_string);
			} else {
				sendLogsToS3($this->request, $this->getStatusCode());
			}
		} catch (Exception $e) {
			//    //echo 'Caught exception: ',  $e->getMessage(), "\n";
		}

		switch ($format) {
			case 'xml':
				$formatter = ArrayToXml::convert($object->toArray(), [
					'rootElementName' => (isset($meta['rootElementName'])) ? $meta['rootElementName'] : 'root',
					'_attributes'     => (isset($meta['rootAttributes'])) ? $meta['rootAttributes'] : [],
				], true, "utf-8");

				return response()->make($formatter, $this->getStatusCode())->header('Content-Type',
					'application/xml; charset=utf-8');
			case 'yaml':
				$formatter = Formatter::make($object, Formatter::ARR);

				return response()->make($formatter->toYaml(), $this->getStatusCode())->header('Content-Type',
					'text/yaml; charset=utf-8');
			case 'csv':
				$formatter = Formatter::make($object, Formatter::ARR);

				return response()->make($formatter->toCsv(), $this->getStatusCode())->header('Content-Type',
					'text/csv; charset=utf-8');
			default:
				if (isset($_GET['pretty'])) {
					return response()->json($object, $this->getStatusCode(), [],
						JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)->header('Content-Type',
						'application/json; charset=utf-8')->setCallback($input);
				} else {
					return response()->json($object, $this->getStatusCode(), [],
						JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)->header('Content-Type',
						'application/json; charset=utf-8')->setCallback($input);
				}
		}
	}

	/**
	 * @param $message
	 *
	 * @return mixed
	 */
	public function replyWithError($message)
	{
		$status = $this->getStatusCode();

		//\Log::error([$message, $status]);
		sendLogsToS3($this->request, $status);

		if (!$this->api AND !isset($status)) {
			return view('errors.broken', compact('message'));
		}
		if (!$this->api) {
			return view("errors.$status", compact('message', 'status'));
		}
		$faces = ['⤜(ʘ_ʘ)⤏', '¯\_ツ_/¯', 'ᗒ ͟ʖᗕ', 'ᖗ´• ꔢ •`ᖘ', '|▰╭╮▰|'];


		if ($this->v == 2) {
			return [];
		}

		return response()->json([
			'error' => [
				'message'     => $message,
				'status code' => $status,
				'status'      => "Fail",
				'face'        => array_random($faces),
			],
		], $this->getStatusCode(), [],
			JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)->header('Content-Type', 'text/json');
	}

	public function signedUrl($url)
	{
		$signer = $_GET['signer'] ?? 's3_fcbh';
		$bucket = $_GET['bucket'] ?? "dbp-dev";
		$expiry = $_GET['expiry'] ?? 5;

		$url = Bucket::signedUrl($url, $signer, $bucket, $expiry);

		return $this->reply($url);
	}

	function utf8_for_xml($string)
	{
		return preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
	}

}