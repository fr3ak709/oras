<?php

namespace AIVIKS\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class amAdmin
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
        if( Auth::check() ) {
            if (  Auth::user()->role === 'Administrator' ) {
                return $next($request);
            }   
        }
        return abort(404);
    }
}
