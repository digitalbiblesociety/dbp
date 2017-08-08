<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use SoapBox\Formatter\Formatter;
use App\Models\User\User;
use i18n;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\ArraySerializer;

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
    protected $paginateNumber;
    protected $api;

    public function __construct(Request $request)
    {
	    $url = explode(".",url()->current());
	    $this->request = $request;
	    if(substr(array_shift($url),-3,3) == "api") {
		    $this->v = checkParam('v');
		    $this->api = true;
		    $this->serializer = ($this->v == "jQueryDataTable") ? new DataArraySerializer() : new ArraySerializer();
		    $this->paginateNumber = $_GET["number"] ?? 20;
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

    public function getVerse($abbr,$reference)
    {
        $id = \DB::connection('sophia')->table('bible_list')->where('fcbhId',$abbr)->first();
        if(isset($id)) {
            $verse = \DB::connection('sophia')->table(str_replace('-','_',$id->translationId).'_vpl')->where('verseID',$reference)->first();
            return $verse;
        }
        return NULL;
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

    public function setIsoPreference($isoPreference)
    {
        $this->isoPreference = $isoPreference;
        return $this;
    }

    /**
     *
     * Get The object and return it in the format requested via query params.
     *
     * @param $object
     * @return mixed
     */
    public function reply($object, $transformer = null, $key = 0, $pretty = 0)
    {
        if (isset($_GET['key'])) {
            $key = \DB::table('users')->where('id','=',$_GET['key'])->first();
            if(!$key) return $this->setStatusCode(403)->replyWithError('Provided authentication key does not match a key in our records');
        } elseif($this->request->header('authorization')) {
            $key = User::where('api_key','=',base64_decode($this->request->header('authorization')))->first();
            if(!$key) return $this->setStatusCode(403)->replyWithError('Provided Authentication Header does not match a key in our records');
        } elseif($key) {
            return $this->setStatusCode(403)->replyWithError('Missing Authentication key');
        }

        $format = @$_GET['format'];
        switch ($format) {
            case 'xml':
                $formatter = Formatter::make($object, Formatter::ARR);
                return response()->make($formatter->toXml(), $this->getStatusCode())->header('Content-Type', 'text/xml;  charset=utf-8');
            case 'yaml':
                $formatter = Formatter::make($object, Formatter::ARR);
                return response()->make($formatter->toYaml(), $this->getStatusCode())->header('Content-Type', 'text/yaml; charset=utf-8');
            case 'csv':
                $formatter = Formatter::make($object, Formatter::ARR);
                return response()->make($formatter->toCsv(), $this->getStatusCode())->header('Content-Type', 'text/csv; charset=utf-8');
            default:
                if(isset($_GET['pretty']) OR $pretty != 0) {
                    return response()->json($object, $this->getStatusCode(), array(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)->header('Content-Type', 'text/json');
                } else {
                    return response()->json($object, $this->getStatusCode(), array(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)->header('Content-Type', 'text/json');
                }

        }
    }

    /**
     *
     * Fetches Json Feed from external API and saves it to storage
     *
     * @param $url
     * @param $name
     * @return mixed|string
     */
    public function saveJson($url, $name)
    {

        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n"
            )
        );

        $context = stream_context_create($opts);
        $json = file_get_contents($url, false, $context);
        file_put_contents(storage_path() . '/data/' . $name . '.json', $json);
        $json = json_decode($json, true);
        return $json;
    }

    /**
     * @param $url
     * @param $name
     * @return mixed|string
     */
    public function fetchJson($url, $name)
    {
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n"
            )
        );
        $context = stream_context_create($opts);
        $json = file_get_contents($url, false, $context);
        $json = json_decode($json, true);
        return $json;
    }

    /**
     * @param $message
     * @return mixed
     */
    public function replyWithError($message)
    {
        $status = $this->getStatusCode();

        if(!$this->api) return view('errors.broken',compact('message','status'));

        return response()->json(['error' => [
            'message' => $message,
            'status code' => $status
        ]], $this->getStatusCode(), array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)->header('Content-Type', 'text/json');
    }

    public function replyWithErrorView($message)
    {
        $status = $this->getStatusCode();
        return view('errors.broken', compact('message','status'));
    }


    /**
     * Removes undesirable keys from array elements
     *
     * @param array $flattened
     * @param array $children
     * @return array
     */
    public function flattenChildren(array $flattened, array $children) {
        foreach($flattened as $key => $value) {
            foreach($children as $child) {
                if(isset($value[$child])) {
                    $flattened[$key][$child] = array_flatten($flattened[$key][$child]);
                }
            }
        }
        return $flattened;
    }


    public function vernacular_numbers($number) {

        switch(i18n::getCurrentLocale()) {
            case 'ara':
                $number = number_format($number);
                $arabic_eastern = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
                $arabic_western = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
                $number = str_replace($arabic_western, $arabic_eastern, $number);
                return $number;
                break;
        }
    }

    public function transformDataTable($objects,$name,$primary,$primaryTitle = null)
    {
        foreach($objects as $key => $object) {
            if(isset($primaryTitle)) {
                $object[$primary] = '<a href="/'.$name.'/'.$object[$primary].'">'.$object[$primaryTitle].'</a>';
                unset($object[$primaryTitle]);
            } else {
                $object[$primary] = '<a href="/'.$name.'/'.$object[$primary].'">'.$object[$primary].'</a>';
            }

            $objects[$key] = array_flatten($object);
        }
        return ['data' => $objects];

    }

}