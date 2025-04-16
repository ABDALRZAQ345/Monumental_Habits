<?php

namespace App\Http\Middleware;

use Closure;

class LocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        // handling languages or maybe last login or any thing else that happen in each request

        return $next($request);

    }
}
