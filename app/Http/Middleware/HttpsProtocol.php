<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol
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
        if ($_SERVER["HTTP_HOST"] === 'localhost:8000') {
            return $next($request);
        }
        if (!$this->is_ssl()) {
            return redirect()->secure($request->getRequestUri());
        }
        // if (!$this->is_ssl() && !(\Request::path() === 'login') && !(\Request::path() === 'register')) {
        //     return redirect()->secure($request->getRequestUri());
        // }
        return $next($request);
    }

    /**
     * Determine if the protocol is HTTPS.
     * @return bool
     */
    public function is_ssl()
    {
        if ( isset($_SERVER['HTTPS']) === true ) // Apache
        {
            return ( $_SERVER['HTTPS'] === 'on' or $_SERVER['HTTPS'] === '1' );
        }
        elseif ( isset($_SERVER['SSL']) === true ) // IIS
        {
            return ( $_SERVER['SSL'] === 'on' );
        }
        elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) === true ) // Reverse proxy
        {
            return ( strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https' );
        }
        elseif ( isset($_SERVER['HTTP_X_FORWARDED_PORT']) === true ) // Reverse proxy
        {
            return ( $_SERVER['HTTP_X_FORWARDED_PORT'] === '443' );
        }
        elseif ( isset($_SERVER['SERVER_PORT']) === true )
        {
            return ( $_SERVER['SERVER_PORT'] === '443' );
        }

        return false;
    }
}
