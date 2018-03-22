<?php
namespace App\Http\Controllers;

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

class APIController extends Controller
{

    /**
     * The statusCode is a http status code. Every variation of this
     * must also be a http status code. There is a full list here
     * https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
     *
     * @var int
     */
    protected $statusCode = 200;
    protected $isoPreference = false;
    protected $api;
    protected $serializer;
    protected $v;
    protected $key;

    public function __construct(Request $request)
    {
	    $url = explode(".",url()->current());
	    $this->request = $request;
	    if(substr(array_shift($url),-3,3) == "api") {
		    $this->api = true;
		    $this->v = checkParam('v');
			$keyExists = Key::find(checkParam('key'));
			if(!isset($keyExists)) {abort(403,'No Authentication Provided');}
			$this->key = $keyExists->key;

		    if(isset($this->v)) {
		    	switch ($this->v) {
				    case "2": {$this->serializer = new ArraySerializer();break;}
				    case "3": {$this->serializer = new EmbeddedArraySerializer(); break;}
				    default: $this->serializer = new DataArraySerializer();
			    }
		    }
	    }
        $this->middleware('auth')->only(['create','edit']);
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
     * @return mixed
     */
    public function reply($object, $meta = [])
    {
    	if(isset($_GET['echo'])) $object = [$_GET,$object];
		$input = checkParam('callback', null, 'optional') ?? checkParam('jsonp', null, 'optional');
        $format = @$_GET['format'];
        switch ($format) {
            case 'xml':
                $formatter = ArrayToXml::convert($object, [
	                	'rootElementName' => (isset($meta['rootElementName'])) ? $meta['rootElementName'] : 'root',
                        '_attributes' => (isset($meta['rootAttributes'])) ? $meta['rootAttributes'] : []
	                ],true,"utf-8");
                return response()->make($formatter, $this->getStatusCode())->header('Content-Type', 'text/xml; charset=utf-8');
            case 'yaml':
                $formatter = Formatter::make($object, Formatter::ARR);
                return response()->make($formatter->toYaml(), $this->getStatusCode())->header('Content-Type', 'text/yaml; charset=utf-8');
            case 'csv':
                $formatter = Formatter::make($object, Formatter::ARR);
                return response()->make($formatter->toCsv(), $this->getStatusCode())->header('Content-Type', 'text/csv; charset=utf-8');
            default:
                if(isset($_GET['pretty'])) {
                    return response()->json($object, $this->getStatusCode(), [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)->header('Content-Type', 'application/json; charset=utf-8')->setCallback($input);
                } else {
                    return response()->json($object, $this->getStatusCode(), [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)->header('Content-Type', 'application/json; charset=utf-8')->setCallback($input);
                }
        }
    }

    /**
     * @param $message
     * @return mixed
     */
    public function replyWithError($message)
    {
        $status = $this->getStatusCode();
	    if(!$this->api AND !isset($status)) return view('errors.broken',compact('message'));
	    if(!$this->api) return view("errors.$status",compact('message','status'));
	    $faces = ['⤜(ʘ_ʘ)⤏','¯\_ツ_/¯','ᗒ ͟ʖᗕ','ᖗ´• ꔢ •`ᖘ','|▰╭╮▰|'];

        return response()->json(['error' => [
            'message' => $message,
            'status code' => $status,
	        'face' => array_random($faces)
        ]], $this->getStatusCode(), array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)->header('Content-Type', 'text/json');
    }

	public function signedUrl($url)
	{
		$signer = $_GET['signer'] ?? 's3_fcbh';
		$bucket = $_GET['bucket'] ?? "dbp-dev";
		$expiry = $_GET['expiry'] ?? 5;

		$url = Bucket::signedUrl($url,$signer,$bucket,$expiry);
		return $this->reply($url);
	}

	function utf8_for_xml($string)
	{
		return preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
	}

}