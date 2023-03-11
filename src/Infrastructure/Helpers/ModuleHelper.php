<?php
/**
 * Created by PhpStorm.
 * User: raphael
 * Date: 2/7/18
 * Time: 8:14 PM
 */
namespace Tyondo\Innkeeper\Infrastructure\Helpers;

class ModuleHelper
{

    public static function createStorage($folderName, $useDate = false)
    {
        /*
        * This function is for creating folders organized by date for the storage of files
        call this function before any file created to set the dependencies
        --this function can be enhanced to look at the name for slashes so as to create subdirectories automatically
        */
        $today = null;
        $folder = "backup/".$folderName; // setting the folder name
        if ($useDate){
            $today = date('Y-m-d'); //setting the date
        }

        if (!is_dir(storage_path($folder)))
        {
            mkdir(storage_path($folder), 0777, true); //creating the folder docs if it does not already exist
        }
        if (!is_dir(storage_path($folder).'/'. $today))
        {
            //creating folder based on day if it does not exist. If it does, it is not created
            if (!mkdir(storage_path($folder).'/'. $today, 0777, true)) {
                die('Failed to create folders...'); // Die if the function mkdir cannot run
            }
            return $folder.'/'.$today;
        } elseif (is_dir(storage_path($folder).'/'. $today)){ //check if the folder is created and return it
            return $folder.'/'.$today;
        } else {
            return $folder.'/'.$today;				// Return the folder if its already created in the file system
        }
    }

    /**
     * GZIPs a file on disk (appending .gz to the name)
     *
     * From http://stackoverflow.com/questions/6073397/how-do-you-create-a-gz-file-using-php
     * Based on function by Kioob at:
     * http://www.php.net/manual/en/function.gzwrite.php#34955
     *
     * @param string $source Path to file that should be compressed
     * @param integer $level GZIP compression level (default: 9)
     * @param bool $fileFormat
     * @return string New filename (with .gz appended) if success, or false if operation fails
     * @internal param bool|string $format
     */
   public static function gzCompressFile($source, $level = 9, $fileFormat = false){
        if ($fileFormat){
            $destination = $source . '.gz';
        }else{
            $destination = $source;
        }
        $mode = 'wb' . $level;
        $error = false;
        if ($fp_out = gzopen($destination, $mode)) {
            if ($fp_in = fopen($source,'rb')) {
                while (!feof($fp_in))
                    gzwrite($fp_out, fread($fp_in, 1024 * 512));
                fclose($fp_in);
            } else {
                $error = true;
            }
            gzclose($fp_out);
        } else {
            $error = true;
        }
        if ($error)
            return false;
        else
            return $destination;
    }
}
