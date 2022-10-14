<?php

namespace Tyondo\Innkeeper\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LandlordAuthenticate
{
    private static $params;
    /**
     * This middleware is used to ensure that the routes are only accessible to users who have managed to log into the system
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = null)
    {
        self::$params = $request->route()->parameters;
        if (! Auth::check()) {
            return redirect()->route('cirembo.console.login.form');
        }
        return $next($request);
    }
}
