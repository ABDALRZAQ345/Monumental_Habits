<?php

namespace App\Http\Middleware;

use Closure;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Http\Request;

class XssProtection
{
    // to protect from xss attacks
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {

        $userInput = $request->all();
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        array_walk_recursive($userInput, function (&$value) use ($purifier) {
            $value = $purifier->purify($value);
        });

        $request->merge($userInput);

        return $next($request);
    }
}
