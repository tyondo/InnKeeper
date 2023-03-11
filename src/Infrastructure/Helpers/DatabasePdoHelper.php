<?php
/**
 * This helper class is meant to assist in; creating database, creating user, and granting them rights.
 * The reason for this is because i got quite frustrated trying to use laravel's DB facade to create new db's for a
 * multi-tenant app
 * the class can be extended to include errors mgs as described in : http://www.fromdual.com/mysql-error-codes-and-messages-1000-1049
 * Created by PhpStorm.
 * User: raphael
 * Date: 11/29/17
 * Time: 9:07 PM
 */

namespace Tyondo\Innkeeper\Infrastructure\Helpers;


use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


class DatabasePdoHelper
{

    private $dbHost;
    private $dbPort;
    private $dbUser;
    private $dbUserPassword;

    public function __construct($host, $port, $user, $password)
    {
        $this->dbHost = $host;
        $this->dbPort = $port;
        $this->dbUser = $user;
        $this->dbUserPassword = $password;
        $this->getPDOConnection(); //opening the connection

    }

    /**
     * @return mixed \PDO
     */
    private function getPDOConnection()
    {
        try {
            // $conn = new \PDO(sprintf('mysql:host=%s;port=%d;', env('DB_HOST'), env('DB_PORT')), env('DB_USERNAME'), env('DB_PASSWORD'));
            $conn = new \PDO(sprintf('mysql:host=%s;port=%d;', $this->dbHost, $this->dbPort), $this->dbUser, $this->dbUserPassword);
            // set the PDO error mode to exception
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $conn;
        }
        catch(\PDOException $e)
        {
            $message = null;
            switch ($e->errorInfo[1]){
                case 1064:
                    $message = [
                        'code' => 1064,
                        'message' => 'You have an error in your SQL syntax',
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
                    break;
                case 2002:
                    $message = [
                        'code' => 2002,
                        'message' => "Are you sure the server is up and the port: {$this->dbPort} open?",
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
                    break;
                case 2003:
                    $message = [
                        'code' => 2003,
                        'message' => "Network connection refused. Check if the following: server is running, network conn enabled and port configured correctly",
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
                    break;
                case 1045:
                    $message = [
                        'code' => 1045,
                        'message' => "Invalid username/password provided, try again",
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
                    break;
                default:
                    $message = [
                        'code' => $e->errorInfo[1],
                        'message' => 'Unexplained error',
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
            }
            return $message;
        }
    }

    public function closePdoConnection(){
        $conn = $this->getPDOConnection();
        $conn = null;
        return $conn;
    }


    public function pdoDoesDatabaseExist($database,$jsonResponse = false){
        try {
            $conn = $this->getPDOConnection();
            $stmt = $conn->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$database}'");
            $execResults = $stmt->fetchColumn();

            if($execResults == 1){
                if ($jsonResponse){
                    return [
                        'status' => 'success',
                        'response' => $execResults,
                        'message' => "database with the name: <{$database}> exists",
                    ];
                }
                return (int)true;

            }else{
                if ($jsonResponse){
                    return [
                        'status' => 'success',
                        'response' => $execResults,
                        'message' => "database with the name: <{$database}> does not exists",
                    ];
                }
                return (integer)false;
            }
        }
        catch(\PDOException $e)
        {
            $message = null;
            switch ($e->errorInfo[1]){
                case 1064:
                    $message = [
                        'code' => 1064,
                        'message' => 'You have an error in your SQL syntax',
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
                    break;
                default:
                    $message = [
                        'code' => $e->errorInfo[1],
                        'message' => 'Unexplained error',
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
            }
            return $message;
        }

    }
    public function pdoCreateDatabase($database = null){
        if (is_null($database)){
            $database = 'organization_test';
        }

        try {
            $conn = $this->getPDOConnection();
            Log::info("CREATE DATABASE {$database}");

            $execResults =  $conn->exec("CREATE DATABASE {$database}"); //using exec() since no results are returned

            if($execResults){

                return [
                    'status' => true,
                    'message' => "database with the name: {$database} created", //can return true
                ];
            }
        }
        catch(\PDOException $e)
        {
            $message = null;
            switch ($e->errorInfo[1]){
                case 1064:
                    $message = [
                        'code' => 1064,
                        'message' => 'You have an error in your SQL syntax',
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
                    break;
                case 1007:
                    $message = [
                        'code' => 1007,
                        'message' => 'The database you are creating already exists',
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
                    break;
                default:
                    $message = [
                        'code' => $e->errorInfo[1],
                        'message' => 'Unexplained error',
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
            }
            return $message;
        }

    }
    public function pdoDestroyDatabase($database = null,$jsonResponse = false){
        if ($this->pdoDoesDatabaseExist($database) === 1){
            try {
                $conn = $this->getPDOConnection();
                $execResults =  $conn->exec("DROP DATABASE {$database}"); //using exec() since no results are returned

                if($execResults){

                    return [
                        'status' => 'success',
                        'message' => "database with the name: {$database} has been dropped", //can return true
                        'data' => (object)[]
                    ];
                }
            }
            catch(\PDOException $e)
            {
                $message = null;
                switch ($e->errorInfo[1]){
                    case 1064:
                        $message = [
                            'code' => 1064,
                            'message' => 'You have an error in your SQL syntax',
                            'debugMessage' => $e->errorInfo[2],
                            'errorInfo' => $e->errorInfo
                        ];
                        break;
                    default:
                        $message = [
                            'code' => $e->errorInfo[1],
                            'message' => 'Unexplained error',
                            'debugMessage' => $e->errorInfo[2],
                            'errorInfo' => $e->errorInfo
                        ];
                }
                return [
                    'status' => 'fail',
                    'message' => "There was an issue dropping the given <{$database}> database",
                    'data' => [
                        'serverResponse' => $message,
                    ],
                ];
            }
        }
        return [
            'status' => 'fail',
            'message' => "database with the name '{$database}' does not exist for it to be dropped",
            'data' => (object)[]
        ];

    }

    public function pdoCreateDbUserAndGrant($database,$databaseUserName,$databaseUserPassword, $permissions){

        return [
            'createResponse' => $this->pdoCreateDbUser($databaseUserName,$databaseUserPassword),
            'grantResponse' => $this->pdoGrantUserPermissions($database,$databaseUserName,$permissions)
        ];
    }


    public function pdoCreateDbUser($databaseUserName,$databaseUserPassword){

        try {
            $conn = $this->getPDOConnection();
            $execResults = $conn->exec("CREATE USER '{$databaseUserName}'@'localhost' IDENTIFIED BY '{$databaseUserPassword}'"); //using exec() since no results are returned
            $conn = null; //closing the db connection
            if ($execResults === 0){
                return "db user: {$databaseUserName} with pwd: {$databaseUserPassword} created"; //can return true
            }
        }
        catch(\PDOException $e)
        {
            $message = null;
            switch ($e->errorInfo[1]){
                case 1064:
                    $message = [
                        'code' => 1064,
                        'message' => 'You have an error in your SQL syntax',
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
                    break;
                case 1396:
                    $message = [
                        'code' => 1396,
                        'message' => 'The user being created already exists in the db',
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
                    break;
                default:
                    $message = [
                        'code' => $e->errorInfo[1],
                        'message' => 'Unexplained error',
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
            }
            return $message;
        }
    }

    public function pdoGrantUserPermissions($database,$databaseUser,$permissions){
       // $database = 'organization_test';
       // $databaseUser = 'organization_test_user';
       // $permissions = 'ALL'; // can give the option as a string 'CREATE, INSERT' e.t.c options CREATE, DROP, DELETE, INSERT, SELECT, UPDATE, GRANT OPTION


        try {
            $conn = $this->getPDOConnection();
            $execResults = $conn->exec("GRANT {$permissions} ON {$database}.* TO '{$databaseUser}'@'localhost'"); // the * means all tables

            if ($execResults === 0){
                $conn->exec("FLUSH PRIVILEGES");
                //$conn = null;
                return "db user: {$databaseUser} granted access to : {$database} "; //can return true
            }
        }
        catch(\PDOException $e)
        {
            $message = null;
            switch ($e->errorInfo[1]){
                case 1064:
                    $message = [
                        'code' => 1064,
                        'message' => 'You have an error in your SQL syntax',
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
                    break;
                case 1133:
                    $message = [
                        'code' => 1133,
                        'message' => 'The user you are giving rights to does not exist in the db',
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
                    break;
                default:
                    $message = [
                        'code' => $e->errorInfo[1],
                        'message' => 'Unexplained error',
                        'debugMessage' => $e->errorInfo[2],
                        'errorInfo' => $e->errorInfo
                    ];
            }
            return $message;
        }
    }
    public function dumpDatabase($currentDb, $isBackup = false, $isCompressed = false){
        if ($this->pdoDoesDatabaseExist($currentDb) == 1){
            $fileName = $currentDb.".sql";
            if ($isBackup){
                $datedFileName = 'backup-db-'.$currentDb .'-'. date('d-m-Y_h:i:s').'.sql';
                if ($isCompressed)
                {
                    $datedFileName .= '.gz';
                    $filePath = storage_path(ModuleHelper::createStorage("databaseBackup/compressed").$datedFileName);
                    $this->createDump($currentDb,$filePath);
                    //compress it
                    ModuleHelper::gzCompressFile($filePath);
                    return [
                        'status' => 'success',
                        'message' => "Compressed backup of database <{$currentDb}> has been created",
                        'data' => [
                            'fileLocation' => $filePath
                        ]
                    ];

                }else{
                    $filePath = storage_path(ModuleHelper::createStorage("databaseBackup/raw").$datedFileName);
                    $this->createDump($currentDb,$filePath);
                    return [
                        'status' => 'success',
                        'message' => "Raw backup of database <{$currentDb}> has been created",
                        'data' => [
                            'fileLocation' => $filePath
                        ],
                    ];
                }
            }else{
                $filePath = storage_path(ModuleHelper::createStorage("databaseBackup").$fileName);
                 $this->createDump($currentDb,$filePath);
                return [
                    'status' => 'success',
                    'message' => "Database <{$currentDb}> has been exported",
                    'data' => [
                        'fileLocation' => $filePath,
                    ],
                ];
            }
        }
        return [
            'status' => 'fail',
            'message' => "The database with the name '{$currentDb}' does not exist for it to be exported",
            'data' => (object)[]
        ];
    }

    /***
     * This method is used for generating database backup using symphony's process class with Hedoc syntax
     * resource: https://github.com/symfony/symfony/issues/23782
     * @param $currentDb
     * @param $filePath
     * @return bool|string
     */
    private function createDump($currentDb,$filePath){

        $process = Process::fromShellCommandline("mysqldump --add-drop-table --default-character-set=utf8mb4 --host={$this->dbHost} --port={$this->dbPort} --user=$this->dbUser --password=$this->dbUserPassword $currentDb > $filePath");
        $process->mustRun();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return $process->getOutput();
    }

}
