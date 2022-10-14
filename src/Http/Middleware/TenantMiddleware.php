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

       // ddd($server = explode('.', $request->getHttpHost()));
        /*$server = explode('.', $request->getHttpHost()); //the $request->getHttpHost() will return something like dev.site.com
        //to include http/https part of the url we would have used  $request->getSchemeAndHttpHost() to return http://dev.site.com
        if (count($server) === 3 && $server !== 'www'){
            //if the number of segments is equal to 3 and the first segment is not equal to www
            $this->org = Organization::where('slug', $server[0])->firstOrFail();

        }
        if (!app()->runningInConsole()){

            // Close any connection made before to avoid conflicts
            // Close any connection made before to avoid conflicts
            DB::disconnect('tenant');
            DB::disconnect('main');
            DB::purge('tenant');

            Config::set('database.connections.tenant.host', $this->org->orgConnection['host']);
            Config::set('database.connections.tenant.username', $this->org->orgConnection ->username);
            Config::set('database.connections.tenant.password', $this->org->orgConnection ->password);
            Config::set('database.connections.tenant.database', $this->org->orgConnection ->database);

//If you want to use query builder without having to specify the connection
            Config::set('database.default', 'tenant');
            DB::reconnect('tenant');*/
           // view()->share('subdomain', $server[0]); //setting variable to be used in views
           // $request->route()->setParameter('subdomain', $server[0]); //setting the subdomain name for use within controllers

      //  }*/
        if (!app()->runningInConsole()){
            LandlordHelper::setTenantFromDomain($request);
        }

        return $next($request);
    }
}
