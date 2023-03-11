<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
 * This methods creates a new .env variable or updates the existing one
 */
if (! function_exists('createOrUpdateEnvVariable')){
    function createOrUpdateEnvVariable(string $key,$value)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $file = file_get_contents($path);
            if(strpos($file, $key))
            {
                $old = env($key);
                file_put_contents($path, str_replace(
                    "$key=".$old, "$key=".$value, file_get_contents($path)
                ));
                return "$key=".$value;
            }else{
                file_put_contents($path, PHP_EOL ."$key=$value" , FILE_APPEND | LOCK_EX);
                return "$key=$value";
            }
        }
        return false;
    }
}

if (! function_exists('createEnvFileHeaders')){
    function createEnvFileHeaders(string $headerDescription){
        $path = base_path('.env');
        if (file_exists($path)) {
            $file = file_get_contents($path);
            if(!strpos($file, $headerDescription))
            {
                file_put_contents($path, PHP_EOL .$headerDescription , FILE_APPEND | LOCK_EX);
            }
        }
    }
}


if (! function_exists('updateEnvVariable')){
    function updateEnvVariable($key, $value)
    {
    file_put_contents(app()->environmentFilePath(), str_replace(
        $key . '=' . env($value),
        $key . '=' . $value,
        file_get_contents(app()->environmentFilePath())
    ));
}
}
