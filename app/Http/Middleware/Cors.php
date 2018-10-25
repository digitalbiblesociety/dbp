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
	    if ($request->getMethod() === 'OPTIONS') {
		    return response(['OK'], 204)->withHeaders([
			    'Access-Control-Allow-Origin'      => '*',
			    'Access-Control-Allow-Methods'     => 'GET, POST, PUT, OPTIONS, DELETE',
			    'Access-Control-Allow-Credentials' => true,
			    'Access-Control-Allow-Headers'     => 'DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range',
			    'Access-Control-Max-Age'           => 1728000,
			    'Content-type'                     => 'text/plain; charset=utf-8',
			    'Content-Length'                   => 0
		    ]);
	    }

	    $response = $next($request);
	    $response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE');
	    $response->header('Access-Control-Allow-Headers', '*');
	    $response->header('Access-Control-Allow-Origin', '*');
	    return $response;
    }
}
