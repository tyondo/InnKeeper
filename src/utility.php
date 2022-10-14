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


if (! function_exists('getTagsList')){
    function getTagsList(){
       // return (new \Tyondo\EnumManager\Services\TagsManagerService())->getTagsList();
    }
}
