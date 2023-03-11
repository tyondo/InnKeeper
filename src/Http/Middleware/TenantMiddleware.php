<?php

namespace Tyondo\Innkeeper\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tyondo\Innkeeper\Infrastructure\Helpers\LandlordHelper;


class TenantMiddleware
{
    protected $org;
    protected $connectionInfo;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!app()->runningInConsole()){
            LandlordHelper::setTenantFromDomain($request);
        }
        return $next($request);
    }
}
