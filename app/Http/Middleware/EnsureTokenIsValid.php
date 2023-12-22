<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to check the API token is valid
 */
class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request The incoming request
     * @param Closure $next    The next middleware to apply
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('X-TOKEN') !== env('API_TOKEN')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
