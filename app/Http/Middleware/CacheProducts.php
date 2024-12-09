<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class CacheProducts
{
    public function handle(Request $request, Closure $next)
    {
        $page = $request->input('page', 1);
        $cacheKey = 'products_page_' . $page;

        if (Cache::has($cacheKey)) {
            return response(Cache::get($cacheKey));
        }
        $response = $next($request);

        Cache::put($cacheKey, $response->getContent(), now()->addMinutes(30));

        return $response;
    }
}
