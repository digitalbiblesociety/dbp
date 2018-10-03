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
	    if ($request->getMethod() == "OPTIONS") {
		    return response(['OK'], 200)->withHeaders([
			    'Access-Control-Allow-Origin' => '*',
			    'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE',
			    'Access-Control-Allow-Credentials' => true,
			    'Access-Control-Allow-Headers' => '*',
		    ]);
	    }

	    $response = $next($request);
	    $response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE');
	    $response->header('Access-Control-Allow-Headers', '*');
	    $response->header('Access-Control-Allow-Origin', '*');
	    return $response;
    }
}
