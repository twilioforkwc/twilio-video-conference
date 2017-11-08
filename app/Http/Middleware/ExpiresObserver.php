<?php

namespace App\Http\Middleware;

use Closure;
use App\MyLibs\ExpiresManager;

class ExpiresObserver
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
        $expires = new ExpiresManager();
        $expires->checkChannels();
        return $next($request);
    }
}
