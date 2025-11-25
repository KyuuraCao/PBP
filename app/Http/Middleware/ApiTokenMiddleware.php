<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
	{
		$token = $request->header('API-TOKEN');

		if ($token !== env('API_TOKEN')) {
			return response()->json([
				'status' => false,
				'message' => 'Unauthorized'
			], 401);
		}

		return $next($request);
	}

}
