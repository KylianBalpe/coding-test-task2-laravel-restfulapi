<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role !== "admin") {
            return response()->json([
                "ok" => false,
                "status" => 403,
                "message" => "Unauthorized",
            ], 403);
        }

        return $next($request);
    }
}
