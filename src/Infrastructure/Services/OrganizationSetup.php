<?php

namespace Tyondo\Innkeeper\Infrastructure\Services;

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;
use Tyondo\Cirembo\Permission\Helpers\AuthorizationHelper;
use Tyondo\Cirembo\Permission\Models\User;
use Tyondo\Innkeeper\Database\Models\Organization\Organization;
use Tyondo\Innkeeper\Database\Models\Organization\OrganizationConnection;
use Tyondo\Innkeeper\Database\Models\Organization\OrganizationUser;
use Tyondo\Innkeeper\Infrastructure\Helpers\DatabasePdoHelper;
use Tyondo\Innkeeper\Infrastructure\Helpers\LandlordHelper;

class OrganizationSetup
{
    protected string $name;
    protected string $slug;
    protected string $databaseName;
    protected array $params;
    protected OrganizationUser $tenantUser;
    protected Organization $org;
    protected DatabasePdoHelper $orgDb;
    protected OrganizationConnection $connectionInfo;

    public function initOrganizationSetup(array $userOrganizationDetails){
        $this->tenantUser = new OrganizationUser();
        $this->org = new Organization();
        $this->connectionInfo = new OrganizationConnection();
        $this->params = $userOrganizationDetails;

       return $this->run();
    }

    public function run(){
        $this->createOrganization();
        $this->createDatabaseAndConnection();
        if ($this->params['management_status'] != 'manage_organization'){
            $this->runMigrations();
            $this->createUserInTenant();
        }
        return $this->org;
    }

    protected function createOrganization(){

        $this->slug = self::camelCase($this->params['organization_name']);
        $this->databaseName = "innkeeper_{$this->slug}";

        $this->org->fill([
            'organization_uid' => Uuid::uuid4(),
            'organization_account' => time(),
            'management_status'=> $this->params['management_status'],
            'name' => $this->params['organization_name'],
            'domain' => $this->params['organization_domain'],
            'slug' =>  $this->slug
        ]);

        $this->tenantUser->fill([
            'user_uid' => Uuid::uuid4(),
            'first_name' => $this->params['first_name'],
            'last_name' => $this->params['last_name'],
            'user_status' => $this->params['organization_status'],
            'mobile_number' => $this->params['mobile_number'],
            'email' => $this->params['email'],
            'password' => bcrypt($this->params['organization_name'])
        ]);

        $this->org->save();
        $this->org->users()->save($this->tenantUser);
    }

    protected function createDatabaseAndConnection(){
        //check if the creation status is set as manage
        if ($this->params['management_status'] == 'manage_organization'){
            DB::connection('innkeeper')->table('organization_connections')->insert(
                [
                    'organization_id' => $this->org->id,
                    'dbUsername' => config("database.connections.mysql.username"),
                    'dbHostname' => config("database.connections.mysql.host"),
                    'dbPassword' => config("database.connections.mysql.password"),
                    'dbName' => $this->databaseName,
                    'created_at' => Carbon::now(),
                ]);
        }else{
            //creating the organization db
            $innkeeperDbEngine = config('innkeeper.tenant-db-server');
            $dbHost = config("database.connections.mysql.host");
            $dbPort = config("database.connections.mysql.port");
            $dbUsername = config("database.connections.mysql.username");
            $dbPassword = config("database.connections.mysql.password");
            $dbName = $this->databaseName;
            $pleskSiteId = config('innkeeper.connections.innkeeper.plesk.web_space_id');
            if ($innkeeperDbEngine === 'mysql'){
                //TODO:: indicate this as the database administrative credentials for use
                $orgDb = new DatabasePdoHelper($dbHost,$dbPort,$dbUsername,$dbPassword);
                $result = $orgDb->pdoCreateDatabase($dbName); //TODO::log this call

                //$charset = config("database.connections.mysql.charset",'utf8mb4');
                //$collation = config("database.connections.mysql.collation",'utf8mb4_unicode_ci');
                //$query = "CREATE DATABASE IF NOT EXISTS $dbName CHARACTER SET $charset COLLATE $collation;";
                //DB::statement($query);

            }elseif ($innkeeperDbEngine == 'plesk'){
                $pleskServer = new PleskServerDbSetup();
                $pleskServer->setWebSpaceId($pleskSiteId)->setDbName($dbName)
                    ->setDbUsername($dbName)->setDbUserPassword($dbPassword)
                    ->setUpDatabaseAndUser();
            }
            DB::connection('innkeeper')->table('organization_connections')->insert(
                [
                    'organization_id' => $this->org->id,
                    'dbUsername' => $dbUsername,
                    'dbHostname' => $dbHost,
                    'dbPassword' => $dbPassword,
                    'dbName' => $dbName,
                    'created_at' => Carbon::now(),
                ]);
            LandlordHelper::setTenantFromSlug($this->slug);
        }

    }


    protected function runMigrations(){
        $migrationConfigs = config('innkeeper.migrations');
        $commandList = $migrationConfigs['commands'];
        foreach ($commandList as $item){
            $args = [
                '--no-interaction' => true,
                '--database' => $migrationConfigs['connection'],
            ];
            if (str_contains($item,'migrate')){
                $args['--path'] = $migrationConfigs['path'];
            }
            Artisan::call($item,$args);
        }
        $this->executeOtherSetupCommands();
    }

    protected function executeOtherSetupCommands(){
        $commandList = config('innkeeper.execute.commands');
        foreach ($commandList as $item){
            Artisan::call($item,[
                '--no-interaction' => true,
            ]);
        }
    }

    protected function createUserInTenant(){
        $this->checkingAndUpdatingUserTable();
          $id=  DB::connection('tenant')->table('users')->insertGetId(
                [
                    'user_uid' => Uuid::uuid4(),
                    'first_name' => $this->params['first_name'],
                    'last_name' => $this->params['last_name'],
                    'mobile_number' => $this->params['mobile_number'],
                    'email' => $this->params['email'],
                    'password' => bcrypt($this->params['organization_name']),
                    'created_at' => Carbon::now(),
                ]);
          new AuthorizationHelper(User::findOrFail($id));
    }

    private function checkingAndUpdatingUserTable(){
        $columns = [
            'user_uid','first_name','last_name','mobile_number','email',
            'password','created_at'
        ];
        if (Schema::connection('tenant')->hasTable('users')) {
            foreach ($columns as $column){
                if (!Schema::connection('tenant')->hasColumn('users', $column)) {
                    Schema::connection('tenant')->table('users', function (Blueprint $table) use (&$column) {
                        $table->string($column)->nullable();
                    });
                }
            }
        }
    }

    /**
     * This function is used to convert a string into a camelCase string
     * e.g it converts Hello World to helloWorld. It can be used in creating database names
     * @param $str
     * @param array $noStrip
     * @return mixed|string
     */
    public static function camelCase($str, array $noStrip = [])
    {
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);
        $str = str_replace(" ", "", $str);
        $str = lcfirst($str);

        return $str;
    }
}
