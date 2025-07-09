<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->user_type !== 'admin') {
            return response()->json(['message' => 'Access denied. Admin privileges required.'], 403);
        }

        return $next($request);
    }
}
