<?php
/**
 * Created by PhpStorm.
 * User: Raphael Ndwiga
 * Date: 8/7/2020
 * Time: 8:48 PM
 */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

if (! function_exists('innkeeperAssets')){
    function innkeeperAssets(?string $custome = null){
        $path = "vendors/innkeeper/";
        if (!is_null($custome)){
            $path .= "$custome";
        }
        return asset($path).'/';
    }
}
if(! function_exists('routeActive')){
    function routeActive($routeName)
    {
        $isActiveList  =[];
        foreach (explode('|',$routeName) as $route){
            if (request()->routeIs($route)){
                array_push($isActiveList,'active');
            }
        }
        if (in_array("active", $isActiveList)){
            return 'active';
        }return '';
    }
}
