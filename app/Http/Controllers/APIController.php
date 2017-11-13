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
use \Spatie\Fractalistic\ArraySerializer;
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
    protected $serializer;
    protected $v;

    public function __construct(Request $request)
    {
	    $url = explode(".",url()->current());
	    $this->request = $request;
	    if(substr(array_shift($url),-3,3) == "api") {
	    	$noVersionRoutes = ['v2_api_apiversion','v4_api_versionLatest'];
	    	if(!in_array(\Route::currentRouteName(), $noVersionRoutes)) $this->v = checkParam('v');
		    $this->api = true;
		    if(isset($this->v)) $this->serializer = (($this->v == "jQueryDataTable") OR ($this->v != 2)) ? new DataArraySerializer() : new ArraySerializer();
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
    public function reply($object, $transformer = null, $key = 0, $pretty = 0)
    {
    	if(isset($_GET['echo'])) $object = [$_GET,$object];
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
                return response()->make($formatter->toXml(), $this->getStatusCode())->header('Content-Type', 'text/xml; charset=utf-8');
            case 'yaml':
                $formatter = Formatter::make($object, Formatter::ARR);
                return response()->make($formatter->toYaml(), $this->getStatusCode())->header('Content-Type', 'text/yaml; charset=utf-8');
            case 'csv':
                $formatter = Formatter::make($object, Formatter::ARR);
                return response()->make($formatter->toCsv(), $this->getStatusCode())->header('Content-Type', 'text/csv; charset=utf-8');
            default:
                if(isset($_GET['pretty']) OR $pretty != 0) {
                    return response()->json($object, $this->getStatusCode(), [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)->header('Content-Type', 'application/json; charset=utf-8');
                } else {
                    return response()->json($object, $this->getStatusCode(), [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)->header('Content-Type', 'application/json; charset=utf-8');
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

        return response()->json(['error' => [
            'message' => $message,
            'status code' => $status
        ]], $this->getStatusCode(), array(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)->header('Content-Type', 'text/json');
    }

}