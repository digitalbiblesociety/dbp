<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Language\Language;
use App\Models\User\Key;

use Illuminate\Support\Facades\Route;
use SoapBox\Formatter\Formatter;
use League\Fractal\Serializer\DataArraySerializer;

use Spatie\Fractalistic\ArraySerializer;
use Spatie\ArrayToXml\ArrayToXml;

class APIController extends Controller
{
	// Top Level Swagger Docs

	/**
	 * @OA\Info(
	 *     description="A Bible API",
	 *     version="4.0.0",
	 *     title="Digital Bible Platform",
	 *     termsOfService="http://dbp4.org/terms/",
	 *     @OA\Contact(email="jon@dbs.org"),
	 *     @OA\License(name="Apache 2.0",url="http://www.apache.org/licenses/LICENSE-2.0.html")
	 * )
	 *
	 * @OA\Server(
	 *     url="https://api.dbp4.org",
	 *     description="Live Server",
	 *     @OA\ServerVariable( serverVariable="schema", enum={"https"}, default="https")
	 * )
	 *
	 * @OA\Server(
	 *     url="https://api.dbp.localhost",
	 *     description="Development server",
	 *     @OA\ServerVariable( serverVariable="schema", enum={"https"}, default="https")
	 * )
	 *
	 * @OA\Parameter(parameter="version_number",name="v",in="query",description="The Version Number",required=true,@OA\Schema(type="integer",enum={2,4},example=4))
	 * @OA\Parameter(parameter="key",name="key",in="query",description="The Key granted to the api user upon sign up",required=true,@OA\Schema(type="string",example="ar45g3h4ae644"))
	 * @OA\Parameter(parameter="pretty",name="pretty",in="query",description="Setting this param to true will add human readable whitespace to the return",@OA\Schema(type="boolean"))
	 * @OA\Parameter(parameter="format",name="format",in="query",description="Setting this param to true will add format the return as a specific file type. The currently supported return types are `xml`, `csv`, `json`, and `yaml`",@OA\Schema(type="string",enum={"xml","csv","json","yaml"}))
	 * @OA\Parameter(name="sort_by", in="query", description="The field to sort by", @OA\Schema(type="string"))
	 * @OA\Parameter(name="sort_dir", in="query", description="The direction to sort by", @OA\Schema(type="string",enum={"asc","desc"}))
	 * @OA\Parameter(name="l10n", in="query", description="When set to a valid three letter language iso, the returning results will be localized in the language matching that iso. (If an applicable translation exists).", @OA\Schema(ref="#/components/schemas/Language/properties/iso")),
	 *
	 */

	/**
	 * Version 2 Tags
	 *
	 * @OA\Tag(name="Library Audio",    description="v2 These methods retrieve all the information needed to build and retrieve audio information for each chapter/book/or volume.")
	 * @OA\Tag(name="Library Catalog",  description="v2 These methods retrieve all the information needed to build and retrieve audio information for each chapter/book/or volume.")
	 * @OA\Tag(name="Library Text",     description="v2 These methods allow the caller to retrieve Bible text in a variety of configurations.")
	 * @OA\Tag(name="Library Video",    description="v2 These calls address the information needed to build and retrieve video information for each volume.")
	 * @OA\Tag(name="Country Language", description="v2 These calls provide all information pertaining to country languages.")
	 * @OA\Tag(name="Study Programs",   description="v2 These calls provide all information pertaining to Bible study programs.")
	 * @OA\Tag(name="API",              description="v2 These calls provide basic information regarding API specifics.")
	 *
	 */

	/**
	 * Version 4 Tags
	 *
	 * @OA\Tag(name="Languages",       description="v4 ")
	 * @OA\Tag(name="Countries",       description="v4 ")
	 * @OA\Tag(name="Bibles",          description="v4 ")
	 * @OA\Tag(name="Users",           description="v4 ")
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

	public function __construct()
	{
		$url           = explode('.', url()->current());
		$subdomain     = array_shift($url);

		if (str_contains($subdomain,'api')) {
			$this->api = true;
			$this->v   = (int) checkParam('v');
			$this->key = checkParam('key');
			$keyExists = Key::find($this->key);
			if(!$keyExists) abort(403, 'You need to provide a valid API key');

			// i18n
			$i18n = checkParam('i18n',null,'optional') ?? 'eng';
			$GLOBALS['i18n_iso'] = $i18n;
			$GLOBALS['i18n_id'] = Language::where('iso',$i18n)->select('iso','id')->first()->id;
			$this->serializer = (($this->v === 1) || ($this->v === 2) || ($this->v === 3)) ? new ArraySerializer() : new DataArraySerializer();
		}
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
	public function reply($object, $meta = [], $s3_transaction_id = null)
	{
		if (isset($_GET['echo'])) $object = [$_GET, $object];
		$input  = checkParam('callback|jsonp', null, 'optional');
		$format = checkParam('reply|format', null, 'optional');

		// Status Code, Headers, Params, Body, Time
		apiLogs(request(), $this->getStatusCode(),$s3_transaction_id);

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
	public function replyWithError($message, $action = null)
	{
		$status = $this->getStatusCode();
		if((env('APP_ENV') != 'local') AND (env('APP_ENV') != 'testing')) apiLogs(request(), $status);

		if ((!$this->api AND !isset($status)) OR isset($_GET['local'])) redirect()->route('error')->with(['message' => $message, 'status' => $status]);
		if (!$this->api OR isset($_GET['local'])) return redirect()->route("errors.$status", compact('message'))->with(['message' => $message]);
		$faces = ['⤜(ʘ_ʘ)⤏', '¯\_ツ_/¯', 'ᗒ ͟ʖᗕ', 'ᖗ´• ꔢ •`ᖘ', '|▰╭╮▰|'];


		if((int) $this->v === 2 && (env('APP_ENV') != 'local')) return [];

		$error = [
				'message'     => $message,
				'status code' => $status,
				'status'      => 'Fail',
				'face'        => array_random($faces)
		];
		if($action) $error['action'] = $action;

		return response()->json(['error' => $error], $this->getStatusCode(), [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}

	function utf8_for_xml($string)
	{
		return preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
	}

}