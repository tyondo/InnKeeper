<?php
/**
 * Created by PhpStorm.
 * User: raphael
 * Date: 2/21/18
 * Time: 11:12 AM
 */

namespace Tyondo\Innkeeper\Infrastructure\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tyondo\Innkeeper\Database\Models\Organization\Organization;

class LandlordHelper
{
    public static $organization;
    public static $tenantSlug;

    public static function setTenantFromDomain(Request $request){
        $server = explode('.', $request->getHttpHost()); //the $request->getHttpHost() will return something like dev.site.com
        $request->merge([
            'domain' => $request->getHttpHost()
        ]);

        //to include http/https part of the url we would have used  $request->getSchemeAndHttpHost() to return http://dev.site.com
        if (count($server) >= 3 && $server !== 'www'){
            //if the number of segments is equal to 3 and the first segment is not equal to www
            self::$tenantSlug = $server[0];

            self::setTenant();
            self::setTenantConnection();
        }
        //return redirect()->to(env('APP_NAME'));
        //TODO:- Add checker that if the tenant does not exist, they are redirected somewhere else
    }

    public static function setTenantFromSlug($domainSlug){
        self::$tenantSlug = $domainSlug;
        self::setTenant();
        self::setTenantConnection();
    }

    public static function getAllTenants(){
       return Organization::all(['id','name', 'slug','management_status'])->toArray();
    }


    private static function setTenant(){
        self::$organization = Organization::where('slug', self::$tenantSlug)->firstOrFail();
    }

    private static function setTenantConnection(){

        $databaseConnection = [
            'driver'    => 'mysql',
            'host'      => self::$organization->orgConnection['dbHostname'],
            'database'  => self::$organization->orgConnection['dbName'],
            'username'  => self::$organization->orgConnection['dbUsername'],
            'password'  => self::$organization->orgConnection['dbPassword'],
            // 'charset'   => $config['db']['charset'],
            // 'collation' => $config['db']['collation'],
            // 'prefix'    => $config['db']['prefix'],
        ];
        //TODO:: Clean this implementation
        Config::set('database.connections.tenant', $databaseConnection);
        Config::set('database.connections.mysql', $databaseConnection);
        // set default connection for this $host
        DB::purge('mysql');
        DB::reconnect('mysql');
        DB::setDefaultConnection('mysql');

        //TODO:: Log and report the problem
    }
}
