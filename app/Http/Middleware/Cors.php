<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	    if ($request->isMethod('OPTIONS')) {
		    app()->options($request->path(), function() { return response('', 200); });
	    }

	    $response = $next($request);
	    $response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE');
	    $response->header('Access-Control-Allow-Headers', '*');
	    $response->header('Access-Control-Allow-Origin', '*');
	    return $response;
    }
}
