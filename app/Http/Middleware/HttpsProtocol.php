<?php

namespace AIVIKS\Http\Middleware;

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
    {/* 
        if (( $request->header('X-Forwarded-Proto') !== 'https' ) && env('APP_ENV') === 'prod') {
            return redirect()->secure($request->getRequestUri());
        }*/
        return $next($request);  
    }
}
